import '@css/auth/register.css';

  // Your Firebase Config
  const firebaseConfig = {
    apiKey: "AIzaSyADvYp5oPU2Cbz2XVNIfsDi50es-x1dYSc",
    authDomain: "luxorix-ce283.firebaseapp.com",
    projectId: "luxorix-ce283",
    storageBucket: "luxorix-ce283.firebasestorage.app",
    messagingSenderId: "787173165111",
    appId: "1:787173165111:web:4b699e0512d37a9649d7b2",
    measurementId: "G-C6CY373ZQ1"
  };

  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  const auth = firebase.auth();

  async function signInWithGoogle() {
  const provider = new firebase.auth.GoogleAuthProvider();

  try {
    const result = await auth.signInWithPopup(provider);
    const token = await result.user.getIdToken();

    const response = await fetch('/google-login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ token: token })
    });

    let data;
    const contentType = response.headers.get('content-type');
    
    if (contentType && contentType.includes('application/json')) {
      data = await response.json();
    } else {
      throw new Error("Server did not return JSON");
    }

    if (data.success) {
      window.location.href = "/";
    } else {
      alert("Login failed: " + data.error);
    }

  } catch (error) {
    console.error(error);
    alert("Google Sign-In Error: " + error.message);
  }
}



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
            const duration = 6 + Math.random() * 6;
            const delay = Math.random() * 5;
            el.style.animationDuration = `${duration}s`;
            el.style.animationDelay = `${delay}s`;
        });
        
        // Adjust layout on window resize
        function adjustLayout() {
            const card = document.querySelector('.register-card');
            if (window.innerHeight < 700) {
                card.classList.add('compact-mode');
            } else {
                card.classList.remove('compact-mode');
            }
        }
        
        // Initial adjustment
        adjustLayout();
        
        window.addEventListener('resize', adjustLayout);