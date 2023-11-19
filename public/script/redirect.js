const errorMessage = document.querySelector('.error-msg');
const treeLeft = document.querySelector('#tree-left');
const treeRight = document.querySelector('#tree-right');
const gateLeft = document.querySelector('#gate-left');
const gateRight = document.querySelector('#gate-right');

window.addEventListener('scroll', () => {
    let value = window.scrollY;

    errorMessage.style.marginTop = value * 3 + 'px';
    treeLeft.style.left = value * -1.5 + 'px';
    treeRight.style.left = value * 1.5 + 'px';
    gateLeft.style.left = value * 0.2 + 'px';
    gateRight.style.left = value * -0.2 + 'px';

})