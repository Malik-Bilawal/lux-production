import '@css/auth/check-email.css';

const emailDisplay = document.getElementById('email-display');
emailDisplay.textContent = "{{ session('email') }}";

// Resend Email Logic
const resendBtn = document.getElementById('resend-btn');
const countdownEl = document.getElementById('countdown');
const resendMessage = document.getElementById('resend-message');
const resendEmailInput = document.querySelector("input[name='email']");

let canResend = true;
let countdownInterval;

resendBtn.addEventListener('click', function (e) {
    e.preventDefault();
    if (!canResend) return;

    canResend = false;
    resendBtn.classList.add('opacity-75', 'cursor-not-allowed');
    countdownEl.classList.remove('hidden');
    resendMessage.classList.remove('hidden');
    setTimeout(() => resendMessage.classList.add('hidden'), 5000);

    let seconds = 60;
    countdownEl.textContent = seconds;

    countdownInterval = setInterval(() => {
        seconds--;
        countdownEl.textContent = seconds;
        if (seconds <= 0) {
            clearInterval(countdownInterval);
            canResend = true;
            resendBtn.classList.remove('opacity-75', 'cursor-not-allowed');
            countdownEl.classList.add('hidden');
        }
    }, 1000);

    const formData = new FormData();
    formData.append("email", resendEmailInput.value.trim());

    fetch("{{ route('resend.verification') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: formData
    })
    .then(async res => {
        const data = await res.json();
        if (!res.ok) {
            alert(data.message || "Cannot send email right now.");
            return;
        }
        resendMessage.textContent = "Email resent successfully! Please check your inbox.";
        resendMessage.classList.remove('hidden');
        setTimeout(() => resendMessage.classList.add('hidden'), 5000);
    })
    .catch(err => {
        console.error("Resend error:", err.message);
        alert("Something went wrong while resending the email.");
    });
});

// Change Email Logic
const changeEmailTrigger = document.getElementById('change-email-trigger');
const changeEmailForm = document.getElementById('change-email-form');
const cancelChange = document.getElementById('cancel-change');
const updateEmailForm = document.getElementById('update-email-form');
const successMessage = document.getElementById('success-message');

changeEmailTrigger.addEventListener('click', function (e) {
    e.preventDefault();
    changeEmailForm.classList.remove('hidden');
});

cancelChange.addEventListener('click', function () {
    changeEmailForm.classList.add('hidden');
});

const loader = document.getElementById('loader'); // loader ko access karo

updateEmailForm.addEventListener('submit', function (e) {
e.preventDefault();

const newEmail = document.getElementById('new-email').value.trim();
const oldEmail = emailDisplay.textContent.trim();

loader.classList.remove('hidden'); // Loader dikhana start pe

fetch("{{ route('update.email') }}", {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "X-CSRF-TOKEN": "{{ csrf_token() }}"
    },
    body: JSON.stringify({ old_email: oldEmail, new_email: newEmail })
})
.then(async res => {
    const data = await res.json();

    loader.classList.add('hidden'); //  Loader hide after response

    if (!res.ok) {
        if (data?.errors?.new_email?.[0]) {
            alert(data.errors.new_email[0]);
        } else if (data?.message) {
            alert(data.message);
        } else {
            alert("Something went wrong while updating the email.");
        }
        return;
    }

    // Success
    emailDisplay.textContent = data.email;
    resendEmailInput.value = data.email;
    changeEmailForm.classList.add('hidden');
    successMessage.classList.remove('hidden');
    updateEmailForm.reset();

    setTimeout(() => {
        successMessage.classList.add('hidden');
    }, 6000);
})
.catch(err => {
    console.error("Update error:", err.message);
    alert("An unexpected error occurred while updating the email.");
    loader.classList.add('hidden');
});
});
