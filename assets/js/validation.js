// Path: assets/js/validation.js

// Wait for the DOM content to load
document.addEventListener("DOMContentLoaded", () => {
    // Sign In Form Validation
    const signInForm = document.querySelector("#signinForm");
    const signInButton = document.querySelector("#signInButton");

    if (signInForm && signInButton) {
        signInButton.addEventListener("click", (event) => {
            event.preventDefault();
            
            const identifier = document.querySelector("input[name='identifier']")?.value;
            const password = document.querySelector("input[name='password']")?.value;

            if (validateSignInForm(identifier, password)) {
                signInForm.submit();
            } else {
                alert("Invalid email/username or password. Please try again.");
            }
        });
    }

    // Sign Up Form Validation
    const signUpForm = document.querySelector("#signupForm");
    if (signUpForm) {
        signUpForm.addEventListener("submit", async (event) => {
            event.preventDefault();
            
            // Get form values
            const firstName = document.querySelector("#firstName")?.value;
            const lastName = document.querySelector("#lastName")?.value;
            const username = document.querySelector("#username")?.value;
            const email = document.querySelector("#signupEmail")?.value;
            const password = document.querySelector("#signupPassword")?.value;
            const confirmPassword = document.querySelector("#confirmPassword")?.value;
            const skillLevel = document.querySelector("#skill_level")?.value;

            // Validate form
            if (validateSignUpForm(firstName, lastName, username, email, password, confirmPassword, skillLevel)) {
                try {
                    const formData = new FormData(signUpForm);
                    const response = await fetch(window.location.href, {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();
                    
                    if (data.status === 'success') {
                        window.location.href = data.redirect;
                    } else {
                        alert(data.message || 'Registration failed. Please try again.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Registration failed. Please try again.');
                }
            }
        });
    }
});

function validateSignInForm(identifier, password) {
    if (!identifier || !password) {
        return false;
    }
    return true;
}

function validateSignUpForm(firstName, lastName, username, email, password, confirmPassword, skillLevel) {
    // Clear previous error messages
    clearErrors();
    let isValid = true;

    // First Name validation
    if (!firstName || firstName.length < 2) {
        showError('firstNameError', 'First name is required (minimum 2 characters)');
        isValid = false;
    }

    // Last Name validation
    if (!lastName || lastName.length < 2) {
        showError('lastNameError', 'Last name is required (minimum 2 characters)');
        isValid = false;
    }

    // Username validation
    if (!username || username.length < 3) {
        showError('usernameError', 'Username is required (minimum 3 characters)');
        isValid = false;
    }

    // Email validation
    if (!email || !isValidEmail(email)) {
        showError('signupEmailError', 'Please enter a valid email address');
        isValid = false;
    }

    // Password validation
    if (!password || password.length < 6) {
        showError('signupPasswordError', 'Password must be at least 6 characters long');
        isValid = false;
    }

    // Confirm Password validation
    if (password !== confirmPassword) {
        showError('confirmPasswordError', 'Passwords do not match');
        isValid = false;
    }

    // Skill Level validation
    if (!skillLevel) {
        showError('skill_level', 'Please select your gardening experience level');
        isValid = false;
    }

    return isValid;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function showError(elementId, message) {
    const errorElement = document.getElementById(elementId);
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.color = 'red';
        errorElement.style.fontSize = '12px';
        errorElement.style.marginTop = '5px';
    }
}

function clearErrors() {
    const errorElements = document.querySelectorAll('.error');
    errorElements.forEach(element => {
        element.textContent = '';
    });
}
