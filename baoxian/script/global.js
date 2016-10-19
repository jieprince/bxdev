
function checkAllBox(form) 
{
    var checkOrNot = false;

    var element = form.elements.checkAll;
    if (element.type.toUpperCase() == 'CHECKBOX') {
        checkOrNot = element.checked;
    }

    for (var i = 0; i < form.elements.length; i++) {
        element = form.elements[i];
        if ((element.type.toUpperCase() == 'CHECKBOX')) {
            element.checked = checkOrNot;
        }
    }
}
