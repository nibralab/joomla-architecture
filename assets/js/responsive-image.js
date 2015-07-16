$(document).ready(function () {
    var cWidth = window.innerWidth;
    var width = 1920;
    var sizes = [480, 768, 1024, 1280, 1920];
    for (var i = 0; i < sizes.length; ++i) {
        if (cWidth <= sizes[i]) {
            width = sizes[i];
            break;
        }
    }

    var $img = $('img#feature-image');
    $img.attr({
        'src': "/images/" + width + "/" + $img.data('ref')
    });
});
