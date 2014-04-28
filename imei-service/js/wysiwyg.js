/**
 * Created with JetBrains PhpStorm.
 * User: zhalnin
 * Date: 13/04/14
 * Time: 16:09
 * To change this template use File | Settings | File Templates.
 */

function init() {
//            document.getElementById( 'iframe_redactor' ).contentWindow.document.designMode = "On";
    frames['iframe_redactor'].contentWindow.document.designMode = 'On';
}

function doStyle( style ) {
    document.getElementById( 'iframe_redactor').contentWindow.document.execCommand( style, false, null );
}

function doURL() {
    var myLink = prompt( "Enter a URL:", "http://" );
    if ( ( myLink != null ) && ( myLink != "" ) ) {
        document.getElementById( "iframe_redactor").contentWindow.document.execCommand( "CreateLink", false, myLink );
    }
}

function handleRequest(resvalue) {
    if( resvalue == 'ok' ) {
        document.getElementById('showmsg').innerHTML = '<font color="#009933">Спасибо, доставлено!</font>';
    } else {
        document.getElementById('showmsg').innerHTML = '<font color="#FF0000">' + resvalue + '</font>';
    }
}

function addTextToBase() {
    var myIFrame = document.getElementById('iframe_redactor'),
        content = encodeURIComponent( myIFrame.contentWindow.document.body.innerHTML ) ,
        params = "text="+content;

    AM.Ajax.ajax({method:'POST',
        url: 'script.php',
        onSuccess: handleRequest,
        postParams: params
    });

    document.getElementById('showmsg').innerHTML = '';
}