$(function() {
    var topChange;
    ///Check if the OurPick have Height Greater than Content Colunm
    var flat = 0;
    var contentHeight = $('.content-col').height();
    var ourPickHeight = $('.our-picks-frame').height();

    if (contentHeight < ourPickHeight) {
        flat = 1;
    }

    $(window).scroll(function() {
        if (flat == 0) {
            var scholarshipBottom = $('.scholarship-result').height();
            if ($(window).scrollTop() > 550 && $(window).scrollTop() < scholarshipBottom) {
                $('.ourPicks').addClass('affix');
                $('.ourPicks').css("bottom", "100px");
            }
            if ($(window).scrollTop() > scholarshipBottom - 500) {
                topChange = $(window).scrollTop() - (scholarshipBottom - 600);
                $('.ourPicks').css("bottom", topChange);
            }
            if ($(window).scrollTop() < 550) {
                $('.ourPicks').css("bottom", "100px");
                $('.ourPicks').removeClass('affix');
            }
        }
    });
});