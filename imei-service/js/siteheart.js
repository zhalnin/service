(function(e){
    if(!("SH"in e)){
        e.SH={};
        SH.DEBUG=!1;
        SH.TEST=!1;
        SH.DEV=!1;
        SH.VERSION="";
        SH.VERSIONS={};
        SH.PROTOCOL="https:"==document.location.protocol?"https:":"http:";
        SH.HOST=SH.PROTOCOL+"//siteheart.com";
        SH.HOST_TEST=SH.PROTOCOL+"//test.siteheart.com";
        SH.API_HOST=SH.PROTOCOL+"//esapi.siteheart.com";
        SH.API_HOST_TEST=SH.PROTOCOL+"//test.siteheart.com";
        SH.AVATAR_HOST=SH.PROTOCOL+"//esapi.siteheart.com";
        SH.AVATAR_HOST_TEST=SH.PROTOCOL+"//test.siteheart.com";
        SH.STATUS_HOST=SH.PROTOCOL+"//webindicator.siteheart.com";
    SH.STATUS_HOST_TEST=SH.PROTOCOL+"//webindicatortest.siteheart.com";
        SH.CDN_HOST=SH.PROTOCOL+"//static.siteheart.com";
        "http:"==SH.PROTOCOL&&(SH.CDN_HOST=SH.PROTOCOL+"//mediacdn.siteheart.com");
        SH.CDN_HOST_TEST=SH.PROTOCOL+"//stat.siteheart.com";
        SH.CONFIG={};
        SH.FRONT_SERVERS=[];
        SH.dir=SH.CDN_HOST+"/apps/";
        SH.autoId=1;
        SH.readyState=0;
        SH._GET={};
        SH.JSON={};
        var j={};
        SH._=function(a,b){
            if(!(a in j))return a;
            var c=j[a];b||(b={});
            return c.replace(/\{([\w\.]*)\}/g,function(a,c){var e=c.split(".");
                c=e.shift();
        var g=c in b?b[c]:"",k=0;
                for(;k<e.length;k++)g=g[e[k]];
                return null===g||void 0===g?"":g})};
        SH._get=function(a){return!a?j:j[a]};
        SH._set=function(a,b){return j[a]=b};
        SH.log=function(a,b){if(SH.DEBUG&&"console"in e&&"log"in console){"object"==typeof a&&(a=SH.JSON.stringify(a));
            var c=new Date,c=c.getHours()+":"+c.getMinutes()+":"+c.getSeconds()+":"+c.getMilliseconds();
            b?"object"==typeof b&&(b=SH.JSON.stringify(b)):b="";
            console.log("["+c+"] "+a+" "+b)}};
        SH.setDefaultLang=function(a){var b="en",c=navigator, c=(c.language||c.systemLanguage||c.userLanguage||b).substr(0,2).toLowerCase();
            a=a?a:"ru en de dk eg es lv cn pt".split(" ");
            SH._GET.language&&-1!=jSH.inArray(SH._GET.language,a)?b=SH._GET.language:-1!=jSH.inArray(c,a)&&(b=c);
            return SH.language=b};
        SH.tmplLoad=function(a,b){
            jSH.ajax({url:a,cache:!0,async:!0,data:{version:SH.VERSION},
                success:function(a){SH.tmplParse(a,b)},
                complete:function(){}})};
        SH.tmplParse=function(a,b){
            "object"==typeof a&&(a=a.data);
            var c=RegExp('<template name="([a-zA-Z0-9_-]*)"[^>]*>(.*?)</template>',"gi"),d;a=a.replace(/[\r\t\n]/g,"").replace(/\s{2,}/g," ").replace(/>\s</g,"><");
            try{
                for(;null!=(d=c.exec(a));)jSH.template(d[1],d[2])}catch(f){console.error("ERROR parse template "+d[1],d[2])}"function"==typeof b&&b(a)
        };
        SH.addJS=function(a,b){var c=document.getElementsByTagName("head")[0]||document.documentElement,d=document.createElement("script");
            d.src=a;d.async=!0;
            d.type="text/javascript";
            d.setAttribute("charset","UTF-8");var f=!1;
            d.onload=d.onreadystatechange=function(){
                if(!f&&(!this.readyState||"loaded"===this.readyState||"complete"===this.readyState))f=!0,b&&b(),d.onload=d.onreadystatechange=null};
            c.insertBefore(d,c.firstChild);return d};
        SH.addCSS=function(a,b){
            var c=document.createElement("link");
            c.rel="stylesheet";
            c.href=a;
            var d=!1;
            c.onload=c.onreadystatechange=function(){
                if(!d&&(!this.readyState||"loaded"===this.readyState||"complete"===this.readyState))d=!0,b&&b(),c.onload=c.onreadystatechange=null};
            SH.addFile(c);
            return c
        };
        SH.addFile=function(a){
            (document.getElementsByTagName("head")[0]||document.getElementsByTagName("body")[0]).appendChild(a)};
        var n=[];
        SH.widget=function(a,b){
            var c=b.callback,d=SH.DEV?!0:!1,f=d?a+"dev":a;
            if(!(a in SH)||!(a.toLowerCase()in n))SH.getVersion(a,b,function(e){
                SH.getScript(e.files.js2||[],0,b,function(){
                    jSH.getScript(SH.dir+f.toLowerCase()+"/js/app.start.js?version="+e.version,function(){
                        SH.widgetStart(a,b,c,d)})})},d),n[a.toLowerCase()]=1;
            else{
                var e;e=setInterval(function(){
                    a in SH&&(clearInterval(e),SH.widgetStart(a,b,c,d))},10)}};
        SH.widgetStart=function(a,b,c,d){
            a=new SH[a](b,d);
            a.init();
            "function"==typeof c&&c(a,b)};
        var l={},p={};
        SH.getVersion=function(a,b,c,d){d=d?a+"dev":a;
            var f=("app"in b?b.app:a.toLowerCase())+".version.js";if(!(f in l)&&!(f in p)){
                p[f]=1;
                d=SH.dir+d.toLowerCase()+"/"+f;
                var h="SHVersion"+a;
                b=SH.versionCache(a,b);
                e[h]=function(b){
                    SH.DEBUG&&(b.version+=(new Date).getTime());
                    SH.VERSION=b.version;
                    SH.VERSIONS[a]=b;
                    l[f]=b;
                    c(b)
                };
                jSH.ajax({url:d+"?callback=?&version="+b,cache:!0,async:!0,jsonpCallback:h,dataType:"json",crossDomain:!0})
            }else{
                var g;
        g=setInterval(function(){
            a in SH&&(clearInterval(g),c(l[f]))},10)}};
        SH.versionCache=function(a,b){
            if(b&&b.version)
                return b.version;
            if(!("localStorage"in e))
                return(new Date).getTime();
            var c=e.localStorage.getItem("Version"+a),d=(new Date).getTime();
            return!c||parseInt(c)<d-36E5?(e.localStorage.setItem("Version"+a,d),d):c};
        SH.getScript=function(a,b,c,d){
            if(!a||!a.length)"function"==typeof d&&d();
            else{
                var e=0;
                for(b=0;b<a.length;b++)c=a[b],c=c.replace("{time}",(new Date).getTime()),c=c.replace("{cdn}", SH.CDN_HOST),c=-1==c.indexOf("?")?c+("?version="+SH.VERSION):c+("&version="+SH.VERSION),SH.addJS(c,function(){
                    e++;a.length==e&&"function"==typeof d&&d()
                })
            }
        };
        SH.getCSS=function(a,b,c){
            if(a&&a.length)
                for(b=0;b<a.length;b++){
                    var d=a[b],d=d.replace("{time}",(new Date).getTime()),d=d.replace("{cdn}",SH.CDN_HOST),d=-1==d.indexOf("?")?d+("?version="+SH.VERSION):d+("&version="+SH.VERSION);
                    jSH.getCSS(d,function(){},function(){},c)}};
        SH.app=function(a,b,c){SH.getVersion(a,b,function(a){
            SH.getCSS(a.files.css,0,b);
            SH.getScript(a.files.js,0,b,c)},b.dev||SH.DEV)
        };
        var m=[];
        SH.ready=function(a){
            return 1==SH.readyState?a():m.push(a)
        };
        SH.init=function(){
            if(!("jSH"in e))
                return SH.addJS(SH.dir+"js/jquery-sh.js",SH.init);
            if(!("JSON"in e))
                return SH.addJS(SH.dir+"js/json.js",SH.init);
            SH.JSON.stringify=SH.JSON.stringify||JSON.stringify||JSON.encode||JSON.toJSONString;
            SH.JSON.parse=SH.JSON.parse||JSON.parse||JSON.decode||JSON.parseJSONString;
            "toJSON"in Array.prototype&&delete Array.prototype.toJSON;
            jSH.ajaxSetup({cache:!0});
        SH.readyState=1;
            for(var a=0;a<m.length;a++)
                m[a]();
            return!0
        };
        SH.setTest=function(){
            SH.HOST=SH.HOST_TEST;
            SH.TEST=!0;
            SH.CDN_HOST=SH.CDN_HOST_TEST;
            SH.dir=SH.CDN_HOST+"/apps/";
            SH.STATUS_HOST=SH.STATUS_HOST_TEST;
            SH.API_HOST=SH.API_HOST_TEST;
            SH.AVATAR_HOST=SH.AVATAR_HOST_TEST};
        SH.setDev=function(){
            SH.DEV=!0
        };
        setTimeout(function(){
            "SHDeveloper"in e&&SH.setTest();
            SH._GET.sh_debug&&(SH.DEBUG=!0);
            SH._GET.sh_test&&SH.setTest();
            SH._GET.sh_dev&&(SH.DEV=!0);
            "SH_CDN_HOST"in e?(SH.CDN_HOST=SH_CDN_HOST,SH.dir=SH.CDN_HOST+"/apps/"):SH._GET.sh_cdn&&(SH_CDN_HOST=decodeURIComponent(SH._GET.sh_cdn),SH.CDN_HOST=SH_CDN_HOST,SH.dir=SH.CDN_HOST+"/apps/");
            "_shcp"in e&&SH.ready(function(){
                var a=[];
                jSH.isArray(_shcp)?a=_shcp:a.push(_shcp);jSH(a).map(function(){
                    try{SH.DEBUG=SH.DEBUG||this.debug,SH.TEST=SH.TEST||this.test,SH.DEV=SH.DEV||this.dev,SH.TEST&&SH.setTest(),SH.DEV&&SH.setDev(),SH.widget(this.widget,this)
                    }catch(a){
                        SH.DEBUG&&console.error(a)}})});
            SH.init()
        },2);
        SH.queryParams=function(a,b){
            for(var c={},d=a.substring("?"),d=d.replace("?",""),d=d.split("&"),e=0; e<d.length;e++){
                var h=d[e].split("=");
                b&&-1!=jSH.inArray(h[0],b)||(c[h[0]]=h[1])
            }
            return c
        };
        e.location.search&&(SH._GET=SH.queryParams(e.location.search))
    }
})(window);

