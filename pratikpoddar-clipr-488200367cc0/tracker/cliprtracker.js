/**
 * This script has two jobs.
 *
 *  1. If the user lands on the current page after being redirected by clipr,
 *     the URL will have one string like "cliprRefer=ABCD1234". This script will
 *     extract this value and set in the cookie valid for next 30 days.
 *
 *  2. If the cookie already has been set for this user, this script sends back
 *     the current URL to a tracker clipr.in.
 */

// Set the user's cookie and enable activity tracking
function cliprTracker(transactionId, price, state ) {
    if(typeof(state)==='undefined') state = "";
    if(typeof(price)==='undefined') price = 0;

    //
    // Only if the cookie has been set by clipr before, send a dummy request to
    // track the page on which user is now.
    //
    var ourCookie = allCookies()["cliprRefer"];
    if (ourCookie != undefined) {
        var i = document.createElement("img");
        i.src = "http://clipr.in/tracker/update" +
                "?refURL=" + window.location.href +
                "&cliprRefer=" + ourCookie +
                "&transactionId=" + transactionId +
                "&state="+ state +
                "&price="+ price;
    }
}

function setCliprCookie(){
    var cliprID = getUrlVars()["cliprRefer"];

    // This is our redirect to the affiliate web page, so set cookie now
    if (cliprID != undefined) {
        setCookie("cliprRefer", cliprID, 30);
    }
}

// Extract values from the cookie
function allCookies() {
    var cr, ck, cv;
    cr = [];
    if (document.cookie != '') {
        ck = document.cookie.split('; ');
        for (var i = ck.length - 1; i >= 0; i--) {
            cv = ck[i].split('=');
            cr[cv[0]] = cv[1];
        }
    }
    return cr;
};

// Extract the variable=value pairs from the URL
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,
        function(m,key,value) {
            vars[key] = value;
        });
    return vars;
}

// Set the cookie with cname=cvalue that expires in these many days
function setCookie(cname, cvalue, days) {
    var dt, expires;
    if (days) {
        dt = new Date();
        dt.setTime(dt.getTime() + (days*24*60*60*1000));
        expires = "; expires = " + dt.toGMTString();
    }
    else {
        expires = '';
    }

    document.cookie = cname + "=" + cvalue + "; path=/" + expires;
}
