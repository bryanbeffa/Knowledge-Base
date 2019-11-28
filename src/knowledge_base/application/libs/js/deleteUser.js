function deleteUser(userId, userName) {
    document.getElementById("userToDeleteId").value = userId;
    document.getElementById("deleteMessage").innerHTML = "Sei sicuro di voler eliminare l'utente <b>" + userName + "</b>?";
}

function modifyUser(userId, userName, userSurname, userEmail) {
    document.getElementById("modifyUserName").value = userName;
    document.getElementById("modifyUserId").value = userId;
    document.getElementById("modifyUserSurname").value = userSurname;
    document.getElementById("modifyUserEmail").value = userEmail;
}

//submit form after confirm popup
$('#confirmModifyUserSubmit').click(function () {
    /* when the submit button in the modal is clicked, submit the form */
    $('#modifyUserForm').submit();
});
