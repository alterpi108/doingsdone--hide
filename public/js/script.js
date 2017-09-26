'use strict';

//////////////////////////////////
// Dealing with cookies
//////////////////////////////////

// Create cookie
function createCookie(name, value, days) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        expires = "; expires="+date.toGMTString();
    }
    else {
        expires = "";
    }
    document.cookie = name+"="+value+expires+"; path=/";
}

// Read cookie
function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1,c.length);
        }
        if (c.indexOf(nameEQ) === 0) {
            return c.substring(nameEQ.length,c.length);
        }
    }
    return null;
}

// Erase cookie
function eraseCookie(name) {
    createCookie(name,"",-1);
}


//////////////////////////////////
// Page behaviour
//////////////////////////////////

function urlNoParam()
{
    return location.protocol + '//' + location.host + location.pathname;
}

function showCompleted() {
  if (readCookie('show')) {
    eraseCookie('show');
  } else {
    createCookie('show', '1', 30);
  }
  location.reload();
}

function replaceQueryParam(param, newval, search) {
    var regex = new RegExp("([?;&])" + param + "[^&;]*[;&]?");
    var query = search.replace(regex, "$1").replace(/&$/, '');

    return (query.length > 2 ? query + "&" : "?") + (newval ? param + "=" + newval : '');
}

function handlerFactory($query) {
    return function (e) {
        e.preventDefault();

        var url = window.location.href;
        if (url.search('filter=') != -1) {
            window.location.replace(urlNoParam() + replaceQueryParam('filter', $query, window.location.search));
        } else if (url.search('\\?') != -1) {
            window.location.replace(url + '&filter=all');
        } else {
            window.location.replace(url + '?filter=all');
        }
    }
}

document.querySelector(".tasks-switch__item:nth-child(1)")
    .addEventListener(handlerFactory('all'));

document.querySelector(".tasks-switch__item:nth-child(2)")
    .addEventListener(handlerFactory('today'));

document.querySelector(".tasks-switch__item:nth-child(3)")
    .addEventListener(handlerFactory('tomorrow'));

document.querySelector(".tasks-switch__item:nth-child(4)")
    .addEventListener(handlerFactory('due'));

function completeTask($id)
{
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        location.reload();
    };
    xhttp.open("GET", '/complete?id=' + $id, true);
    xhttp.send();
}
