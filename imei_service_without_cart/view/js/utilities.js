/**
 * Created with JetBrains PhpStorm.
 * User: zhalnin
 * Date: 5/9/13
 * Time: 11:49 AM
 * To change this template use File | Settings | File Templates.
 */

/**
 * Created by JetBrains PhpStorm.
 * User: zhalnin
 * Date: 15.10.12
 * Time: 17:02
 * To change this template use File | Settings | File Templates.
 */
var U = {
    $: function(id){
        'use strict';
        if((typeof id == 'string')){
            return document.getElementById(id);
        }
    },

    setText: function(id, message){
        'use strict';
        if((typeof id == 'string')
            && (typeof message == 'string')){
            var output = this.$(id);
            if(!output) return false;
            if(output.textContent !== undefined){
                output.textContent = message;
            } else {
                output.innerText = message;
            }
            return true;
        }
    },

    addEvent: function(obj, type, fn){
        'use strict';
        if(obj && obj.addEventListener){
            obj.addEventListener(type, fn, false);
        } else if (obj && obj.attachEvent){
            obj.attachEvent('on' + type, fn);
        }
    },

    removeEvent: function(obj, type, fn){
        'use strict';
        if(obj && obj.removeEventListener){
            obj.removeEventListener(type, fn, false);
        } else if (obj && obj.detachEvent){
            obj.detachEvent('on' + type, fn);
        }
    },

    enableTooltips: function(id){
        'use strict';
        var elem = this.$(id);
        this.addEvent(elem, 'focus', this.showTooltip);
        this.addEvent(elem, 'mouseover', this.showTooltip);
        this.addEvent(elem, 'blur', this.hideTooltip);
        this.addEvent(elem, 'mouseout', this.hideTooltip);
    },

    showTooltip: function(e){
        'use strict';
        if(typeof e == 'undefined') var e = window.event;
        var target = e.target || e.srcElement;
        target.previousSibling.previousSibling.lastChild.style.visibility = 'visible';
    },

    hideTooltip: function(e){
        'use strict';
        if(typeof e == 'undefined') var e = window.event;
        var target = e.target || e.srcElement;
        target.previousSibling.previousSibling.lastChild.style.visibility = 'hidden';
    }

};

var COOKIE = {
    setCookie: function(name,value,expires,path,domain,secure){
        'use strict';
        var str = encodeURIComponent(name) + '=' + encodeURIComponent(value);

        if(expires instanceof Date){
            str += ';expires=' + expires.toGMTString();
        }
        if(path){
            str += ";path=" + path;
        }
        if(domain){
            str += ";domain=" + domain;
        }
        if(secure){
            str += ";secure";
        }

        document.cookie = str;
    },
    getCookie: function(name){
        'use strict';
        var len = name.length;
        var cookies = document.cookie.split(';');
        for(var i = 0, count = cookies.length; i < count; i++){
            var value = (cookies[i].slice(0,1)== ' ') ?
                cookies[i].slice(1) : cookies[i];
//      if(cookies[i].slice(0,1) == ' '){
//        var value = cookies[i].slice(1);
//      } else {
//        var value = cookies[i];
//      }
            value = decodeURIComponent(value);
            if(value.slice(0, len) == name){
                return cookies[i].split('=')[1];
            }
            var v = cookies[i].split('=');
            return v[1];
        }
        return false;
    },
    deleteCookie: function(name){
        'use strict';
        document.cookie = encodeURIComponent(name) +
            '=;expires=Thu, 01-Jan-1970 00:00:01 GMT';
    }
};


var SUBCOOKIE = {
    getCookie: function(name, subName){
        var subCookies = this.getAllCookie(name);
        if(subCookies){
            return subCookies[subName];
        } else {
            return null;
        }
    },

    getAllCookie: function(name){
        var cookieName = encodeURIComponent(name) + "=",
            cookieStart = document.cookie.indexOf(cookieName),
            cookieValue = null,
            cookieEnd,
            i,
            parts,
            result = {};
        if(cookieStart > -1){
            cookieEnd = document.cookie.indexOf(";",cookieStart);
            if(cookieEnd == -1){
                cookieEnd = document.cookie.length;
            }
            cookieValue = document.cookie.substring(cookieStart +
                cookieName.length, cookieEnd);
            if(cookieValue.length > 0){
                subCookies = cookieValue.split("&");
                for(i=0,len=subCookies.length; i<len; i++){
                    parts = subCookies[i].split("=");
                    result[decodeURIComponent(parts[0])] =
                        decodeURIComponent(parts[1]);
                }
                return result;
            }
        }
        return null;
    },

    setCookie: function(name,subName,value,expires,path,domain,secure){
        var subcookies = this.getAllCookie(name) || {};
        subcookies[subName] = value;
        this.setAllCookie(name,subcookies,expires,path,domain,secure);
    },

    setAllCookie: function(name,subcookies,expires,path,domain,secure){
        var cookieText = encodeURIComponent(name) + "=",
            subcookieParts = new Array(),
            subName;
        for(subName in subcookies){
            if(subName.length > 0 && subcookies.hasOwnProperty(subName)){
                subcookieParts.push(encodeURIComponent(subName) + "=" +
                    encodeURIComponent(subcookies[subName]));
            }
        }
        if(subcookieParts.length > 0){
            cookieText += subcookieParts.join("&");
            if(expires instanceof Date){
                cookieText += ";expires=" + expires.toGMTString();
            }
            if(path){
                cookieText += ";path=" + path;
            }
            if(domain){
                cookieText += ";domain=" + domain;
            }
            if(secure){
                cookieText += ";secure";
            }
        } else {
            cookieText += "; expires=" + (new Date(0)).toGMTString();
        }
        document.cookie = cookieText;
    },

    unsetCookie: function(name,subName,path,domain,secure){
        var subcookies = this.getAllCookie(name);
        if(subcookies){
            delete subcookies[subName];
            this.setAll(name,subcookies,path,domain,secure);
        }
    },

    unsetAllCookie: function(name,path,domain,secure){
        this.setAllCookie(name,null,new Date(0),path,domain,secure);
    }
};

var EventUtil = {
    addHandler: function(element, type, handler)
    {
        if(element.addEventListener)
        {
            element.addEventListener(type, handler, false);
        }
        else if(element.attachEvent)
        {
            element.attachEvent("on" + type, handler);
        }
        else
        {
            element["on" + type] = handler;
        }
    },

    getEvent: function(event)
    {
        return event ? event : window.event;
    },

    getTarget: function(event)
    {
        return event.target || event.srcElement;
    },

    getRelatedTarget: function(event)
    {
        if(event.relatedTarget)
        {
            return event.relatedTarget;
        }
        else if(event.toElement)
        {
            return event.toElement;
        }
        else if(event.fromElement)
        {
            return event.fromElement;
        }
        else
        {
            return null;
        }
    },

    getButton: function(event)
    {
        if(document.implementation.hasFeature("MouseEvents", "2.0"))
        {
            return event.button;
        }
        else
        {
            switch(event.button)
            {
                case 0:
                case 1:
                case 3:
                case 5:
                case 7:
                    return 0;
                case 2:
                case 6:
                    return 2;
                case 4:
                    return 1;
            }
        }
    },

    getWheelDelta: function(event)
    {
        if(event.wheelDelta)
        {
            return(client.engine.opera && client.engine.opera < 9.5 ?
                -event.wheelDelta : event.wheelDelta);
        }
        else
        {
            return -event.detail * 40;
        }
    },

    getCharCode: function(event)
    {
        if(typeof event.charCode == "number")
        {
            return event.charCode;
        }
        else
        {
            return event.keyCode;
        }
    },

    getClipboardText: function(event)
    {
        var clipboardData = (event.clipboardData || window.clipboardData);
        return clipboardData.getData("text");
    },

    setClipboardText: function(event, value)
    {
        if(event.clipboardData)
        {
            return event.clipboardData.setData("text/plain", value);
        }
        else if(window.clipboardData)
        {
            return window.clipboardData.setData("text", value);
        }
    },

    preventDefault: function(event)
    {
        if(event.preventDefault)
        {
            event.preventDefault();
        }
        else
        {
            event.returnValue = false;
        }
    },

    removeHandler: function(element, type, handler)
    {
        if(element.removeEventListener)
        {
            element.removeEventListener(type, handler,false);
        }
        else if(element.detachEvent)
        {
            element.detachEvent("on" + type, handler);
        }
        else
        {
            element["on" + type] = null;
        }
    },

    stopPropagation: function(event)
    {
        if(event.stopPropagation)
        {
            event.stopPropagation();
        }
        else
        {
            event.cancelBubble = true;
        }
    }
};