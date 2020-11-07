let $ = window.$;

$(document).ready(function() {
    $('[data-copy]').bind('click', function() {
        let el = document.getElementById($(this).data('copy'));
        let range = document.createRange();

        range.selectNode(el);
        window.getSelection().removeAllRanges(); // clear current selection
        window.getSelection().addRange(range); // to select text
        document.execCommand("copy");
        window.getSelection().removeAllRanges();// to deselect
    });
});
