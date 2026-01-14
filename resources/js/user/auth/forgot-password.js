import '@css/auth/forgot-password.css';
let countdownInterval;
let secondsLeft = 0;

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}


document.querySelector("form").addEventListener("submit", function (e) {
    const btn = document.getElementById("reset-btn");
    const text = document.getElementById("button-text");

    // Prevent multiple submissions
    if (secondsLeft > 0) {
        e.preventDefault(); 
        return;
    }

    // Start cooldown
    secondsLeft = 60;
    btn.disabled = true;
    updateButtonText();

    countdownInterval = setInterval(() => {
        secondsLeft--;
        updateButtonText();

        if (secondsLeft <= 0) {
            clearInterval(countdownInterval);
            btn.disabled = false;
            updateButtonText();
        }
    }, 1000);
});


function updateButtonText() {
    const buttonText = document.getElementById('button-text');
    if (secondsLeft > 0) {
        buttonText.textContent = `Wait (${secondsLeft}s)`;
    } else {
        buttonText.textContent = "Send Reset Link";
    }
}

function showSuccess(msg) {
    const el = document.getElementById('successMessage');
    el.textContent = msg;
    el.classList.remove('hidden');
}

function showError(msg) {
    const el = document.getElementById('emailError');
    el.textContent = msg;
    el.classList.remove('hidden');
}

document.querySelectorAll('.floating-element').forEach((el) => {
    const duration = 6 + Math.random() * 6;
    const delay = Math.random() * 5;
    el.style.animationDuration = `${duration}s`;
    el.style.animationDelay = `${delay}s`;
});

//  Responsive card layout
function adjustLayout() {
    const card = document.querySelector('.password-reset-card');
    if (window.innerHeight < 700) {
        card.classList.add('compact-mode');
    } else {
        card.classList.remove('compact-mode');
    }
}
adjustLayout();
window.addEventListener('resize', adjustLayout);