document.addEventListener('DOMContentLoaded', () => {
    // Password toggle utility
    const togglePass = document.getElementById('togglePassword');
    if (togglePass) {
        togglePass.addEventListener('click', function() {
            const passInput = document.getElementById('password');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                this.textContent = 'Hide';
            } else {
                passInput.type = 'password';
                this.textContent = 'Show';
            }
        });
    }
});