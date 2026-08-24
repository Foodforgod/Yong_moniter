# 🌐 Website Monitor

A lightweight, efficient website monitoring tool designed to track uptime, response times, and status codes of target URLs, sending automated alerts when downtime or service degradation is detected.

---

## ✨ Key Features

* Uptime Tracking: Continuously pings specified websites at regular, configurable intervals to check availability.
* Performance Metrics: Tracks response times (latency) to evaluate server health and performance trends over time.
* Status Code Inspection: Verifies HTTP response codes (e.g., 200 OK, 404 Not Found, 500 Server Error) to ensure accurate service health.
* Alert Notifications: Automatically triggers alerts (such as email or webhook notifications) when a monitored site goes down or recovers.
* Dashboard Overview: Provides a clean interface or log output to view the real-time status of all configured targets.

---

## 📂 System File Directory

| Path / File | Description |
| :--- | :--- |
| monitor.py or index.js | Core monitoring engine that performs health checks and handles intervals. |
| config.json | Configuration file containing target URLs, check intervals, and alert webhooks. |
| logger.log / database | Stores historical uptime data, response times, and incident logs. |
| alerts.py / notification module | Handles sending notifications via email, Slack, or Discord webhooks. |
| README.md | Project documentation and setup instructions. |

---

## 🛠️ Tech Stack

* Backend Language: Python / Node.js (depending on your implementation setup)
* Networking: HTTP/HTTPS requests libraries (e.g., requests in Python or axios in Node.js)
* Storage: JSON files, SQLite, or lightweight databases for historical logs

---

## ⚙️ Step-by-Step Guide: How to Use This Project

### Step 1: Local Environment Setup

<img width="945" height="212" alt="image" src="https://github.com/user-attachments/assets/a1b4ce51-f75f-43e7-b8a9-cb41e44572cf" />

1. Clone or download the repository to your local machine or server.
2. Ensure you have the required runtime environment installed (e.g., Python 3.x or Node.js).

### Step 2: Install Dependencies
<img width="1917" height="1006" alt="image" src="https://github.com/user-attachments/assets/bcb5fc4b-31c2-4606-b4c5-c21a96edeede" />

1. Open your terminal inside the project root directory.



2. Install the necessary packages (if using Python):
   pip install -r requirements.txt
   (Or if using Node.js: npm install)

### Step 3: Configure Target Websites
1. Open the configuration file (config.json).

<img width="1897" height="885" alt="image" src="https://github.com/user-attachments/assets/29bda38b-60aa-4e16-b48a-de8f4e23158d" />

2. Add the URLs you want to monitor, along with your preferred check interval:
   {
     "targets": [
       "https://example.com",
       "https://your-portfolio.com"
     ],
     "interval_seconds": 60
   }

### Step 4: Set Up Alert Credentials (Optional)

<img width="1917" height="785" alt="image" src="https://github.com/user-attachments/assets/9d4750a1-c914-4d06-b797-c0c54a4b99c7" />

1. Update the notification settings in your config or environment variables file (.env).
2. Add your webhook URL or SMTP mail server details if you wish to receive downtime alerts.

### Step 5: Run the Monitor
<img width="1210" height="380" alt="image" src="https://github.com/user-attachments/assets/d2c03c92-cfb9-4072-bc08-98338138f65e" />

1. Start the monitoring script from your terminal:
   python monitor.py
   (Or npm start for Node.js)
2. Watch the console logs as it actively pings your targets and reports status codes and response latencies in real time.

---

## 📄 License

This project is open-source and available under the MIT License.
