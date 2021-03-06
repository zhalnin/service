/**
 * Created with JetBrains PhpStorm.
 * User: zhalnin
 * Date: 4/20/13
 * Time: 4:31 PM
 * To change this template use File | Settings | File Templates.
 */



AM.Event.addEvent(window, 'load', function() {
    try {
//        var iframeTD = AM.DOM.$('iframe_td');
//        var iframe2 = document.createElement('iframe');
//        iframe2.id = 'iframe_redactor';
//        iframe2.setAttribute('name','iframe_redactor');
//        iframe2.style.width = 200+'px';
//        iframe2.style.height = 200+'px';
//        iframeTD.appendChild(iframe2);


        var form = "",
            form_search = "";
        // scroll window to x=0 and y=0
//        window.scrollTo(0,0);
        // look for first form in page
        for(var z = 0, zlen = document.getElementsByTagName('form').length; z < zlen; z++ ) {
            var tmp = document.getElementsByTagName('form')[z];
            switch( tmp.id ) {
                case 'udid-form':
                    form = tmp;
                    break;
                case 'unlock-form':
                    form = tmp;
                    break;
                case 'unlockDetails-form':
                    form = tmp;
                    break;
                case 'carrier-form':
                    form = tmp;
                    break;
                case 'fastCheck-form':
                    form = tmp;
                    break;
                case 'blacklist-form':
                    form = tmp;
                    break;
                case 'guestbook-form':
                    form = tmp;
                    break;
                case 'login-form':
                    form = tmp;
                    break;
                case 'register-form':
                    form = tmp;
                    break;
                case 'flogin-form':
                    form = tmp;
                    break;
                case 'ractivation-form':
                    form = tmp;
                    break;
                case 'g-search':
                    form_search = tmp;
                    break;
                case 'cart-form':
                    form = tmp;
                    break;
            }
        }
//        var form = document.getElementsByTagName('form')[0],
            // look for button to send
         var button_send = AM.DOM.$("shipping-continue-button"),
            // look for button to reload
            button_reload = AM.DOM.$("shipping-button"),
            button_detail = AM.DOM.$("detail-button"),
            // create block for overlay to show when ajax send
            overlay = document.createElement("div"),
//            // create element <div> for image
//            div_img = document.createElement("div"),
//            // create element <img>
//            overlay_img = document.createElement("img"),
            // create fragment element
//            fragment_overlay = document.createDocumentFragment(),
            // length of elements
            len, lenj,
            // index of iteration
            i, j,
            // блок <div> с кнопкой submit
            submitButton = AM.DOM.$('chipping-continue-button-submit'),
            cancelButton = AM.DOM.$('cancel-button');

        // add event to 'document' for 'keypress'
        AM.Event.addEvent(document,'keypress',function(event){
            // take event
            var e = AM.Event.getEvent(event);

            // если фокус не на поиске и нажат Enter и присутствует форма
            if( document.activeElement.id != 'sp-searchtext' && e.keyCode == 13 && form ){
                // set flag to true - stop watchForm()
//                checking = true;
                // если это не добавить, то при нажатии на Enter перегружается форма
//                AM.Event.stopDefault(event);
                //                // scroll window to x=0 and y=0
                //                window.scrollTo(0,0);
                // start 'watchForm();
//                AMForm.watchForm(form);
            // если фокус на поиске и нажат Enter и присутствует форма поиска и поле поиска не пустое
            } else if( document.activeElement.id == 'sp-searchtext' && e.keyCode == 13 && form_search && /^[0-9a-zа-я]+/i.test( document.activeElement.value ) ) {
//                // если это не добавить, то при нажатии на Enter перегружается форма
                AM.Event.stopDefault(event);
                form_search.submit();
            }else if( document.activeElement.id == 'sp-searchtext' && e.keyCode == 13 && form_search && /^[^0-9a-zа-я]+/i.test( document.activeElement.value ) ) {
                AM.Event.stopDefault(event);
            }
        });

        if( AM.DOM.$('refreshCode') != null ) {
            var refreshCode = AM.DOM.$('refreshCode');
            AM.Event.addEvent( refreshCode, 'click', function( event ) {
                AM.DOM.$('capchaImg').src = "imei_service/view/utils/capcha/capcha.php?"+Math.random();
            });
        }

            if( AM.DOM.$('iframe_redactor') != null ) {
                var theIframe = AM.DOM.$('iframe_redactor'),
                    doc = theIframe.contentWindow.document || theIframe.contentDocument,
                    editorTR = AM.DOM.$('editorTR'),
                    editorTD = AM.DOM.tag('td', editorTR),
                    editorResize = AM.DOM.$('editorResize'),
                    wysiwyg_toolbar = AM.DOM.$('wysiwyg_toolbar');

                for( j = 0, lenj = editorTD.length; j < lenj; j++ ) {
                    with( { num: j }) {
                        AM.Event.addEvent( AM.DOM.first(editorTD[num]), 'click', function( event ) {
                            AM.Event.stopDefault( event );
                            var getX = AM.Position.getX(event),
                                getY = AM.Position.getY(event),
                                targetId = AM.DOM.first(editorTD[num]).id;

                            switch ( targetId ) {
                                case "uploadImage":
                                    doUploadImg(getX, getY);
                                    break;
                                case "url":
                                    doURL(getX, getY);
                                    break;
                                case "image":
                                    doImg(getX, getY);
                                    break;
                                case "emoticon":
                                    doEmoticon(getX, getY);
                                    break;
                                default:
                                    doStyle(targetId);
                                    break;
                            }
                        });
                    }
                }

//                new DragObject( editorResize, wysiwyg_toolbar, theIframe );
                new DragMove( wysiwyg_toolbar );

            }

        adjustAllOverlay();


        // В Гостевой - находим блок <div class='guestbook-all-reply'> - в нем кнопка "Ответить" на комментарий
        // Используем замыкание для назначения каждому отдельному блоку <a></a> HTML-коллекции событие "click",
        // для предотвращения нативной функции (переход по ссылке). Перемещаем блок с формой к нужному комментарию и
        // скроллим страницу в область видимости этого комментария
        var divGuestbookAllReply = document.getElementsByClassName('guestbook-all-reply');
        ( function() {
            for( var i = 0, len = divGuestbookAllReply.length; i < len; i++ ) {
                ( function( num )  {
                    var diva = AM.DOM.tag( 'a', divGuestbookAllReply[num] );
                    AM.Event.addEvent(diva[0], 'click', function( event ) {
                        AM.Event.stopDefault(event);

                        var guestbookForm = AM.DOM.$('guestbook-form'),
                            guestbookReply = AM.DOM.$('guestbookReply'),
                            am = AM.Query.getQueryStringArgs( diva[0].href );

                        guestbookReply.value = am['idp'];
                        divGuestbookAllReply[num].appendChild(guestbookForm);

//                        setTimeout( function() {
//                            var iframes = document.getElementsByTagName('iframe');
//                            for (var j = 0, lenj = iframes.length, doc; j < lenj; ++j) {
//                                doc = iframes[j].contentDocument || iframes[j].contentWindow.document;
//                                doc.designMode = "On";
//                            }
//
//                        }, 1000 );
                        setTimeout( function() {
                            var iframes = document.getElementsByTagName('iframe');
                            for (var j = 0, lenj = iframes.length; j < lenj; ++j) {
                                doc = iframes[j].contentDocument || iframes[j].contentWindow.document;
                                doc.designMode = "On";
                            }

                        }, 1000 );

//                        AM.DOM.parent( divGuestbookAllReply[num] ).scrollIntoView(true);
                        guestbookForm.scrollIntoView( true );
                    } );
                })( i );
            }
         })();



        // Если после отправки формы была ошибка, возвращаем блок с ответом к нужному блоку
        if( AM.DOM.$('guestbookReply') != null ) {
            if( AM.DOM.$('guestbookReply').value != "" ) {
                var guestbookForm = AM.DOM.$('guestbook-form'), // форма для ответа
                    guestbookReply = AM.DOM.$('guestbookReply'),// скрытое поле в форме, хранит idp - id_parent
                    id = AM.DOM.$('guestbookReply').value, // получаем value
                    appendDiv = AM.DOM.$(id); // находим div с нужным id ( ему отвечаем )
                appendDiv.appendChild(guestbookForm); // добавляем форму для ответа к нужному div
                guestbookForm.scrollIntoView( true ); // фокусируемся на родительском посте
            }

        }


        if( form != null ) {
            // set of form's elements
            var elements = form.elements;
        } else {
            return null;
        }

        // Убираем кнопку submit с экрана в скрипте guestbook_addmessage.php
        if( submitButton != null ) {
            AM.DOM.addClass('chipping-continue-button-submit', submitButton );
        }


        // if button of reload is not null, add event 'click' to reload page
        if(button_reload != null){
            AM.Event.addEvent(button_reload,'click', function(event){
                // scroll window to x=0 and y=0
                window.scrollTo(0,0);
                // reload page
                location.reload();
            });
        }


        // or if is not key=='Enter' use button_send 'click'
        if(button_send != null){
            // add event 'click' to button_send
            AM.Event.addEvent(button_send, 'click', function(event){
                // scroll window to x=0 and y=0
//                window.scrollTo(0,0);
                AM.Event.stopDefault(event);
                AMForm.watchForm(form);
                //            showOverlay();
            });
        } else {
            return null;
        }
        // Проверяем наличие на странице кнопки "Отмена" - для отмены ответа на комментарий,
        // если есть, то назначаем событие для возврата всей формы вниз страницы
        if(cancelButton != null ) {
            AM.Event.addEvent(cancelButton, 'click', function( event ) {
                var newsMain = AM.DOM.$('news-main');
                var guestbookForm = AM.DOM.$('guestbook-form');
                AM.DOM.append( newsMain, guestbookForm );
            });
        }




        // if window is loaded, look for input elements, if elements disabled, make it enabled
//        for( i=0,len=elements.length; i<len; i++ ) {
//            if( elements[i].nodeName == 'INPUT' && elements[i].type != 'hidden' &&
//                elements[i].type != 'submit' && elements[i].type != 'button' ) {
//                elements[i].value = '';
//                elements[i].disabled = false;
//            }
//        }

        var wysiwyg = new WysiwygObject();

        // add id to block <div id='overlay'>
        overlay.id = "overlay";
        overlay.onclick = wysiwyg.hideOverlay;
        AM.DOM.append(document.body, overlay);


//        // add event to 'document' for 'keypress'
//        AM.Event.addEvent(document,'keypress',function(event){
//            // take event
//            var e = AM.Event.getEvent(event);
//            // if key is 'Enter' and button pressed for the first time
//            if( document.activeElement.id != 'sp-searchtext' && e.keyCode == 13){
//                // set flag to true - stop watchForm()
//                checking = true;
//                // если это не добавить, то при нажатии на Enter перегружается форма
//                AM.Event.stopDefault(event);
//                //                // scroll window to x=0 and y=0
//                //                window.scrollTo(0,0);
//                // start 'watchForm();
//                AMForm.watchForm(form);
//            } else if( document.activeElement.id == 'sp-searchtext' && e.keyCode == 13 ) {
//                // если это не добавить, то при нажатии на Enter перегружается форма
//                AM.Event.stopDefault(event);
//                console.log('search');
//            }
//        });

        // если скроллим, то экран "ожидания" оптимизируется по всему экрану
        AM.Event.addEvent(document, 'scroll', function(event) {
          adjustAllOverlay();
        } );
//        // если изменяем размер окна, то экран "ожидания" оптимизируется по всему экрану
//        AM.Event.addEvent(window, 'resize', function(event) {
//            adjustAllOverlay();
////            console.log("scroll");
//          adjustOverlay();
////            adjustElem( 'overlay' );
//        } );
//        // если изменяем размер окна, то экран "ожидания" оптимизируется
//        AM.Event.addEvent(document, 'resize', function(event) {
////            console.log("resize");
//            adjustOverlay();
////            adjustElem( 'overlay' );
//        } );



        /**
         * To show image
         * @param id_image
         * @param width
         * @param height
         * @param adm
         */
        function show_img(id_image,width,height,adm) {
            var a,
                b,
                url;
            vidWindowWidth=width;
            vidWindowHeight=height;
            a=(screen.height-vidWindowHeight)/5;
            b=(screen.width-vidWindowWidth)/2;
            features = "top="+ a +
                ",left=" + b +
                ",height=" + vidWindowHeight +
                ",width=" + vidWindowWidth +
                ",toolbar=no," +
                "menubar=no," +
                "location=no," +
                "directories=no," +
                "scrollbars=no," +
                "resizable=no";
            url="show.php?id_image=" + id_image;
            window.open(url,'',features,true);
        }


        /**
         * For detail-button
         * To view whole news
         * @param url
         */
        function detail(url)
        {
            location.href=url;
        }


        try {
            AMForm.watchFieldsTest(form);
        } catch(e){
            console.log(e.message);
        }

        var editor_span = AM.DOM.$('editorSpan'),
            modal_preview = document.createElement("div");

        modal_preview.id = "modal_preview";
        AM.DOM.attr( modal_preview, 'class', 'modal_preview' );
        modal_preview.innerHTML = '<div id="modalPreviewTitle">Предпросмотр</div>'+
            '<div id="modalPreviewContent"></div>';
        document.body.appendChild(modal_preview);





        var modal = document.createElement("div");
        modal.id = "modal";
        modal.innerHTML = '<div id="modalContent"></div>' +
            '<div id="modalTitle"></div>';
        document.body.appendChild(modal);

        if( AM.DOM.$('iframe_redactor') != null ) {
            (function() {
                setTimeout( function() {
//                    var d = document, g = 'getElementsByTagName';
////                    var iframes = document.getElementsByTagName('iframe');
//                    var iframes = d[g]('iframe');
//                    for (var j = 0, leni = iframes.length, doc; j < leni; ++j) {
//                        doc = iframes[j].contentDocument || iframes[j].contentWindow.document;
//                        doc.designMode = "On";
//                    }
                    var ifr = AM.DOM.$('iframe_redactor');
                    doc = ifr.contentDocument || ifr.contentWindow.document;
                    doc.designMode = "On";

                }, 1000 );
            }());
        }

    } catch(e) {
//        alert('error in load.js');
        console.log(e.message);
    }

});