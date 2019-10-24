function deleteUser(userId, userName){

    document.getElementById("userToDeleteId").value = userId;
    document.getElementById("deleteMessage").innerHTML = "Sei sicuro di voler eliminare l'utente <b>" + userName + "</b>?";

    console.log("I dati arrivano")

}