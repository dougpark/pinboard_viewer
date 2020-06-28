// global user

let user = {};
let history = [];
let cache = {};
let config = {};

let pref = {};
pref.priTag = 5;
pref.lowTag = 1;
pref.tagMax = 100;

// this runs when page is finished loading
$(document).ready(function () {

    // get q url param for items to query from pinboard
    // ?q=u:username/
    // ?q=t:programming/
    // ?q=t:javascript/
    let url = new URL(window.location.href);
    let searchParams = new URLSearchParams(url.search);

    // load default params from config.json
    $.ajax({
        url: "config.json",
        method: "GET",
        success: function (response) {

            // get from config file
            user.userid = response.userid;
            user.token = response.token;

            // url params overide config.json
            if (searchParams.get('userid')) {
                user.userid = searchParams.get('userid');
            };
            if (searchParams.get('token')) {
                user.token = searchParams.get('token');
            };

            config = response;

            getByTag('start');
            //getRecent();
            //getByDate(userid, token, '2020-06-07');
        }
    });

    $(document).on('click', '.btnPublic', function (event) {
        event.stopPropagation();
        event.stopImmediatePropagation();
        swapPublic(event.target.dataset.url);
        console.log('public pressed, url= ' + event.target.dataset.url);
        //(... rest of your JS code)
    });

    $(document).on('click', '.btnUnRead', function (event) {
        event.stopPropagation();
        event.stopImmediatePropagation();
        swapUnRead(event.target.dataset.url);
        console.log('unRead pressed, url= ' + event.target.dataset.url);
        //(... rest of your JS code)
    });


    // left = 37
    // up = 38
    // right = 39
    // down = 40
    $(document).keydown(function (e) {
        let up = [0, 37, 38, 65, 87];
        let down = [0, 32, 39, 40, 68, 83];
        let r = [0, 82];
        //console.log(e.keyCode);
        if (up.indexOf(e.keyCode) > 0) {
            scrollDir(-1);
        } else if (down.indexOf(e.keyCode) > 0) {
            scrollDir();
        } else if (r.indexOf(e.keyCode) > 0) {
            scrollToId(true);
        }

    });

})

// when user changes any bookmark then clearCache and reload current view
function reloadCurrent() {

    if (history.length > 0) {
        let prev = history.pop(); // current location
        clearCache();
        doHistory(prev);

    }
}

// when user clicks back button then pop current location and go back one view
function popHistory() {

    if (history.length > 1) {

        let prev = history.pop(); // current location
        prev = history.pop(); // prev location

        doHistory(prev);
    }

}

// take action on the selected view from history
function doHistory(prev) {
    if (prev.loc) {
        switch (prev.loc) {
            case 'getRecent':
                getRecent();
                break;
            case 'getTagCloud':
                getTagCloud();
                break;
            case 'getByDate':
                getByDate(prev.date);
                break;
            case 'getByTag':
                getByTag(prev.tag);
                break;
            case 'getByHost':
                getByHost(prev.host);
                break;
            default:
                getRecent();
        }
    } else {
        getRecent();
    }
}

// any changes to a bookmark must clear the entire cache
function clearCache() {
    Object.keys(cache).forEach(function (key) {
        delete cache[key];
    });

}

// if key in cache then use it otherwise return false to reload from server
function inCache(data) {
    // if key exists use it
    if (cache[data.key]) {
        document.getElementById("output").innerHTML = cache[data.key];
        //console.log('used the cache');
        return true;
    } else {
        return false;
    }
}

function addCache(data) {
    cache[data.key] = data.data;
    //console.log(cache);

    // when to clear the cace
    // check if cache has date
    // if no date then get date from pinboard and return
    // 
    // if yes date then get date from pinboard and see if newer
    // if newer then clear cache
}

function pushHistory(data) {
    history.push(data);
}

// user clicked public button so invert public setting
function swapPublic(url) {

    $.ajax({
        url: "api.php",
        method: "POST",
        data: {
            userid: user.userid,
            token: user.token,
            max: pref.tagMax,
            url: url,
            action: 'swapPublic',
            pref: pref,
        },
        success: function (response) {

            // get current view from cache and show again with updates from server
            reloadCurrent();

        }
    });

}

// user clicked unread button so invert unread setting
function swapUnRead(url) {

    $.ajax({
        url: "api.php",
        method: "POST",
        data: {
            userid: user.userid,
            token: user.token,
            max: pref.tagMax,
            url: url,
            action: 'swapUnRead',
            pref: pref,
        },
        success: function (response) {

            // get current view from cache and show again with updates from server
            reloadCurrent();

        }
    });

}

// call the php code to run the pinboard query
function getRecent() {
    pushHistory({
        loc: 'getRecent'
    });

    if (inCache({
            key: 'getRecent'
        })) {
        return;
    }

    $.ajax({
        url: "api.php",
        method: "POST",
        data: {
            userid: user.userid,
            token: user.token,
            action: 'getRecent',
            pref: pref,
        },
        success: function (response) {

            addCache({
                key: 'getRecent',
                data: response
            })
            document.getElementById("output").innerHTML = response;

        }
    });

}


function getTagCloud(count = 100) {
    pushHistory({
        loc: 'getTagCloud'
    });

    if (inCache({
            key: 'getTagCloud'
        })) {
        return;
    }

    $.ajax({
        url: "api.php",
        method: "POST",
        data: {
            userid: user.userid,
            token: user.token,
            max: pref.tagMax,
            action: 'getTagCloud',
            pref: pref,
        },
        success: function (response) {

            addCache({
                key: 'getTagCloud',
                data: response
            })

            document.getElementById("output").innerHTML = response;

        }
    });

}



function getByDate(date) {
    pushHistory({
        loc: 'getByDate',
        date: date
    });

    if (inCache({
            key: date
        })) {
        return;
    }

    $.ajax({
        url: "api.php",
        method: "POST",
        data: {
            userid: user.userid,
            token: user.token,
            date: date,
            action: 'getByDate',
            pref: pref,
        },
        success: function (response) {

            addCache({
                key: date,
                data: response
            })

            document.getElementById("output").innerHTML = response;

        }
    });

}

// pinboard api seems to be case SENSITIVE for tag queries
function getByTag(tag) {
    pushHistory({
        loc: 'getByTag',
        tag: tag
    });

    if (inCache({
            key: tag
        })) {
        return;
    }

    $.ajax({
        url: "api.php",
        method: "POST",
        data: {
            userid: user.userid,
            token: user.token,
            tag: tag,
            action: 'getByTag',
            pref: pref,
        },
        success: function (response) {

            addCache({
                key: tag,
                data: response
            })

            document.getElementById("output").innerHTML = response;

        }
    });

}

function getByHost(host) {
    pushHistory({
        loc: 'getByHost',
        host: host
    });

    $.ajax({
        url: "api.php",
        method: "POST",
        data: {
            userid: user.userid,
            token: user.token,
            host: host,
            action: 'getByHost',
            pref: pref,
        },
        success: function (response) {

            // scan results to match host

            document.getElementById("output").innerHTML = response;

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
    opt = "_blank"; // see if we like this
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