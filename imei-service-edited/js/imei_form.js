/**
 * Created by zhalnin on 17/01/14.
 */

var AMForm = {

    /**
     * Check - to check all fields in form's inputs while 'validateField()'
     * Проверка: либо значение, либо пустая строка!!!
     */
    check: {
        required: {
            msg: "Это поле обязательно к заполнению",
                test: function(obj, load) {
                return obj.value.length > 0 || load || obj.value == obj.defaultValue
            }
        },
        email: {
            msg: "Введите корректный электронный адрес",
                test: function(obj, load) {
                return !obj.value || /^[a-z0-9_+.-]+\@(?:[a-z0-9-]+\.)+[a-z]{2,4}$/i.test(obj.value);
            }
        },
        imei: {
            msg: "IMEI не верен, проверьте еще раз",
                test: function(obj) {
                obj.value = obj.value.replace(new RegExp("\\s+",'g'),'');
                return !obj.value || /^[039][0-9]{14}$/.test(obj.value);
            }
        },
        udid: {
            msg: "UDID не верен, проверьте еще раз",
                test: function(obj) {
                obj.value = obj.value.replace(new RegExp("\\s+",'g'),'');
                return !obj.value || /^[a-z0-9]{40}$/.test(obj.value);
            }
        },
        name: {
            msg: "Имя должно быть длиной больше 3-х символов",
            test: function(obj) {
                return !obj.value || obj.value.length > 2;
            }
        },
        textarea: {
            msg: "Текст должен быть длиной минимум из двух слов",
            test: function(obj) {
                return !obj.value || obj.value.match( new RegExp("[-a-zа-я0-9_.]{1,20} [-a-zа-я0-9_.]{1,20}",'i'));
//                return obj.value.length >= 10;
            }
        }
        ,
        code: {
            msg: "Не верный код",
            test: function( obj ) {
                    return !obj.value ||  (  obj.value.match( new RegExp("[a-z0-9]{6}") ) ?  true : false );
            }
        }
    },

    // flag to indicate status of pressing button to send form (enter or click)
    watchedForm: false,

    /**
     * Check form
     * @param form
     */
    watchForm: function(form) {

        if( ( AM.DOM.$('mode') != null ) && ( AMForm.validateForm(form) == true ) ) {
            // if button pressed for the first time
            if( this.watchedForm == false ){
                // take params from input
                this.param = AM.DOM.$('imei').value;
                // send ajax to take IMEI info for UDID
                start_ajax(this.param);
                // scroll page to view input 'imei'
                AM.DOM.$('shipping-box-title').scrollIntoView(true);
                // set flag to true, it means button is pressed already
                this.watchedForm = true;
            }
        }
        else
        if( AMForm.validateForm(form) == true ){
            // if button pressed for the first time
            if( this.watchedForm == false ) {

                // set value in field 'action' in input
//                form.action = "sendMail.php";
                if( AM.DOM.$('shipping-box-title') != null ) {
//                    form.action = "sendMail.php";
                    form.action = "sendMail.php";
                }

                wysiwyg.showOverlay();
                setTimeout( function() {
                        // submit form for Unlock, Carrier check, Blacklist Check
                        form.submit();
                },1000);
                if( AM.DOM.$('shipping-box-title') != null ) {
                    // scroll page to view input 'imei'
                    AM.DOM.$('shipping-box-title').scrollIntoView(true);
                } else if( AM.DOM.$('guestbook-form') != null ) {
                    AM.DOM.$('guestbook-form').scrollIntoView(true);
                }



                // set flag to true, it means button is pressed already
                this.watchedForm = true;
            } else {
                console.log("no");
            }
        }
    },

    /**
     * Используя замыкание, находим нужный элемент формы "span"
     * добавляем по фокусу или убирания фокуса
     * родительскому элементу "label" видимость или невидимость
     * @param form
     */
    watchFieldsTest: function(form){
        var elem = form.elements,
            result,
            len,
            span,
            span_tag,
            span_text,
            i,
        // чтобы сохранить текущую область видимости, а в метод передавать локальную область видимости
            that = this;
        // замыкание для того, чтобы привязать событие к тому элементу,
        // который нужен
        for(i = 0, len = elem.length; i<len; i++){
            if(elem[i].nodeName != 'FIELDSET' && elem[i].type != 'hidden'){
                result =  function(num){

//                console.log(elem[num]);

//                    // для теста
//                    AM.Event.addEvent(elem[num], 'click', function(){
//                        span = AM.DOM.parent(elem[num]);
//                        span_tag = AM.DOM.tag('span',span);
//                        span_text = AM.DOM.getInnerText(span_tag[0]);
//    //                            console.log(span_text);
//                    });

                    // при фокусе на элементе span "подсказка" убирается
                    AM.Event.addEvent(elem[num],'focus',function(event){
//                        console.log(1);
//                        console.log(elem[num]);
                        //его родитель label делаем невидимым
//                        AM.DOM.prev(elem[num]).style.display = "none";
                        //его родитель label делаем полупрозрачным
                        AM.DOM.prev(elem[num]).style.opacity = 0.5;
                        // при фокусе на элементе, выделяем весь текст - удобно при ошибке заполнения
                        AM.DOM.selectText(elem[num],0,elem[num].value.length);
                    });

                    // при смене фокуса с элемента span "подсказка" появляется
                    AM.Event.addEvent(elem[num],'blur',function(event){
//                        console.log(2);
                        // если значение пустое
                        if(elem[num].value == '') {
                            // делаем его родителя label видимым
                            AM.DOM.prev(elem[num]).style.display = "";
                            // делаем его родителя label видимым
                            AM.DOM.prev(elem[num]).style.opacity = 1;
                        }
                    });

                    // при изменении значения поля, проверяем его на корректность
                    AM.Event.addEvent(elem[num], "change", function(event){
//                        console.log(3);
                       that.validateField(elem[num]);
                    });

                    // при загрузке страницы фокус находится на первом элементе управления
                    // при нажатии на клавишу убирается "подсказка"
                    AM.Event.addEvent( elem[num], "keypress", function( event ) {
//                        console.log(4);
                        var e = AM.Event.getEvent( event );
                        if(e.keyCode != 13 || e.keyCode != 9 ) {
                            AM.DOM.prev(elem[num]).style.opacity = 0.5;
//                            AM.DOM.prev(elem[num]).style.display = "none";
                        }
                    } );

                    // при вставке из памяти элемента управления "подсказка" убирается
                    // но, если поле value элемента управления пустое, то отображается подсказка(полупрозрачная)
                    AM.Event.addEvent( elem[num], "input", function( event ) {
//                        console.log(5);
                        if( elem[num].value == ''  ) {
                            AM.DOM.prev( elem[num]).style.display = "";
                        } else {
                            AM.DOM.prev(elem[num]).style.display = "none";

                        }
                    } );


                    if( elem[num].value != "" ) {
//                        console.log(6);
                        AM.DOM.prev( elem[num] ).style.display = "none";
                    }
                }(i);
            }
        }
    },


    /**
     * Проверяем форму
     * @param form
     * @returns {boolean}
     */
    validateForm: function( form ) {
        // take HTMLCollection - array
        var elem = form.elements,
        // count of elements in HTMLCollection
            count = 0,
        // array for count from 1 to ... (not from 0 to ...)
            index = [],
        // return result
            res = true,
            error = [],
            len,
            i;

        // находим все элементы формы и проверяем, чтобы поле value имело значение (первичная проверка
        // при submit)
        for( i= 0, len = elem.length; i<len; i++ ) {
            // если это не <fieldset>, не имеет аттрибут 'hidden' и имеет название класса
            if( elem[i].nodeName !== 'FIELDSET' && elem[i].type != 'hidden' && elem[i].className ) {
//                console.log(elem[i].value);
                // делаем проверку данного поля
                AMForm.validateField(elem[i]);
                // если отсутствует значение элемента
                if( elem[i].value == '' ) {
                    // и нет элемента 'span', где размещена ошибка
                    if( ! AM.DOM.next( elem[i] ) ) {
                        // добавляем блок с ошибкой
                        AMForm.showErrors( elem[i],"Поле не заполнено" );
                        // если блок с ошибкой уже существуют, удаляем его
                    } else {
                        AMForm.hideErrors( AM.DOM.next( elem[i] ) );
                    }
                    // добавляем в массив ошибок значение
                    error[i] = "error";
                    // если значение элемента присутствует
                } else {
                    // проверяем данное поле, если оно не удовлетворяет условию
                    if( AMForm.validateField( elem[i] ) == false ) {
//                        console.log(elem[i]);
                        // добавляем в массив ошибок значение
                        error[i] = "error";
                    }
                }
            }
        }


        // Проверяем iFrame в гостевой книге
        if( AM.DOM.$('iframe_redactor') != null ) {

                var theIframe = AM.DOM.$('iframe_redactor'),
                    content = wysiwyg.doc().body.innerHTML,
                    editorSpan = AM.DOM.$('editorSpan');
//                content = content.replace(/&nbsp;/g,'');

            if( content.length >= 5 ) {
                var textareaIframe = AM.DOM.$('textareaIframe');
                textareaIframe.value = content;

                if(  AM.DOM.next( editorSpan ) != null ) {
                    AMForm.hideErrors( editorSpan  );
                }
            } else {
                if(  AM.DOM.next( editorSpan ) == null ) {
                    // добавляем блок с ошибкой
                    AMForm.showErrors( editorSpan,"Поле не заполнено" );
                }
                error[i + 1] = "error";
            }
        }




        // если есть хоть одна ошибка
        if( error.length ) {
            if( AM.DOM.$('guestbook-form') != null ) {
                // то фокусируем страницу на форме
//                var guestbookForm = AM.DOM.$('guestbook-form');
//                guestbookForm.scrollIntoView(true);

            }
            AM.DOM.$('shipping-box').scrollIntoView(true);
            // возвращаем результат False
            return false;
            // если ошибок нет
        } else {
            // возвращаем результат True
            return true;
        }


    },



    /**
     * Проверяем отдельный элемент на соответствование условиям
     * @param elem
     * @param load
     * @returns {boolean|*}
     */
    validateField: function(elem, load){

        var errors = [],
            res = true,
            name,
            re,
            tes;
        for( name in this.check) {
            re = new RegExp("(^|\\s+)"+name+"(\\s+|$)");
//            if( this.check[name].test(elem, load ) ) {
//                var sd = this.check[name].test(elem, load );
//                console.log(sd);
//
//            }

            if(re.test(elem.className) && !this.check[name].test(elem, load )) {
//                console.log(name);
                errors.push(this.check[name].msg);
            } else {
                errors[this.check[name].msg] = '';
                AMForm.hideErrors(elem);
            }
        }
        if(errors.length){
            if( AM.DOM.next(elem) ) {
                AMForm.hideErrors( AM.DOM.next(elem) );
            }
            AMForm.showErrors(elem, errors);
            res = false
        } else if(  AM.DOM.next(elem) ) {
            AMForm.hideErrors( AM.DOM.next(elem) );
        }
        return res;
    },




    showErrors: function(elem, errors) {
        var n = AM.DOM.next(elem),
            p = AM.DOM.parent(elem),
            li = AM.DOM.create('li'),
            ul = AM.DOM.create('ul');
        ul.className = "errors";
        li.innerHTML = errors;
        li.style.color = "#ff0000";
        AM.DOM.append(ul,li);
        if( n == null) {
            AM.DOM.append(p,ul);
        }

    },

    hideErrors: function(elem) {
        var n = AM.DOM.next(elem);
        if( n && n.nodeName == 'UL' && n.className == 'errors' ) {
            AM.DOM.remove(n);
        }
    }
};