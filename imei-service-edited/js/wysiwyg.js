/**
 * Created with JetBrains PhpStorm.
 * User: zhalnin
 * Date: 13/04/14
 * Time: 16:09
 * To change this template use File | Settings | File Templates.
 */

// Подгружаем смайлики
var smiles = [
    'guestbook/data/files/emoticons/happy.gif','guestbook/data/files/emoticons/laugh.gif',
    'guestbook/data/files/emoticons/silly.gif','guestbook/data/files/emoticons/wink.gif',
    'guestbook/data/files/emoticons/plain.gif','guestbook/data/files/emoticons/angry.gif',
    'guestbook/data/files/emoticons/blush.gif','guestbook/data/files/emoticons/confused.gif',
    'guestbook/data/files/emoticons/cool.gif','guestbook/data/files/emoticons/cry.gif',
    'guestbook/data/files/emoticons/devil.gif','guestbook/data/files/emoticons/grin.gif',
    'guestbook/data/files/emoticons/love.gif','guestbook/data/files/emoticons/mischief.gif',
    'guestbook/data/files/emoticons/sad.gif','guestbook/data/files/emoticons/shocked.gif',
    'guestbook/data/files/emoticons/info.gif','guestbook/data/files/emoticons/plus.gif',
    'guestbook/data/files/emoticons/minus.gif','guestbook/data/files/emoticons/alert.gif'
];
var arrSmiles = [];
for( var i=0, len = smiles.length; i<len; i++ ) {
    arrSmiles[i] = new Image();
    arrSmiles[i].src = smiles[i];
}


function WysiwygObject() {

    this.theIframe = function() {
        return AM.DOM.$('iframe_redactor');
    };
    this.doc = function() {
        return this.theIframe().contentWindow.document || this.theIframe().contentDocument;
    };
    this.hideOverlay = function(){
        AM.DOM.hide(AM.DOM.$("overlay"));
        AM.DOM.hide(AM.DOM.$("modal"));
        AM.DOM.hide(AM.DOM.$("modal_preview"));
    };
    this.showOverlay = function(){
        var over = AM.DOM.$("overlay");
        adjustAllOverlay();
        AM.DOM.fadeIn(over, 50, 10);
    };
    this.showOverlayEmoticon = function() {
        var over = AM.DOM.$("overlay");
        adjustAllOverlay();
        AM.DOM.fadeIn(over, 0, 1 );
    };
    this.openModal = function( param, x, y ){

        switch( param ) {
            case 'uploadImage':
                this.showOverlay();
                this.showFormUploadImage(x,y);
                break;
            case 'url':
                this.showOverlay();
                this.showFormUrl(x,y);
                break;
            case 'image':
                this.showOverlay();
                this.showFormImage(x,y);
                break;
            case 'emoticon':
                this.showOverlayEmoticon();
                this.showEmoticon(x, y);
                break;
            case 'preview':
                this.showOverlay();
                this.showPreview();
                break;
        }

        return false;
    };
    this.showPreview = function() {
        var img = AM.DOM.$("modalPreviewContent");
        if(img.firstChild){
            img.removeChild(img.firstChild);
        }
        img.innerHTML=response;
        AM.DOM.fadeIn(img, 100, 10);
//        AM.DOM.$('showmsg').innerHTML = response;
    };
    this.showFormUploadImage = function(x, y){
        var modalUploadImage = AM.DOM.$('modal');
        AM.Position.setX(modalUploadImage, x);
        AM.Position.setY(modalUploadImage, y);

        var img = AM.DOM.$("modalContent");
        if(img.firstChild){
            img.removeChild(img.firstChild);
        }
        var form = '<iframe style="display: none;" id="uploadFrame" name="uploadFrame"></iframe>' +
            '<form class="main-modal shadowed rounded" enctype="multipart/form-data" action="upload.php" target="uploadFrame" method="post" id="uploadForm">'+
            '<fieldset>'+
            '<legend>Загрузить изображение</legend>'+
            '<div class="two"><label for="filename"><span>Выберите файл</span></label><input type="file" name="filename" id="filename" /></div>'+
            '<p>Максимальный размер файла: 2.0 MB. </p>' +
            '<p>Изображение будет сжато до размера 450px в ширину или 600px в высоту. </p>'+
            '<div class="two"><label for="submit"></label><div type="submit" onclick="submitForm(uploadForm)" class="button" value="Вставить" id="submit">' +
            '<span style=""><span class="effect"></span><span class="label"> Отправить </span></span></div>'+
            '</fieldset>'+
            '</form>';

        img.innerHTML=form;
        AM.DOM.fadeIn(modalUploadImage, 100, 10);
    };
    this.showFormUrl = function(x, y){

        var modalUrl = AM.DOM.$('modal');
        AM.Position.setX(modalUrl, x);
        AM.Position.setY(modalUrl, y);

        var img = AM.DOM.$("modalContent");
        if(img.firstChild){
            img.removeChild(img.firstChild);
        }
        var form = '<iframe style="display: none;" id="uploadUrlFrame" name="uploadUrlFrame"></iframe>' +
            '<form class="main-modal shadowed rounded" action="upload.php" target="uploadUrlFrame" method="post" id="uploadUrlForm">'+
            '<fieldset>'+
            '<legend>Вставить ссылку</legend>'+
            '<div class="two"><label for="url"><span>Введите адрес ссылки</span></label><input type="text" name="url" id="url" value="http://" /></div>'+
            '<div class="two"><label for="submit"></label><div type="submit" onclick="submitForm(uploadUrlForm)" class="button" value="Вставить" id="submit">' +
            '<span style=""><span class="effect"></span><span class="label"> Отправить </span></span></div>'+
            '</fieldset>'+
            '</form>';

        img.innerHTML=form;
        AM.DOM.fadeIn(modalUrl, 100, 10);
    };

    this.showFormImage = function(x, y){

        var modalImage = AM.DOM.$('modal');
        AM.Position.setX(modalImage, x);
        AM.Position.setY(modalImage, y);
        var img = AM.DOM.$("modalContent");
        if(img.firstChild){
            img.removeChild(img.firstChild);
        }
        var form = '<iframe style="display: none;" id="insertImage" name="insertImage"></iframe>' +
            '<form class="main-modal shadowed rounded" action="upload.php" target="insertImage" method="post" id="insertImageForm">'+
            '<fieldset>'+
            '<legend>Прикрепить изображение</legend>'+
            '<div class="two"><label for="image"><span>Введите адрес изображения</span></label><input type="text" name="image" id="image" value="http://" /></div>'+
            '<div class="two"><label for="submit"></label><div type="submit" onclick="submitForm(insertImageForm)" class="button" value="Вставить" id="submit">' +
            '<span style=""><span class="effect"></span><span class="label"> Отправить </span></span></div>'+
            '</fieldset>'+
            '</form>';

        img.innerHTML=form;
        AM.DOM.fadeIn(modalImage, 100, 10);
    };

    this.showEmoticon = function(x, y) {

        var emoticon = AM.DOM.$('modal');

        AM.Position.setX(emoticon, x);
        AM.Position.setY(emoticon, y);
        var img = AM.DOM.$("modalContent");
        if(img.firstChild){
            img.removeChild(img.firstChild);
        }
        var smiles = '<div id="emoticons" style="width: 95px; height: 500px;">'+
            '<div class="mceMenu">'+
            '<table id="menu_wysiwyg" cellspacing="0" cellpadding="0" border="0"><tbody>'+
            '<tr id="mce_0" class="mceMenuItem">'+
            '<td><a href="javascript:;" onclick="return false;" onmousedown="return false;">'+
            '<span class="mceIcon mce_emoticon_happy"></span>'+
            '<span class="mceText" title="Happy">Happy</span></a></td></tr>'+
            '<tr id="mce_1" class="mceMenuItem mceMenuItemEnabled">'+
            '<td><a href="javascript:;" onclick="return false;" onmousedown="return false;">'+
            '<span class="mceIcon mce_emoticon_laugh"></span>'+
            '<span class="mceText" title="Laugh">Laugh</span></a></td></tr>'+
            '<tr id="mce_2" class="mceMenuItem mceMenuItemEnabled">'+
            '<td><a href="javascript:;" onclick="return false;" onmousedown="return false;">'+
            '<span class="mceIcon mce_emoticon_silly"></span>'+
            '<span class="mceText" title="Silly">Silly</span></a></td></tr>'+
            '<tr id="mce_3" class="mceMenuItem mceMenuItemEnabled">'+
            '<td><a href="javascript:;" onclick="return false;" onmousedown="return false;">'+
            '<span class="mceIcon mce_emoticon_wink"></span>'+
            '<span class="mceText" title="Wink">Wink</span></a></td></tr>'+
            '<tr id="mce_4" class="mceMenuItem mceMenuItemEnabled">'+
            '<td><a href="javascript:;" onclick="return false;" onmousedown="return false;">'+
            '<span class="mceIcon mce_emoticon_plain"></span>'+
            '<span class="mceText" title="Plain">Plain</span></a></td></tr>'+
            '<tr id="mce_5" class="mceMenuItem mceMenuItemEnabled">'+
            '<td><a href="javascript:;" onclick="return false;" onmousedown="return false;">'+
            '<span class="mceIcon mce_emoticon_angry"></span>'+
            '<span class="mceText" title="Angry">Angry</span></a></td></tr>'+
            '<tr id="mce_6" class="mceMenuItem mceMenuItemEnabled">'+
            '<td><a href="javascript:;" onclick="return false;" onmousedown="return false;">'+
            '<span class="mceIcon mce_emoticon_blush"></span>'+
            '<span class="mceText" title="Blush">Blush</span></a></td></tr>'+
            '<tr id="mce_7" class="mceMenuItem mceMenuItemEnabled">'+
            '<td><a href="javascript:;" onclick="return false;" onmousedown="return false;">'+
            '<span class="mceIcon mce_emoticon_confused"></span>'+
            '<span class="mceText" title="Confused">Confused</span></a></td></tr>'+
            '<tr id="mce_8" class="mceMenuItem mceMenuItemEnabled">'+
            '<td><a href="javascript:;" onclick="return false;" onmousedown="return false;">'+
            '<span class="mceIcon mce_emoticon_cool"></span>'+
            '<span class="mceText" title="Cool">Cool</span></a></td></tr>'+
            '<tr id="mce_9" class="mceMenuItem mceMenuItemEnabled">'+
            '<td><a href="javascript:;" onclick="return false;" onmousedown="return false;">'+
            '<span class="mceIcon mce_emoticon_cry"></span>'+
            '<span class="mceText" title="Cry">Cry</span></a></td></tr>'+
            '<tr id="mce_10" class="mceMenuItem mceMenuItemEnabled">'+
            '<td><a href="javascript:;" onclick="return false;" onmousedown="return false;">'+
            '<span class="mceIcon mce_emoticon_devil"></span>'+
            '<span class="mceText" title="Devil">Devil</span></a></td></tr>'+
            '<tr id="mce_11" class="mceMenuItem mceMenuItemEnabled">'+
            '<td><a href="javascript:;" onclick="return false;" onmousedown="return false;">'+
            '<span class="mceIcon mce_emoticon_grin"></span>'+
            '<span class="mceText" title="Grin">Grin</span></a></td></tr>'+
            '<tr id="mce_12" class="mceMenuItem mceMenuItemEnabled">'+
            '<td><a href="javascript:;" onclick="return false;" onmousedown="return false;">'+
            '<span class="mceIcon mce_emoticon_love"></span>'+
            '<span class="mceText" title="Love">Love</span></a></td></tr>'+
            '<tr id="mce_13" class="mceMenuItem mceMenuItemEnabled">'+
            '<td><a href="javascript:;" onclick="return false;" onmousedown="return false;">'+
            '<span class="mceIcon mce_emoticon_mischief"></span>'+
            '<span class="mceText" title="Mischief">Mischief</span></a></td></tr>'+
            '<tr id="mce_14" class="mceMenuItem mceMenuItemEnabled">'+
            '<td><a href="javascript:;" onclick="return false;" onmousedown="return false;">'+
            '<span class="mceIcon mce_emoticon_sad"></span>'+
            '<span class="mceText" title="Sad">Sad</span></a></td></tr>'+
            '<tr id="mce_15" class="mceMenuItem mceMenuItemEnabled">'+
            '<td><a href="javascript:;" onclick="return false;" onmousedown="return false;">'+
            '<span class="mceIcon mce_emoticon_shocked"></span>'+
            '<span class="mceText" title="Shocked">Shocked</span></a></td></tr>'+
            '<tr id="mce_16" class="mceMenuItem mceMenuItemEnabled">'+
            '<td><a href="javascript:;" onclick="return false;" onmousedown="return false;">'+
            '<span class="mceIcon mce_emoticon_info"></span>'+
            '<span class="mceText" title="Info">Info</span></a></td></tr>'+
            '<tr id="mce_17" class="mceMenuItem mceMenuItemEnabled">'+
            '<td><a href="javascript:;" onclick="return false;" onmousedown="return false;">'+
            '<span class="mceIcon mce_emoticon_plus"></span>'+
            '<span class="mceText" title="Plus">Plus</span></a></td></tr>'+
            '<tr id="mce_18" class="mceMenuItem mceMenuItemEnabled">'+
            '<td><a href="javascript:;" onclick="return false;" onmousedown="return false;">'+
            '<span class="mceIcon mce_emoticon_minus"></span>'+
            '<span class="mceText" title="Minus">Minus</span></a></td></tr>'+
            '<tr id="mce_19" class="mceMenuItem mceMenuItemEnabled">'+
            '<td><a href="javascript:;" onclick="return false;" onmousedown="return false;">'+
            '<span class="mceIcon mce_emoticon_alert"></span>'+
            '<span class="mceText" title="Alert">Alert</span></a></td></tr>'+
            '</tbody></table></div></div>';
        img.innerHTML=smiles;
        AM.Event.addEvent(AM.DOM.$('menu_wysiwyg'), 'click', function(e) {
            var event = AM.Event.getEvent(e),
                target = AM.Event.getTarget(event),
                that = new WysiwygObject();
            if( target.nodeName == 'SPAN' ) {
                that.theIframe().focus();
                var targetId = AM.DOM.parent(target,3).id,
                    targetSplit = targetId.split('_'),
                    imageSrc = arrSmiles[parseInt(targetSplit[1])].src;
                that.doc().execCommand('insertHtml', false, "<img src="+imageSrc+" />" );
                that.hideOverlay();
                that.theIframe().focus();

            }
        });
        emoticon.style.display = 'block';
//        AM.DOM.fadeIn(emoticon, 100, 5);
    };


    this.uploadUrlSuccess = function( url ) {
        if( url === 'error' ) {
            this.hideOverlay();
            this.theIframe().focus();
            return;
        }
        this.doc().execCommand('createlink', null, url );
        this.hideOverlay();
        this.theIframe().focus();
    };

    this.uploadSuccess = function( path ) {
        if( path === 'error' ) {
            this.hideOverlay();
            this.theIframe().focus();
            return;
        }
        this.doc().execCommand('InsertImage', null, path );
        this.hideOverlay();
        this.theIframe().focus();
    };

    this.uploadInsertImageSuccess = function( image, img_width, img_height ) {
        if( image === 'error' ) {
            this.hideOverlay();
            this.theIframe().focus();
            return;
        }
        this.doc().execCommand('insertHtml', false, "<img src="+image+" height="+img_height+" width="+img_width+" />" );
        this.hideOverlay();
        this.theIframe().focus();
    };

}





var wysiwyg = new WysiwygObject();


function doStyle(style) {
    wysiwyg.doc().execCommand(style, false, null);
    wysiwyg.theIframe().focus();
}

function doURL(x, y) {
    wysiwyg.openModal( 'url', x, y );
}

function doUploadImg(x, y) {
    wysiwyg.openModal( 'uploadImage', x, y );
}

function doImg(x, y) {
    wysiwyg.openModal( 'image', x, y );
}

function doEmoticon(x, y) {
    wysiwyg.openModal( 'emoticon', x, y );
}

function submitForm(param) {
    param.submit();
}

function previewPost() {
    var modal_preview = AM.DOM.$('modal_preview'),
        editor_span = AM.DOM.$('editorSpan'),
        editor_span_width = AM.Position.getElementLeft(editor_span),
        editor_span_height = AM.Position.getElementTop(editor_span);

    AM.Position.setX(modal_preview, editor_span_width);
    AM.Position.setY(modal_preview, editor_span_height);
    var content = wysiwyg.doc().body.innerHTML;
    var amp = content.replace(/&amp;/g,'');
    var nbsp = amp.replace(/&nbsp;/g,'');

    AM.Ajax.ajax({
        'method':'POST',
        'url': 'ajax_handle.php',
        'postParams': 'mode=preview&text='+nbsp,
        'onSuccess': handleResultPreview
    });

}


function handleResultPreview( response ) {
//    var amp = response.replace(/&amp;/g,'&');
//    var nbsp = amp.replace(/&nbsp;/g,'');
    wysiwyg.showOverlay();
    var img = AM.DOM.$("modalPreviewContent"),
        modal_preview = AM.DOM.$('modal_preview');
    if(img.firstChild){
        img.removeChild(img.firstChild);
    }
    var dialog_response = "<div class=\"dialog_response main-modal rounded shadowed\" style='width: 700px;'>"+response+"</div>";
    img.innerHTML=dialog_response;
    AM.DOM.fadeIn(modal_preview, 100, 10);

//    wysiwyg.openModal('preivew');
//    AM.DOM.$('showmsg').innerHTML = response;
}


function handleError( error ) {
    console.log( error );

}
