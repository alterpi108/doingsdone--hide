'use strict';

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

function showCompleted() {
  if (readCookie('show')) {
    eraseCookie('show');
  } else {
    createCookie('show', '1', 30);
  }
  location.reload();
}


function urlNoParam()
{
    return location.protocol + '//' + location.host + location.pathname;
}

function replaceQueryParam(param, newval, search) {
    var regex = new RegExp("([?;&])" + param + "[^&;]*[;&]?");
    var query = search.replace(regex, "$1").replace(/&$/, '');

    return (query.length > 2 ? query + "&" : "?") + (newval ? param + "=" + newval : '');
}

/* Все функции ниже похожи как две капли воды */

document.querySelector(".tasks-switch__item:nth-child(1)")
    .addEventListener("click", function (e) {
        e.preventDefault();

        var url = window.location.href;
        if(url.search('filter=') != -1) {
            window.location.replace(urlNoParam() + replaceQueryParam('filter', 'all', window.location.search));
        } else if (url.search('\\?') != -1) {
            window.location.replace(url + '&filter=all');
        } else {
            window.location.replace(url + '?filter=all');
        }
    });

document.querySelector(".tasks-switch__item:nth-child(2)")
    .addEventListener("click", function (e) {
        e.preventDefault();

        var url = window.location.href;
        if(url.search('filter=') != -1) {
            window.location.replace(urlNoParam() + replaceQueryParam('filter', 'today', window.location.search));
        } else if (url.search('\\?') != -1) {
            window.location.replace(url + '&filter=today');
        } else {
            window.location.replace(url + '?filter=today');
        }
    });


document.querySelector(".tasks-switch__item:nth-child(3)")
    .addEventListener("click", function (e) {
        e.preventDefault();

        var url = window.location.href;
        if(url.search('filter=') != -1) {
            window.location.replace(urlNoParam() + replaceQueryParam('filter', 'tomorrow', window.location.search));
        } else if (url.search('\\?') != -1) {
            window.location.replace(url + '&filter=tomorrow');
        } else {
            window.location.replace(url + '?filter=tomorrow');
        }
    });

document.querySelector(".tasks-switch__item:nth-child(4)")
    .addEventListener("click", function (e) {
        e.preventDefault();

        var url = window.location.href;
        if(url.search('filter=') != -1) {
            window.location.replace(urlNoParam() + replaceQueryParam('filter', 'overdue', window.location.search));
        } else if (url.search('\\?') != -1) {
            window.location.replace(url + '&filter=overdue');
        } else {
            window.location.replace(url + '?filter=overdue');
        }
    });


function completeTask($id)
{
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        location.reload();
    };
    xhttp.open("GET", '/complete?id=' + $id, true);
    xhttp.send();
}






/* Код, который был изначально */
// var expandControls = document.querySelectorAll('.expand-control');
//
// var hidePopups = function() {
//   [].forEach.call(document.querySelectorAll('.expand-list'), function(item) {
//     item.classList.add('hidden');
//   });
// };
//
// document.body.addEventListener('click', hidePopups, true);
//
// [].forEach.call(expandControls, function(item) {
//   item.addEventListener('click', function() {
//     item.nextElementSibling.classList.toggle('hidden');
//   });
// });
//
// var $checkbox = document.getElementsByClassName('checkbox__input')[0];
//
// $checkbox.addEventListener('change', function(event) {
//   var is_checked = +event.target.checked;
//
//   window.location = '/guest.view.php?show_completed=' + is_checked;
// });
