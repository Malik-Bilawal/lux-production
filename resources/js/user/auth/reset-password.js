import '@css/auth/reset-password.css';

  // Toggle password visibility
  function togglePassword(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);
    
    // Toggle eye icon
    const icon = passwordField.nextElementSibling.querySelector('i');
    if (type === 'password') {
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    } else {
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }
}

// Animated background elements
document.querySelectorAll('.floating-element').forEach((el, index) => {
    // Randomize animation duration and delay
    const duration = 6 + Math.random() * 6;
    const delay = Math.random() * 5;
    el.style.animationDuration = `${duration}s`;
    el.style.animationDelay = `${delay}s`;
});

// Password strength checker
const passwordInput = document.getElementById('new-password');
const confirmInput = document.getElementById('confirm-password');
const passwordMatch = document.getElementById('password-match');

  
    


    
    
    // Check password match
    checkPasswordMatch();


confirmInput.addEventListener('input', checkPasswordMatch);

function checkPasswordMatch() {
    if (passwordInput.value && confirmInput.value) {
        if (passwordInput.value === confirmInput.value) {
            passwordMatch.classList.remove('hidden');
            passwordMatch.innerHTML = '<i class="fas fa-check-circle text-green-400 mr-1"></i><span>Passwords match</span>';
        } else {
            passwordMatch.classList.remove('hidden');
            passwordMatch.innerHTML = '<i class="fas fa-times-circle text-red-400 mr-1"></i><span>Passwords do not match</span>';
        }
    } else {
        passwordMatch.classList.add('hidden');
    }
}

function updateRequirement(type, met) {
    const requirement = document.querySelector(`.requirement[data-check="${type}"]`);
    if (met) {
        requirement.innerHTML = `<i class="fas fa-check-circle text-green-400 mr-2"></i><span>${requirement.textContent}</span>`;
    } else {
        requirement.innerHTML = `<i class="fas fa-times-circle text-red-400 mr-2"></i><span>${requirement.textContent}</span>`;
    }
}

// Form submission with success message
document.getElementById('reset-button').addEventListener('click', function() {
    const password = passwordInput.value;
    const confirm = confirmInput.value;
    
    if (password && password === confirm && password.length >= 8) {
        const successMessage = document.getElementById('success-message');
        successMessage.classList.remove('hidden');
        
        // Hide the form elements
        document.querySelectorAll('.form-group, .bg-primary').forEach(el => {
            el.style.opacity = '0.3';
        });
        this.style.opacity = '0.3';
        this.disabled = true;
        
        setTimeout(() => {
            successMessage.classList.add('flex');
        }, 300);
    } else {
        this.classList.add('animate-pulse');
        setTimeout(() => {
            this.classList.remove('animate-pulse');
        }, 500);
    }
});

function adjustLayout() {
    const card = document.querySelector('.password-reset-card');
    if (window.innerHeight < 700) {
        card.classList.add('compact-mode');
    } else {
        card.classList.remove('compact-mode');
    }
}

// Initial adjustment
adjustLayout();

// Adjust on window resize
window.addEventListener('resize', adjustLayout);