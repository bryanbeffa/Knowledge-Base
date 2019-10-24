function deleteCase(caseId, caseName){

    document.getElementById("caseToDeleteId").value = caseId;
    document.getElementById("deleteMessage").innerHTML = "Sei sicuro di voler eliminare il caso <b> (id: "
        + caseId + ") " + caseName + "</b>?";
}