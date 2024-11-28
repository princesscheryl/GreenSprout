//retrieve all the elements needed
const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');

//get form elements for Sign Up
const signupForm = document.getElementById('signupForm');
const firstName = document.getElementById('firstName');
const lastName = document.getElementById('lastName');
const username = document.getElementById('username');
const signupEmail = document.getElementById('signupEmail');
const signupPassword = document.getElementById('signupPassword');
const confirmPassword = document.getElementById('confirmPassword');

//get form elements for sign in
const signinForm = document.getElementById('signinForm');
const signinEmail = document.getElementById('signinEmail');
const signinPassword = document.getElementById('signinPassword');

//handle the sliding animation between panels
signUpButton.addEventListener('click', () => {
    container.classList.add('right-panel-active');
});

signInButton.addEventListener('click', () => {
    container.classList.remove('right-panel-active');
});

//check if an email is valid
function checkEmail(email) {
    
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailPattern.test(email);
}

// check if a password meets all requirements
function checkPassword(password) {
    //check password requirements one by one
    const isLongEnough = password.length >= 8;
    const hasUpperCase = /[A-Z]/.test(password);
    const hasEnoughNumbers = (password.match(/\d/g) || []).length >= 3;
    const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);

    //return an object with the results
    return {
        isValid: isLongEnough && hasUpperCase && hasEnoughNumbers && hasSpecialChar,
        errors: {
            length: !isLongEnough,
            uppercase: !hasUpperCase,
            numbers: !hasEnoughNumbers,
            special: !hasSpecialChar
        }
    };
}

// show an error message under an input
function showError(inputElement, message) {
    const errorDisplay = inputElement.nextElementSibling;
    errorDisplay.textContent = message;
    inputElement.classList.add('error');
}

//to clear an error message
function clearError(inputElement) {
    const errorDisplay = inputElement.nextElementSibling;
    errorDisplay.textContent = '';
    inputElement.classList.remove('error');
}

//to create password error message
function getPasswordErrorMessage(validationResult) {
    let errors = [];
    if (validationResult.errors.length) {
        errors.push("at least 8 characters");
    }
    if (validationResult.errors.uppercase) {
        errors.push("one uppercase letter");
    }
    if (validationResult.errors.numbers) {
        errors.push("three numbers");
    }
    if (validationResult.errors.special) {
        errors.push("one special character");
    }
    return "Password needs: " + errors.join(", ");
}

//handle sign up form submission
signupForm.addEventListener('submit', function(event) {
    // Prevent the form from submitting normally
    event.preventDefault();
    
    let formIsValid = true;

    //check if all required fields are filled
    if (firstName.value.trim() === '') {
        showError(firstName, 'First name is required');
        formIsValid = false;
    } else {
        clearError(firstName);
    }

    if (lastName.value.trim() === '') {
        showError(lastName, 'Last name is required');
        formIsValid = false;
    } else {
        clearError(lastName);
    }

    if (username.value.trim() === '') {
        showError(username, 'Username is required');
        formIsValid = false;
    } else {
        clearError(username);
    }

    //check email
    if (signupEmail.value.trim() === '') {
        showError(signupEmail, 'Email is required');
        formIsValid = false;
    } else if (!checkEmail(signupEmail.value.trim())) {
        showError(signupEmail, 'Please enter a valid email');
        formIsValid = false;
    } else {
        clearError(signupEmail);
    }

    //ceck password
    if (signupPassword.value === '') {
        showError(signupPassword, 'Password is required');
        formIsValid = false;
    } else {
        const passwordCheck = checkPassword(signupPassword.value);
        if (!passwordCheck.isValid) {
            showError(signupPassword, getPasswordErrorMessage(passwordCheck));
            formIsValid = false;
        } else {
            clearError(signupPassword);
        }
    }

    //check if passwords typed match
    if (confirmPassword.value === '') {
        showError(confirmPassword, 'Please confirm your password');
        formIsValid = false;
    } else if (confirmPassword.value !== signupPassword.value) {
        showError(confirmPassword, 'Passwords do not match');
        formIsValid = false;
    } else {
        clearError(confirmPassword);
    }

    //submit if all the details are valid
    if (formIsValid) {
        alert('Welcome New Sprout!');
        signupForm.reset(); 
    }
});

//handle sign in form submission
signinForm.addEventListener('submit', function(event) {
    // prevent the form from submitting normally
    event.preventDefault();
    
    let formIsValid = true;

    //check email
    if (signinEmail.value.trim() === '') {
        showError(signinEmail, 'Email is required');
        formIsValid = false;
    } else if (!checkEmail(signinEmail.value.trim())) {
        showError(signinEmail, 'Please enter a valid email');
        formIsValid = false;
    } else {
        clearError(signinEmail);
    }

    //check password
    if (signinPassword.value === '') {
        showError(signinPassword, 'Password is required');
        formIsValid = false;
    } else {
        const passwordCheck = checkPassword(signinPassword.value);
        if (!passwordCheck.isValid) {
            showError(signinPassword, 'Invalid password format');
            formIsValid = false;
        } else {
            clearError(signinPassword);
        }
    }

    //submit if details are valid
    if (formIsValid) {
        alert('Sign In successful!');
        signinForm.reset(); 
    }
});