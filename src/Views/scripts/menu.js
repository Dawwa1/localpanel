function togglePopup(popupElement, popupDisplayType){
    switch (popupElement.style.display) {
    case 'none':
        popupElement.style.display = popupDisplayType;
        break;
    case popupDisplayType:
        popupElement.style.display = 'none';
        break;
    default:
        popupElement.style.display = popupDisplayType;
        break;
    }
}