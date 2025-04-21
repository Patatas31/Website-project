const form = document.getElementById('form');
const Name_input = document.getElementById('Name');
const Email_input = document.getElementById('Email');
const Password_input = document.getElementById('Password');
const phone_num = document.getElementById('Phone');
const repeatPassword_input = document.getElementById('RepeatPassword');
const error_message = document.getElementById('error-message');

form.addEventListener('submit', (e)=>{
    //e.preventDefault() Prevent submission
    let errors = [];

    if(Name_input){
        errors = getSignupFormErrors(Name_input.value, Email_input.value, Password_input.value, phone_num.value,
            repeatPassword_input.value);
    }
    else{
        errors = getLoginFormErrors(Email_input.value, Password_input.value);
    }
    if(errors.length > 0){
        e.preventDefault();
        error_message.innerText = errors.join(". ");
    }
})

function getSignupFormErrors(Name, Email, Password, Phone, RepeatPassword){
    let errors = [];

    if(Name === '' || Name == null){
        errors.push('Name is required');
        Name_input.parentElement.classList.add('incorrect');
    }
    if(Email === '' || Email == null){
        errors.push('Email is required');
        Email_input.parentElement.classList.add('incorrect');
    }

    if(Phone === '' || Phone == null){
        errors.push('phone is required');
        phone_num.parentElement.classList.add('incorrect');
    }else if (!/^\d{11}$/.test(Phone)) { // Check if it's exactly 11 digits
        errors.push('Phone must be exactly 11 digits');
        phone_num.parentElement.classList.add('incorrect');
    }

    if(Password === '' || Password == null){ 
        errors.push('Password is required');
        Password_input.parentElement.classList.add('incorrect');
    }
    if(Password !== RepeatPassword){
        errors.push('Password does not match repeated password');
        Password_input.parentElement.classList.add('incorrect');
        repeatPassword_input.parentElement.classList.add('incorrect');
    }
    return errors;
}

function getLoginFormErrors(Email, Password){
    let errors = [];

    if(Email === '' || Email == null){
        errors.push('Email is required');
        Email_input.parentElement.classList.add('incorrect');
    }
    if(Password === '' || Password == null){
        errors.push('Password is required');
        Password_input.parentElement.classList.add('incorrect');
    }

    return errors;
}

const allInputs = [Name_input, Password_input, Email_input, phone_num, repeatPassword_input].filter(input => input != null);
allInputs.forEach(input => {
    input.addEventListener('input', ()=>{
        if(input.parentElement.classList.contains('incorrect')){
            input.parentElement.classList.remove('incorrect');
            error_message.innerText = '';
        }
    })
})
