

function confirmPassCorrect(){
    var errorMsg = document.getElementById('errorMsg');
    var pass = document.getElementById('password').value;
    var confirm = document.getElementById('confirmPassword').value;

    if(pass === confirm){
        errorMsg.innerHTML = "<b class='text-success'>Password uguale</b>";
    }else{
        errorMsg.innerHTML = "<b class='text-danger'>Password non corrisponde</b>";
    }
}