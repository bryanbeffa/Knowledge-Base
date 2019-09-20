
function confirmPassCorrect(){
    var pass = document.getElementById('password').value;
    var confirm = document.getElementById('confirmPassword').value;

    if(pass === confirm){
        console.log("Password uguale");
    }else{
        console.log("Password diversa");
    }
}