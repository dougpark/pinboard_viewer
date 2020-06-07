// this runs when page is finished loading
$(document).ready(function () {

    // get q url param for items to query from pinboard
    // ?q=u:username/
    // ?q=t:programming/
    // ?q=t:javascript/
    let url = new URL(window.location.href);
    let searchParams = new URLSearchParams(url.search);

    // load default q search term from config.json
    $.ajax({
        url: "config.json",
        method: "GET",
        success: function (response) {

            let q = response.q;
            // url params overide config.json
            if (searchParams.get('q')) {
                q = searchParams.get('q');
            };

            showRSS(q);
        }
    });


    // left = 37
    // up = 38
    // right = 39
    // down = 40
    $(document).keydown(function (e) {
        let up = [0, 37, 38, 65, 87];
        let down = [0, 32, 39, 40, 68, 83];
        let r = [0, 82];
        console.log(e.keyCode);
        if (up.indexOf(e.keyCode) > 0) {
            scrollDir(-1);
        } else if (down.indexOf(e.keyCode) > 0) {
            scrollDir();
        } else if (r.indexOf(e.keyCode) > 0) {
            scrollToId(true);
        }

    });

})

// call the php code to run the pinboard query
function showRSS(str) {

    $.ajax({
        url: "getrss.php?q=" + str,
        method: "GET",
        success: function (response) {

            document.getElementById("rssOutput").innerHTML = response;

        }
    });

}

// called when a bookmark link is clicked
// hack to determine if running in bookmarklet mode
function openWin(url) {
    let opt = "_self";
    if (window.innerWidth < 550) {
        opt = "_blank";
    }
    setTimeout(window.close, 10);
    open(url, opt);
}

function randomIntFromInterval(min, max) { // min and max included 
    return Math.floor(Math.random() * (max - min + 1) + min);
}

function inViewport(element) {
    if (typeof jQuery === "function" && element instanceof jQuery) {
        element = element[0];
    }
    var elementBounds = element.getBoundingClientRect();
    return (
        elementBounds.top >= 0 &&
        elementBounds.left >= 0 &&
        elementBounds.bottom <= $(window).height() &&
        elementBounds.right <= $(window).width()
    );
}

function idInCurrentView() {
    for (id = 1; id <= 49; id++) {
        if (inViewport($('#koan-' + id))) {
            break;
        }
    }
    return id;
}

function scrollDir(direction = 1) {

    let currId = idInCurrentView();

    if (direction > 0) {
        if (currId < 49) {
            scrollToId(currId + 1);
        }
    } else {
        if (currId > 1) {
            scrollToId(currId - 1);
        }
    }

}

function scrollToId(id) {
    if (!Number.isInteger(id)) {
        id = randomIntFromInterval(1, 49);
    }

    $('#koan-' + id).scrollTo();

}

$.fn.scrollTo = function (speed) {
    if (typeof (speed) === 'undefined')
        speed = 500;

    $('html, body').animate({
        scrollTop: parseInt($(this).offset().top - 100)
    }, speed);
};