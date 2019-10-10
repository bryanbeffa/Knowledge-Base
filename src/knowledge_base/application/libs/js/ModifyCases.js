function showModifyModal(caseId, caseDescription) {
    document.getElementById("caseTitle").innerHTML = caseId;
    document.getElementById("caseDescription").innerHTML = caseDescription;

    $("#modifyCase").modal("toggle");

}
