function showModifyModal(caseName, caseId, caseDescription, categoryId, variantId) {

    //set data
    document.getElementById("caseTitle").innerHTML = "Modifica caso: " + caseName + " (id: " + caseId + ")";
    document.getElementById("caseDescription").innerHTML = caseDescription;
    document.getElementById("modifyCaseTitle").value = caseName;

    //set selected variant
    if (parseInt(variantId) >= 0) {

        //set selected variant
        var variantName = "variant" + variantId;
        document.getElementById(variantName).selected = true;
    } else {

        //default variant -> "all cases"
        document.getElementById("modifyCaseVariantSelect").selectedIndex = 0;
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
