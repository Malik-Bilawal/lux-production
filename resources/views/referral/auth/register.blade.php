<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Our Referral Program</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        .step {
            display: none;
            opacity: 0;
            transform: translateX(20px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .step.active {
            display: block;
            opacity: 1;
            transform: translateX(0);
        }
        .progress-bar {
            height: 6px;
            border-radius: 10px;
            transition: width 0.5s ease;
        }
        .step-indicator {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .step-indicator.active {
            background-color: #4f46e5;
            color: white;
        }
        .step-indicator.completed {
            background-color: #10b981;
            color: white;
        }
        .form-card {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .info-card {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.3), 0 10px 10px -5px rgba(79, 70, 229, 0.2);
        }
        .benefit-item {
            transition: transform 0.2s ease;
        }
        .benefit-item:hover {
            transform: translateY(-2px);
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-50 to-purple-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl form-card w-full max-w-6xl overflow-hidden flex flex-col md:flex-row">
        <!-- Left Side - Program Information -->
        <div class="info-card text-white p-8 md:p-12 md:w-1/2 flex flex-col justify-between">
            <div>
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center mr-3">
                        <i class="fas fa-handshake text-white"></i>
                    </div>
                    <h2 class="text-xl font-bold">ReferralProgram</h2>
                </div>
                
                <h1 class="text-3xl md:text-4xl font-bold mb-4">Earn With Our Referral Program</h1>
                <p class="text-lg text-indigo-100 mb-8">Join thousands of affiliates who are earning commissions by promoting our products. Start earning today with our industry-leading commission structure.</p>
                
                <div class="space-y-6 mb-10">
                    <div class="benefit-item flex items-start bg-white bg-opacity-10 p-4 rounded-xl">
                        <div class="bg-white bg-opacity-20 p-2 rounded-lg mr-4">
                            <i class="fas fa-percentage text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg mb-1">Up to 30% Commission</h3>
                            <p class="text-indigo-100">Industry-leading commission rates on all sales you refer.</p>
                        </div>
                    </div>
                    
                    <div class="benefit-item flex items-start bg-white bg-opacity-10 p-4 rounded-xl">
                        <div class="bg-white bg-opacity-20 p-2 rounded-lg mr-4">
                            <i class="fas fa-chart-line text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg mb-1">Real-time Analytics</h3>
                            <p class="text-indigo-100">Track your performance with our comprehensive dashboard.</p>
                        </div>
                    </div>
                    
                    <div class="benefit-item flex items-start bg-white bg-opacity-10 p-4 rounded-xl">
                        <div class="bg-white bg-opacity-20 p-2 rounded-lg mr-4">
                            <i class="fas fa-gift text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg mb-1">Exclusive Promotions</h3>
                            <p class="text-indigo-100">Access special offers and bonuses for top performers.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white bg-opacity-10 p-5 rounded-xl">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 rounded-full bg-yellow-500 flex items-center justify-center mr-3">
                        <i class="fas fa-star text-white"></i>
                    </div>
                    <div>
                        <p class="font-bold">"I've earned over $5,000 in my first 3 months!"</p>
                        <p class="text-sm text-indigo-100">- Sarah Johnson, Top Affiliate</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Registration Form -->
        <div class="p-8 md:p-12 md:w-1/2">
            <!-- Progress Bar -->
            <div class="bg-gray-200 h-2 w-full rounded-full mb-8">
                <div id="progress" class="progress-bar bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full" style="width: 33.33%"></div>
            </div>
            
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Join Our Referral Program</h1>
                <p class="text-gray-600 mt-2">Start earning commissions in just a few steps</p>
                
                <!-- Step Indicators -->
                <div class="flex justify-between mt-8 mb-2">
                    <div class="flex flex-col items-center">
                        <div id="step1-indicator" class="step-indicator active">1</div>
                        <span class="text-sm font-medium mt-2 text-indigo-600">Account</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div id="step2-indicator" class="step-indicator">2</div>
                        <span class="text-sm font-medium mt-2 text-gray-500">Profile</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div id="step3-indicator" class="step-indicator">3</div>
                        <span class="text-sm font-medium mt-2 text-gray-500">Social</span>
                    </div>
                </div>
            </div>
            
            <!-- Form Container -->
            <div>
                <form id="registration-form">
                    <!-- Step 1: Account Details -->
                    <div id="step1" class="step active">
                        <div class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" id="name" name="name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <input type="email" id="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                            
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <input type="password" id="password" name="password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                            
                            <div>
                                <label for="confirm-password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                                <input type="password" id="confirm-password" name="password_confirmation" required class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 2: Profile Details -->
                    <div id="step2" class="step">
                        <div class="space-y-6">
                            <div>
                                <label for="profile-picture" class="block text-sm font-medium text-gray-700 mb-1">Profile Picture (Optional)</label>
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" id="profile-picture" name="profile-picture" accept="image/*" class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country (Optional)</label>
                                <select id="country" name="country" class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                    <option value="">Select your country</option>
                                    <option value="US">United States</option>
                                    <option value="UK">United Kingdom</option>
                                    <option value="CA">Canada</option>
                                    <option value="AU">Australia</option>
                                    <option value="DE">Germany</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Account Type</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="relative">
                                        <input type="radio" id="student" name="type" value="student" class="sr-only" checked>
                                        <label for="student" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-300 transition input-focus">
                                            <i class="fas fa-graduation-cap text-2xl text-indigo-500 mb-2"></i>
                                            <span class="font-medium">Student</span>
                                        </label>
                                    </div>
                                    <div class="relative">
                                        <input type="radio" id="creator" name="type" value="creator" class="sr-only">
                                        <label for="creator" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-300 transition input-focus">
                                            <i class="fas fa-palette text-2xl text-purple-500 mb-2"></i>
                                            <span class="font-medium">Creator/Seller</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label for="niche" class="block text-sm font-medium text-gray-700 mb-1">Niche</label>
                                <select id="niche" name="niche" required class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                    <option value="">Select your niche</option>
                                    <option value="art">Art & Design</option>
                                    <option value="tech">Technology</option>
                                    <option value="education">Education</option>
                                    <option value="fashion">Fashion</option>
                                    <option value="lifestyle">Lifestyle</option>
                                    <option value="business">Business</option>
                                    <option value="gaming">Gaming</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 3: Social Details -->
                    <div id="step3" class="step">
                        <div class="space-y-6">
                            <div>
                                <label for="follower-count" class="block text-sm font-medium text-gray-700 mb-1">Approximate Follower Count (Optional)</label>
                                <input type="number" id="follower-count" name="follower-count" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="e.g., 10000">
                            </div>
                            
                            <div>
                                <label for="social-platform" class="block text-sm font-medium text-gray-700 mb-1">Your Best Platform (Optional)</label>
                                <select id="social-platform" name="social-platform" class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                    <option value="">Select your platform</option>
                                    <option value="instagram">Instagram</option>
                                    <option value="youtube">YouTube</option>
                                    <option value="tiktok">TikTok</option>
                                    <option value="twitter">Twitter</option>
                                    <option value="facebook">Facebook</option>
                                    <option value="linkedin">LinkedIn</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            
                            <div id="social-link-container" class="hidden">
                                <label for="social-link" class="block text-sm font-medium text-gray-700 mb-1">Social Link (Optional)</label>
                                <input type="url" id="social-link" name="social-link" class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="https://">
                            </div>
                            
                            <div>
                                <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Why are you a good fit for our program?</label>
                                <textarea id="bio" name="bio" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="Tell us about your experience and why you'd be a great addition..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navigation Buttons -->
                    <div class="flex justify-between mt-8">
                        <button type="button" id="prev-btn" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition hidden">
                            <i class="fas fa-arrow-left mr-2"></i>Previous
                        </button>
                        
                        <button type="button" id="next-btn" class="ml-auto px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-lg font-medium hover:from-indigo-600 hover:to-purple-700 transition shadow-md">
                            Next Step<i class="fas fa-arrow-right ml-2"></i>
                        </button>
                        
                        <button type="submit" id="submit-btn" class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg font-medium hover:from-green-600 hover:to-emerald-700 transition shadow-md hidden">
                            Complete Registration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const steps = document.querySelectorAll('.step');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const submitBtn = document.getElementById('submit-btn');
            const progressBar = document.getElementById('progress');
            const stepIndicators = [
                document.getElementById('step1-indicator'),
                document.getElementById('step2-indicator'),
                document.getElementById('step3-indicator')
            ];
            
            let currentStep = 0;
            
            // Initialize the form
            updateForm();
            
            // Next button event
            nextBtn.addEventListener('click', function() {
                if (validateStep(currentStep)) {
                    currentStep++;
                    updateForm();
                }
            });
            
            // Previous button event
            prevBtn.addEventListener('click', function() {
                currentStep--;
                updateForm();
            });
            
            // Social platform change event
            document.getElementById('social-platform').addEventListener('change', function() {
                const socialLinkContainer = document.getElementById('social-link-container');
                if (this.value) {
                    socialLinkContainer.classList.remove('hidden');
                } else {
                    socialLinkContainer.classList.add('hidden');
                }
            });
            
            const SUBMIT_URL = "{{ route('referral.register') }}";
document.getElementById('registration-form').addEventListener('submit', async function(e) {
    
    e.preventDefault();


    const submitButton = this.querySelector('button[type="submit"]');
    const originalButtonText = submitButton.innerHTML;
    submitButton.innerHTML = 'Submitting...';
    submitButton.disabled = true;

    clearAllErrors(); 

    if (validateStep(currentStep)) {
        
        const formData = new FormData(this);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const response = await fetch(SUBMIT_URL, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) { 
                
                alert('Registration completed successfully! Welcome to our referral program!');
                
                window.location.href = data.redirect_url; 

            } else if (response.status === 422) { 
                
                handleValidationErrors(data.errors);
                alert('Please fix the errors in your form.');

            } else { 
                alert('An error occurred on the server. Please try again later.');
            }

        } catch (error) {
            console.error('Submission failed:', error);
            alert('A network error occurred. Please check your connection.');
        } finally {
            submitButton.innerHTML = originalButtonText;
            submitButton.disabled = false;
        }
    } else {
        submitButton.innerHTML = originalButtonText;
        submitButton.disabled = false;
    }
});


function handleValidationErrors(errors) {
    for (const field in errors) {
        const input = document.querySelector(`[name="${field}"]`);
        const errorElement = input.nextElementSibling; 

        if (errorElement) {
            errorElement.innerText = errors[field][0]; 
            errorElement.style.display = 'block';
        }
    }
}

function clearAllErrors() {
    const errorElements = document.querySelectorAll('.error-message'); // Use a common class
    errorElements.forEach(el => {
        el.innerText = '';
        el.style.display = 'none';
    });
}
            
            // Update form based on current step
            function updateForm() {
                // Hide all steps
                steps.forEach(step => step.classList.remove('active'));
                
                // Show current step
                steps[currentStep].classList.add('active');
                
                // Update progress bar
                progressBar.style.width = `${((currentStep + 1) / steps.length) * 100}%`;
                
                // Update step indicators
                stepIndicators.forEach((indicator, index) => {
                    indicator.classList.remove('active', 'completed');
                    if (index < currentStep) {
                        indicator.classList.add('completed');
                    } else if (index === currentStep) {
                        indicator.classList.add('active');
                    }
                });
                
                // Update buttons
                if (currentStep === 0) {
                    prevBtn.classList.add('hidden');
                    nextBtn.classList.remove('hidden');
                    submitBtn.classList.add('hidden');
                } else if (currentStep === steps.length - 1) {
                    prevBtn.classList.remove('hidden');
                    nextBtn.classList.add('hidden');
                    submitBtn.classList.remove('hidden');
                } else {
                    prevBtn.classList.remove('hidden');
                    nextBtn.classList.remove('hidden');
                    submitBtn.classList.add('hidden');
                }
            }
            
            // Validate current step
            function validateStep(step) {
                let isValid = true;
                
                if (step === 0) {
                    const name = document.getElementById('name').value;
                    const email = document.getElementById('email').value;
                    const password = document.getElementById('password').value;
                    const confirmPassword = document.getElementById('confirm-password').value;
                    
                    if (!name || !email || !password || !confirmPassword) {
                        alert('Please fill in all required fields in Step 1');
                        isValid = false;
                    } else if (password !== confirmPassword) {
                        alert('Passwords do not match');
                        isValid = false;
                    }
                } else if (step === 1) {
                    // Validate step 2 fields
                    const type = document.querySelector('input[name="type"]:checked');
                    const niche = document.getElementById('niche').value;
                    
                    if (!type || !niche) {
                        alert('Please select an account type and niche');
                        isValid = false;
                    }
                }
                
                return isValid;
            }
        });
    </script>
</body>
</html>
