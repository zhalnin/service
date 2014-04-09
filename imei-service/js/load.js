/**
 * Created with JetBrains PhpStorm.
 * User: zhalnin
 * Date: 4/20/13
 * Time: 4:31 PM
 * To change this template use File | Settings | File Templates.
 */



AM.Event.addEvent(window, 'load', function() {
    try{

        // scroll window to x=0 and y=0
        window.scrollTo(0,0);
        // look for first form in page
        var form = document.getElementsByTagName('form')[0],
        // look for button to send
            button_send = AM.DOM.$("shipping-continue-button"),
        // look for button to reload
            button_reload = AM.DOM.$("shipping-button"),
            button_detail = AM.DOM.$("detail-button"),
        // create block for overlay to show when ajax send
            overlay = document.createElement("div"),
        // create element <div> for image
            div_img = document.createElement("div"),
        // create element <img>
            overlay_img = document.createElement("img"),
        // create fragment element
            fragment_overlay = document.createDocumentFragment(),
        // length of elements
            len,
        // index of iteration
            i;

        if( form != null ) {
            // set of form's elements
            var elements = form.elements;
        } else {
            return null;
        }


        // if button of reload is not null, add event 'click' to reload page
        if(button_reload != null){
            AM.Event.addEvent(button_reload,'click', function(event){
                // scroll window to x=0 and y=0
                window.scrollTo(0,0);
                // reload page
                location.reload();
            });
        } else {
            return null;
        }


        // or if is not key=='Enter' use button_send 'click'
        if(button_send != null){
            // add event 'click' to button_send
            AM.Event.addEvent(button_send, 'click', function(event){
                // scroll window to x=0 and y=0
//                window.scrollTo(0,0);
                //stopDefault(e);
                AMForm.watchForm(form);
                //            showOverlay();
            });
        } else {
            return null;
        }


        // if window is loaded, look for input elements, if elements disabled, make it enabled
        for( i=0,len=elements.length; i<len; i++ ) {
            if( elements[i].nodeName == 'INPUT' && elements[i].type != 'hidden' ) {
                elements[i].value = '';
                elements[i].disabled = false;
            }
        }


        // add id to block <div id='overlay'>
        overlay.id = "overlay";
        // add <div id='div_img'>
        div_img.id = "div_img";
        // add to <img src='images/loading2.gif'>
        overlay_img.src = "images/loading2.gif";
        // add <div style=align:center>
        div_img.style.align = "center";
        // add <img style=width:100px>
        overlay_img.style.width = 100+"px";
        // add <img style=height:75px>
        overlay_img.style.height = 75+"px";

        // add <img> to <div id='div_img'>
//        div_img.appendChild(overlay_img);
        AM.DOM.append(div_img, overlay_img);
        // add <div id='div_img'><img></div> to <div id='overlay'>
//        overlay.appendChild(div_img);
        AM.DOM.append(overlay, div_img);
        // Добавление затемнения к DOM пока скрыто, до запуска ajax
//        document.body.appendChild(overlay);
        AM.DOM.append(document.body, overlay)



        // add event to 'document' for 'keypress'
        AM.Event.addEvent(document,'keypress',function(event){
            // take event
            var e = AM.Event.getEvent(event);
            // if key is 'Enter' and button pressed for the first time
            if(e.keyCode == 13){
                // set flag to true - stop watchForm()
                checking = true;
                // если это не добавить, то при нажатии на Enter перегружается форма
                AM.DOM.stopDefault(event);
//                // scroll window to x=0 and y=0
//                window.scrollTo(0,0);
                // start 'watchForm();
                AMForm.watchForm(form);

            }
        });

        // если скроллим, то экран "ожидания" оптимизируется по всему экрану
        AM.Event.addEvent(document, 'scroll', function(event) {
          adjustAllOverlay();
        } );
        // если изменяем размер окна, то экран "ожидания" оптимизируется по всему экрану
        AM.Event.addEvent(window, 'resize', function(event) {
            adjustAllOverlay();
//            console.log("scroll");
          adjustOverlay();
//            adjustElem( 'overlay' );
        } );
        // если изменяем размер окна, то экран "ожидания" оптимизируется
        AM.Event.addEvent(document, 'resize', function(event) {
//            console.log("resize");
            adjustOverlay();
//            adjustElem( 'overlay' );
        } );



        /**
         * To show image
         * @param id_image
         * @param width
         * @param height
         * @param adm
         */
        function show_img(id_image,width,height,adm)
        {
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
        function detail_button(url) {
            alert("detail");
            location.href=url;
        }


        try {
            AMForm.watchFieldsTest(form);
        } catch(e){
            console.log(e.message);
        }

//        adjustOverlay();
//        showOverlay();
//        adjustOverlay();

    } catch(e) {
//        alert('error in load.js');
//        console.log(e.message);
    }

} );