function showModifyModal(caseName, caseId, caseDescription, categoryId, variantId) {
    //set data
    document.getElementById("caseTitle").innerHTML = "Modifica caso: " + caseName + " (id: " + caseId + ")";
    document.getElementById("caseDescription").innerHTML = caseDescription;
    document.getElementById("modifyCaseTitle").value = caseName;
    document.getElementById("modifyCaseId").value = caseId;

    var select = document.getElementById("modifyCaseVariantSelect");

    //show all options
    var options = select.options;
    for (var i = 0; i < options.length; i++){
        options[i].hidden = false;
    }

    //set hidden variant
    var hiddenVariant = "variant" + caseId;

    //hide current case as variant
    document.getElementById(hiddenVariant).hidden = true;

    //set selected variant
    if (parseInt(variantId) >= 0) {

        //set selected variant
        var variantName = "variant" + variantId;

        document.getElementById(variantName).selected = true;
    } else {

        //default variant -> "all cases"
        select.selectedIndex = 0;
    }

    //set selected category
    if (parseInt(categoryId) >= 0) {

        //set selected variant
        var categoryName = "category" + categoryId;
        document.getElementById(categoryName).selected = true;
    } else {

        //default variant -> "all cases"
        document.getElementById("modifyCaseCategorySelect").selectedIndex = 0;
    }

    $("#modifyCase").modal("toggle");

}
