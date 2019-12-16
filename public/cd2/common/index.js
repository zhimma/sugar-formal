window.onload = () => {
    const data = {};
    const $ = window.document.querySelector.bind(window.document);
};
document.addEventListener('DOMContentLoaded', function() {
    function handleCalc() {
        var dw = document.body.clientWidth;
        var minScale = Math.min(dw / 750, 1);
        document.documentElement.style.fontSize = (minScale * 75) + 'px';
    }
    handleCalc();
    window.addEventListener('resize', handleCalc);
});