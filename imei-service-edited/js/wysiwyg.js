/**
 * Created with JetBrains PhpStorm.
 * User: zhalnin
 * Date: 13/04/14
 * Time: 16:09
 * To change this template use File | Settings | File Templates.
 */


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
    this.openModal = function( param, x, y ){
        this.showOverlay();
        switch( param ) {
            case 'uploadImage':
                this.showFormUploadImage(x,y);
                break;
            case 'url':
                this.showFormUrl(x,y);
                break;
            case 'image':
                this.showFormImage(x,y);
                break;
            case 'preview':
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
        AM.DOM.fadeIn(modal_preview, 100, 10);
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
        AM.DOM.fadeIn(modal, 100, 10);
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
        AM.DOM.fadeIn(modal, 100, 10);
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
            '<div class="two"><label for="submit"></label><div type="submit" onclick="submitForm(insertImageForm);" class="button" value="Вставить" id="submit">' +
            '<span style=""><span class="effect"></span><span class="label"> Отправить </span></span></div>'+
            '</fieldset>'+
            '</form>';

        img.innerHTML=form;
        AM.DOM.fadeIn(modal, 100, 10);
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

//function sendPost() {
//    var modal_preview = AM.DOM.$('modal_preview'),
//        editor_span = AM.DOM.$('editorSpan'),
//        editor_span_width = AM.Position.getElementLeft(editor_span),
//        editor_span_height = AM.Position.getElementTop(editor_span);
//    AM.Position.setX(modal_preview, editor_span_width);
//    AM.Position.setY(modal_preview, editor_span_height);
//    var content = wysiwyg.doc().body.innerHTML;
//    var result = content.replace(/&nbsp;/, '' );
//    AM.Ajax.ajax({
//        'method':'POST',
//        'url': 'ajax_handle.php',
//        'postParams': 'mode=submit&text='+result,
//        'onSuccess': handleResult
//    });
//}

function handleResultPreview( response ) {
//    var amp = response.replace(/&amp;/g,'&');
//    var nbsp = amp.replace(/&nbsp;/g,'');
    wysiwyg.showOverlay();
    var img = AM.DOM.$("modalPreviewContent");
    if(img.firstChild){
        img.removeChild(img.firstChild);
    }
    var dialog_response = "<div class=\"dialog_response main-modal rounded shadowed\" style='width: 700px;'>"+response+"</div>";
    img.innerHTML=dialog_response;
    AM.DOM.fadeIn(modal_preview, 100, 10);

//    wysiwyg.openModal('preivew');
//    AM.DOM.$('showmsg').innerHTML = response;
}

//function handleResult( response ) {
//    wysiwyg.showOverlay();
//    var img = AM.DOM.$("modalPreviewContent");
//    if(img.firstChild){
//        img.removeChild(img.firstChild);
//    }
//    var dialog_response = "<div class=\"dialog_response main-modal rounded shadowed\" style='width: 700px;'>"+response+"</div>";
//    img.innerHTML=dialog_response;
//    AM.DOM.fadeIn(modal_preview, 100, 10);
//
////    wysiwyg.openModal('preivew');
////    AM.DOM.$('showmsg').innerHTML = response;
//}
function handleResult( response ) {
    var textareaIframe = AM.DOM.$('textareaIframe');

    var amp = response.replace(/&amp;/g,'');
    var nbsp = amp.replace(/&nbsp;/g,'');

//    console.log(nbsp);

    textareaIframe.value = nbsp ;

}
function handleError( error ) {
    console.log( error );

}
