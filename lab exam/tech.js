const Fullnametext = document.getElementById('fullname');
const EmailText=document.getElementById('email');
const ComopanyText = document.getElementById('company');
const AttendenceText = document.getElementById('attendance');

function validateFulname(){
    const name = Fullnametext.value.trim();
    const errorElement = document.getElementById('name-error');
    if(name.length <6 || name.length<=100 ){
        errorElement.textContent = 'Full name must be between 6 and 100 characters.';
        return false;
    }
        else 
        errorElement.textContent = '';
        
}
function validateEmail(){
    const email = EmailText.value.trim();
    const errorElement = document.getElementById('email-error');
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
     if (!emailRegex.test(email)) {
        errorElement.textContent = 'Please enter a valid email address.';
        return false;
    } else
        errorElement.textContent = '';
}

function validateAttendance(){
    const attendance = AttendenceText.value;
    const errorElement = document.getElementById('attendance-error');
    if(attendance === ''){
        errorElement.textContent = 'Please select your attendance status.';
        return false;
    } else
        errorElement.textContent = ''; 
}