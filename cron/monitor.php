<?php
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/telegram.php';

$websites = $pdo->query("SELECT * FROM websites WHERE enabled = 1")->fetchAll();
$tg = $pdo->query("SELECT * FROM telegram_config WHERE id = 1")->fetch();

foreach ($websites as $site) {
    // Check interval constraints
    if ($site['last_checked']) {
        $next_check = strtotime($site['last_checked']) + ($site['monitoring_interval'] * 60);
        if (time() < $next_check) continue;
    }

    $url = $site['url'];
    $start_time = microtime(true);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $error_msg = curl_error($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $total_time = round((microtime(true) - $start_time) * 1000);
    curl_close($ch);

    // Determine status logic
    $new_status = 'UP';
    if ($error_msg || $http_code >= 400 || $http_code == 0) {
        $new_status = 'DOWN';
    } elseif ($total_time > $site['slow_threshold']) {
        $new_status = 'SLOW';
    }

    $prev_status = $site['current_status'];

    // Insert log
    $stmt = $pdo->prepare("INSERT INTO monitoring_logs (website_id, status, response_time, http_status_code, error_message) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$site['id'], $new_status, $total_time, $http_code, $error_msg ?: null]);

    // Update website state
    $stmt = $pdo->prepare("UPDATE websites SET current_status = ?, last_checked = NOW(), response_time = ?, http_status_code = ? WHERE id = ?");
    $stmt->execute([$new_status, $total_time, $http_code, $site['id']]);

    // Status change detection & Telegram notification
    if ($new_status !== $prev_status) {
        // Incident Tracking
        if ($prev_status === 'UP' && ($new_status === 'DOWN' || $new_status === 'SLOW')) {
            $stmt = $pdo->prepare("INSERT INTO incidents (website_id, previous_status, current_status, response_time) VALUES (?, ?, ?, ?)");
            $stmt->execute([$site['id'], $prev_status, $new_status, $total_time]);
        } elseif (($prev_status === 'DOWN' || $prev_status === 'SLOW') && $new_status === 'UP') {
            $stmt = $pdo->prepare("UPDATE incidents SET resolved_at = NOW() WHERE website_id = ? AND resolved_at IS NULL ORDER BY created_at DESC LIMIT 1");
            $stmt->execute([$site['id']]);
        }

        // Send Telegram alert
        if ($tg['enabled']) {
            $time_str = date('d M Y, h:i A');
            if ($new_status === 'DOWN') {
                $msg = "🚨 <b>WEBSITE DOWN</b>\n\nWebsite: {$site['name']}\nURL: $url\nStatus: DOWN\nTime: $time_str";
            } elseif ($new_status === 'UP' && $prev_status !== 'UP') {
                $msg = "✅ <b>WEBSITE RECOVERED</b>\n\nWebsite: {$site['name']}\nURL: $url\nStatus: UP\nResponse Time: {$total_time} ms\nTime: $time_str";
            } elseif ($new_status === 'SLOW') {
                $msg = "⚠️ <b>SLOW RESPONSE</b>\n\nWebsite: {$site['name']}\nURL: $url\nResponse Time: {$total_time} ms\nThreshold: {$site['slow_threshold']} ms";
            }
            if (isset($msg)) {
                send_telegram_alert($tg['bot_token'], $tg['chat_id'], $msg);
            }
        }
    }
}
echo "Monitoring execution finished.";