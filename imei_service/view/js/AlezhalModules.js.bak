/**
 * Created by zhalnin on 05/01/14.
 */

var AM = {

    /**
     * DOM object
     */
    DOM: {
        /**
         * Возвращаем элемент по его id
         * @param $id
         * @returns {HTMLElement}
         */
        $: function( $id ) {
            if( typeof $id == 'string' ) {
                return document.getElementById( $id );
            }
        },
        /**
         * Функция обнаружения элементов по имени тега внутри HTML DOM-документа
         * @param name
         * @param root
         * @returns {NodeList}
         */
        tag: function(name, root) {
            // Если конкретный элемент не предоставлен, вести поиск по всему
            // документу
            return (root || document).getElementsByTagName(name);
        },
        /**
         * Ищем родительский элемент
         * @param elem
         * @param num
         * @returns {*}
         */
        parent: function(elem, num) {
            num = num || 1;
            for(var i = 0; i < num; i++) {
                if(elem != null) {
                    elem = elem.parentNode;
                }
            }
            return elem;
        },
        /**
         * Ищем предыдущий сестринский элемент
         * @param elem
         * @returns {*}
         */
        prev: function(elem) {
            do {
                elem = elem.previousSibling;
            }
            while(elem && elem.nodeType != 1);
            return elem;
        },
        /**
         * Ищем следующий сестринский эелемент
         * @param elem
         * @returns {*}
         */
        next: function(elem) {
            do{
                elem = elem.nextSibling;
            }
            while(elem && elem.nodeType != 1);
            return elem;
        },
        /**
         * Ищем первый дочерний элемент
         * @param elem
         * @returns {*}
         */
        first: function(elem) {
            elem = elem.firstChild;
            return elem && elem.nodeType != 1 ? AM.DOM.next(elem) : elem;
        },
        /**
         * Ищем последний дочерний элемент
         * @param elem
         * @returns {*}
         */
        last: function(elem) {
            elem = elem.lastChild;
            return elem && elem.nodeType != 1 ? AM.DOM.prev(elem) : elem;
        },
        /**
         * Вставка элемента перед другим элементом
         * @param parent
         * @param before
         * @param elem
         */
        before: function (parent, before, elem) {
            // Выяснение, предоставлен ли родительский (parent) узел
            if(elem == null) {
                elem = before;
                before = parent;
                parent = before.parentNode;
            }
            // Получение нового массива элементов
            var elems = AM.DOM.checkElem(elem);
            // Обратный перебор элементов массива,
            // поскольку мы добавляем элементы к началу
            for( var i = elems.length - 1; i >= 0; i-- ) {
                parent.insertBefore(elems[i], before);
            }
        },
        /**
         * Добавление элемента в качестве дочернего к другому элементу
         * @param parent
         * @param elem
         */
        append: function(parent, elem) {
            // Получение массива элементов
            var elems = AM.DOM.checkElem(elem);

            // Добавление всех элементов к родительскому элементу
            for(var i = 0; i <= elems.length-1; i++){
                parent.appendChild(elems[i]);
            }
        },
        /**
         * Вспомогательная для before и append
         * @param a
         * @returns {Array}
         */
        checkElem: function( a ) {
            //console.log(a);
            var r = [];
            // Превращение аргумента в массив, если он еще им не является
            if( a.constructor != Array ) a = [a];
            //console.log(a);
            for(var i = 0; i < a.length; i++) {
                //console.log(a);
                // Если это строка
                if(a[i].constructor == String)  {
                    //console.log("string");
                    // Создание временного элемента для помещения в него HTML
                    var div = document.createElement("div");

                    // Вставка HTML, для превращения его в DOM-структуру
                    div.innerHTML = a[i];

                    (function(){
                        // Обратное извлечение DOM-структуры из временного DIV-элемента
                        for(var j = 0; j < div.childNodes.length; j++) {
                            //console.log(div.childNodes.length);
                            r[r.length] = div.childNodes[j];
                        }
                    })();

                } else if(a[i].length)  {
                    //console.log("not string");
                    // Если это массив DOM-узлов
                    for(var j = 0; j < a[i].length; j++) {
                        r[r.length] = a[i][j];
                    }
                } else {
                    //console.log("DOM-node");
                    // Иначе, предположение, что это DOM-узел
                    r[r.length] = a[i];
                    //console.log(r);
                }
            }
            return r;
        },
        /**
         * Создание нового DOM-элемента
         * @param elem
         * @returns {HTMLElement}
         */
        create: function( elem ) {
            return document.createElementNS ?
                document.createElementNS('http://www.w3.org/1999/xhtml', elem) :
                document.createElement( elem );
        },
        /**
         * Удаление из DOM отдельного узла
         * @param elem
         */
        remove: function( elem ) {
            if(elem) elem.parentNode.removeChild(elem);
        },
        /**
         * Удаление из DOM всех дочерних узлов элемента
         * @param elem
         */
        empty: function(elem) {
            while(elem.firstChild) {
                AM.DOM.remove(elem.firstChild);
            }
        },
        /**
         * Функция для извлечения текстового содержимого элементов
         * @param elem
         * @returns {string}
         */
        text: function (elem) {
            var t = "";
            // Если элемент был передан, получение его дочерних элементов
            // иначе, предположение о том, что передан массив
            elem = elem.childNodes || elem;
            // Просмотр всех дочерних узлов
            for(var j = 0; j < elem.length; j++) {
                // Если это не элемент, присоединить его текстовое значение
                // иначе, провести рекурсивный перебор всех дочерних составляющих
                // элемента
                t += elem[j].nodeType != 1 ? elem[j].nodeValue : AM.DOM.text(elem[j].childNodes);
            }
            return t;
        },
        /**
         * Получаем текст из указанного тега
         * @param element
         */
        getInnerText: function(element) {
            return (typeof element.textContent == "string") ?
                element.textContent : element.innerText;
        },
        /**
         * Вставляем текст в указанный тег
         * @param element
         */
        setInnerText: function(element, text) {
            if(typeof element.textContent == "string") {
                element.textContent = text;
            }
            else {
                element.innerText = text;
            }
        },
        /**
         * Получение свойства style( name ) определенного элемента ( elem )
         * @param elem
         * @param name
         * @returns {*}
         */
        getStyle: function( elem, name) {
            // Если свойство присутствует в style[], значит, оно было
            // недавно установлено ( и является теущим)
            if(elem.style[name]) {
                return elem.style[name];
            }
            // В противном случае, попытка воспользоваться методом IE
            else if(elem.currentStyle)
                return elem.currentStyle[name];

            // Или методом W3C, если он существует
            else if(document.defaultView && document.defaultView.getComputedStyle){
                // Вместо textAlign в нем используется традиционное правило
                // написания стиля - 'text-align'
                name = name.replace(/([A-Z])/g,"-$1");
                name = name.toLowerCase();

                // Получение объекта style и получение значения свойства
                // (если оно существует)
                var s = document.defaultView.getComputedStyle(elem,"");
                return s && s.getPropertyValue(name);

                // В противном случае, мы используем какой-то другой браузер
            }
            else
                return null;
        },
        /**
         * Get the style sheet for the first <link/> element
         * @param element
         * return sheet
         */
        getStyleSheet: function(element) {
            return element.sheet || element.styleSheet;
        },
        /**
         * Add rule for css
         * @param sheet
         * @param selectorText
         * @param cssText
         * @param position
         */
        insertRule: function(sheet, selectorText, cssText, position) {
            if(sheet.insertRule) {
                sheet.insertRule(selectorText + "{" + cssText + "}", position);
            } else if(sheet.addRule) {
                sheet.addRule(selectorText, cssText, position);
            }
        },
        /**
         * Delete rule from style sheet
         * @param sheet
         * @param index
         */
        deleteRule: function(sheet, index) {
            if(sheet.deleteRule) {
                sheet.deleteRule(index);
            } else if(sheet.removeRule) {
                sheet.removeRule(index);
            }
        },
        /**
         * Функция сокрытия элемента (с использование свойства display)
         * @param elem
         */
        hide: function( elem ){
            // Определение текущего состояния свойства display
            var curDisplay = AM.DOM.getStyle( elem, 'display');

            // Запоминание состояния свойства display на будущее
            if( curDisplay != 'none' )
                elem.$oldDisplay = curDisplay;

            // Установка display в none (сокрытие элемента)
            elem.style.display = 'none';
        },
        /**
         * Функция показа элемента (с использование свойства display)
         * @param elem
         */
        show: function( elem ){
            // Возвращение свойства display к тому значеню, которое им
            // использовалось, или использование
            // 'block', если предыдущее состояние этого свойства не было
            // сохранено
            elem.style.display = elem.$oldDisplay || 'block';
        },
        /**
         * Корректировка уровня прозрачности элемента
         * @param elem
         * @param level
         */
        setOpacity: function( elem, level ){
            // Если существуют какие-нибудь фильтры, значит,
            // мы имеем дело с IE, и нужно устанавливать фильта Alpha
            if( elem.filters ){
                //        elem.filters.alpha.opacity = level;
                elem.style.filters = 'alpha(opacity='+ level + ')';

            }
            // В противном случае мы используем W3C-свойство opacity
            else
                elem.style.opacity = level / 100;
        },
        /**
         * Медленное проявление скрытого элемента за счет увеличения
         * в течение секунды его непрозрачности
         * @param elem
         * @param to
         * @param speed
         */
        fadeIn: function( elem, to, speed ){
            // Начало непрозрачности с 0
            AM.DOM.setOpacity(elem, 0);
            // Отображение элемента ( но вы его не увидите, пока непрозрачность
            // равна 0)
            AM.DOM.show( elem );
            // Мы собираем я за секунду показать анимацию, состоящую из
            // 20 "кадров"
            for( var i = 0; i <= 100; i+=5) {
                //        alert("i - "+i);
                // Замкнутое выражение, гарантирующее, что у нас в распоряжении
                // находится именно та переменная 'i', которая нам нужна
                (function() {
                    var opacity = i;
                    //            alert("pos - "+opacity);
                    // Установка времени ожидания для совершения будущих
                    //  действий в определенное время
                    setTimeout(function() {
                        //                alert("second i - "+i);
                        //                alert("second pos - "+opacity);
                        // Установка новой степени прозрачности элемента
                        AM.DOM.setOpacity( elem, (opacity / 100) * to );
                    }, (i + 1) * speed);
                })();
            }
        },
        /**
         * Медленное скрывание открытого элемента за счет увеличения
         * в течение секунды его непрозрачности
         * @param elem
         * @param to
         * @param speed
         */
        fadeOut: function( elem, to, speed ){
//            console.log(speed);
            // Начало непрозрачности с 1
            //    setOpacity(elem, 1);
            // Отображение элемента ( но вы его не увидите, пока непрозрачность
            // равна 0)
            //    hide( elem );

            // Мы собираемся за секунду показать анимацию, состоящую из
            // 20 "кадров"
//            for( var i = 100; i >= 0; i-=5)
            for( var i = 0; i <= 100; i+=5) {
                // Замкнутое выражение, гарантирующее, что у нас в распоряжении
                // находится именно та переменная 'i', которая нам нужна
                (function() {
                    var opacity = i;
//                    console.log("first pos - "+opacity);
                    //            console.log(pos);
                    // Установка времени ожидания для совершения будущих
                    //  действий в определенное время
                    setTimeout(function() {
                        // Установка новой степени прозрачности элемента
//                        console.log("p - "+p);

                        AM.DOM.setOpacity( elem, 100 - opacity );
                        if(opacity == 95)
                            AM.DOM.hide( elem );
                    }, (i + 1) * speed);
                })();
            }
        },

        /**
         *  Возвращаем выделенный тексе
         * @param textbox
         */
        getSelectedText: function(textbox) {
            if(typeof textbox.selectionStart == "number") {
                return textbox.value.substring(textbox.selectionStart, textbox.selectionEnd);
            } else if(document.selection) {
                return document.selection.createRange().text;
            }
        },
        /**
         * Добавляем выделение указанному элементу (elem)
         * в указанных позициях (start, end)
         * @param textbox - обычно textarea
         * @param startIndex - начальная позиция
         * @param stopIndex - конечная позиция
         */
        selectText: function(textbox, startIndex, stopIndex) {
            if(textbox.setSelectionRange) {
                textbox.setSelectionRange(startIndex, stopIndex);
            } else if(textbox.createTextRange) {
                var range = textbox.createTextRange();
                range.collapse(true);
                range.moveStart("character", startIndex);
                range.moveEnd("character", stopIndex - startIndex);
                range.select();
            }
            textbox.focus();
        },
        /**
         * Добавялем к выделенному элементу два тега,
         * один перед выделение и второй после выделения
         * затем вызываем selectTxt() для создания выделения нужному выделению
         * @param obj - обычно textarea
         * @param str1 - начальный тег
         * @param str2 - конечный тег
         */
        tagInsert: function(obj, str1, str2) {
            try {

                var aim,
                    res,
                    start,
                    end,
                    startLen,
                    endLen,
                    allLen,
                    subLen,
                    ar;
//                AM.DOM.consoleLog(obj);
//                AM.DOM.pageLog("start");
                if(document.selection) {

                    // Сохраняем выделенный текст
                    aim = document.selection.createRange();

                    //
                    // Здесь возможно вместо aim.text надо будет использовать просто aim
                    //
                    // Сохраняем текст из позиции 0 до начала выделения
                    start = obj.value.substring(0, aim.text);
                    // Сохраняем текст от конца выделения до конца
                    end = obj.value.substring(aim.text);
                    // Начальная позиция выделенного текста
                    // - это длина текста до начала выделения + длина открывающего тега
                    startLen = start.length + str1.length;
                    // Длина текста от конца выделения до конца строки
                    endLen = end.length;
                    // Длина всего текста
                    allLen = obj.value.length;
                    // Конечная позиция выделенного текста
                    // - это длина текста от конца выделения до конца строки + длина закрывающего тега
                    subLen = allLen - endLen + str2.length - 1;
                    // Добавляем startLen и subLen к массиву
                    ar = [startLen, subLen];


                    // К найденной фразе добавляем спереди и сзади теги
                    aim.text = str1+aim.text+str2;

                    // Вызываем функцию для создания выделения после добавления тегов
                    AM.DOM.selectText(obj,ar[0],ar[1]);

                } else if(document.getSelection) {

                    // Сохраняем текст из позиции 0 до начала выделения
                    start = obj.value.substring(0, obj.selectionStart);
                    // Сохраняем выделенный текст
                    aim = obj.value.substring(obj.selectionStart, obj.selectionEnd);
                    // Сохраняем текст от конца выделения до конца
                    end = obj.value.substring(obj.selectionEnd);

                    // Начальная позиция выделенного текста
                    // - это длина текста до начала выделения + длина открывающего тега
                    startLen = start.length + str1.length;
                    // Длина текста от конца выделения до конца строки
                    endLen = end.length;
                    // Длина всего текста
                    allLen = obj.value.length;
                    // Конечная позиция выделенного текста
                    // - это длина текста от конца выделения до конца строки + длина закрывающего тега
                    subLen = allLen - endLen + str2.length - 1;

                    // Добавляем startLen и subLen к массиву
                    ar = [startLen, subLen];


                    // К найденной фразе добавляем начало строки до выделения спереди, начальный тег
                    // затем само выделение конечный тег и окончание строки после выделения
                    res = start+str1+aim+str2+end;
                    // Возвращаем результат обратно в элемент
                    obj.value = res;

                    // Вызываем функцию для создания выделения после добавления тегов
                    AM.DOM.selectText(obj,ar[0],ar[1]);

                } else {
                    alert('no');
                }
            } catch (ex) {
                console.log(ex);
            }
        },
        /**
         * Медленное появление скрытого элемента за счет увеличения
         * его высоты в течение секунды
         * @param elem
         * @param speed
         */
        slideDown: function( elem, speed ) {
            // Начало выплывания вниз с 0
            //    elem.style.height = 0+'px';

            // Показ элемента ( но вы его не увидите, пока высота равна 0)
            AM.DOM.show( elem );
            if( ! speed ) {
                speed = 0;
            }
            // Определение полной, потенциальной высоты элемента
            var h = AM.Position.fullHeight( elem );
            // Мы собираемся за секунду показать анимацию, состоящую из
            // 20 кадров
            for( var i = 0; i <= 100; i += 5) {
                // Замкнутое выражение, гарантирующее, что у нас в паспоряжении
                // находится именно та переменная 'i', которая нам нужна
                (function()  {
                    var pos = i;
                    // Установка времени ожидания для совершения будущих
                    // действий в определенное время
                    setTimeout(function() {                // Установка новой высоты элемента
                        elem.style.height = ( pos / 100 ) * h + "px";

                    }, ( pos + 1 ) * speed);
                })();
            }
        },
        /**
         * Функция, используемая для переустановки наора CSS-свойств, которые
         * позже можно будет восстановить
         * @param elem
         * @param prop
         * @returns {{}}
         */
        resetCSS: function ( elem, prop ) {
            var old = {};
            // Перебор всех свойств
            for( var i in prop )
            {
                // Запоминание старых значений свойств
                old[ i ] = elem.style[ i ];
                // и установка новых значений
                elem.style[ i ] = prop[i];
            }
            // возвращение набора значений для использования в функции restoreCSS
            return old;
        },
        /**
         * Функция для устранения побочных эффектов функции resetCSS
         * @param elem
         * @param prop
         */
        restoreCSS: function ( elem, prop ) {
            // Переустановка всех свойств и возвращение им первоначальных значений
            for( var i in prop )
                elem.style[ i ] = prop[i];
        },
        /**
         * Установка и получение значений атрибутов элементов
         * @param elem
         * @param name
         * @param value
         * @returns {*}
         */
        attr: function(elem, name, value) {
            // Гарантирование допустимости предоставленного имени
            if(!name || name.constructor != String) return '';

            // Определение, не относится ли это имя к тем самым "роковым"
            // именам
            name = { 'for':'htmlFor', 'class':'className', 'float':'cssFloat', 'text':'cssText'}[name] || name;

            // Если пользователь устанавливает значение, то также
            if(typeof value != 'undefined') {
                // сначала установить быстрый способ
                elem[name] = value;

                // По возмоности воспользоваться setAttribute
                if(elem.setAttribute)
                    elem.setAttribute(name, value);
            }
//            console.log(elem);
//            console.log(name);
            // Вернуть значение атрибута
            return elem[name] || elem.getAttribute(name) || '';
        },
        /**
         * Функции поиска всех элементов с заданным классом
         * @param name
         * @param elem
         * @returns {Array}
         */
        hasClass: function(name, elem) {
            var r = [];
            // Обнаружение имени класса (работает и при наличии
            // нескольких имен классов
            var re = new RegExp("(^|\\s)"+name+"(\\s|$)");
            // Ограничение поиска элементами определенного типа
            // или поиск по всем элементам
            var e = document.getElementsByTagName(elem || "*");
            (function(){
                for(var j = 0; j < e.length; j++) {
                    // Если элемент имеет нужный класс, добавление его в
                    // возвращаемый массив
                    //        if((e[j]).className.match(re)) {
                    //            r.push(e[j]);
                    //        }
                    if(re.test(e[j].className)) {
                        r.push(e[j]);
                    }
                }
            })();
            return r;
        },
        /**
         * Функция добавления класса элементу
         * @param name
         * @param elem
         */
        addClass: function(name, elem) {
            var r = [];
            // Если данный элемент не содержит добавляемый класс
            if (AM.DOM.hasClass(name, elem) == '') {
                // Проверяем, это объект или коллекция
                var e = typeof elem == 'object' ? elem : document.getElementsByTagName(elem || "*");
                // Если элемент уже имеет классы, то добавляем наш к нему
                // Если это HTMLCollection (тег)
                if(e.constructor == HTMLCollection ) {
                    (function(){
                        for(var i= 0, len= e.length; i < len; i++ ){
                            // Сохраняем существующий класс
                            var tmp = e[i].className;
                            // Добавляем к существующему классу наш
                            e[i].className = tmp + " " + name;
                        }
                    })();
                } else {
                    // Если это объект (не тег, а уже найденный элемент),
                    // Сохраняем существующий класс
                    var tmp = e.className;
                    // Добавляем к существующему классу наш
                    elem.className = tmp + " "+name;
                }
            }
        },
        /**
         * Функция замены класса у элемента
         * @param name
         * @param elem
         */
        toggleClass: function(name, elem) {
            if (AM.DOM.hasClass(name, elem) == '') {
                // Ограничение поиска элементами определенного типа
                // или поиск по всем элементам
                var e = typeof elem == 'object' ? elem : document.getElementsByTagName(elem || "*");
                if(e.constructor == HTMLCollection) {
                    for(var j = 0; j < e.length; j++) {
                        e[j].className = name;
                    }
                } else {
                    e.className = name;
                }
            }
        },
        /**
         * Функция удаления класса из элемента
         * @param name
         * @param elem
         */
        removeClass: function (name, elem) {
            // Ограничение поиска элементами определенного типа
            // или поиск по всем элементам
            var e = typeof elem == 'object' ? elem : document.getElementsByTagName(elem || "*");
            // Обнаружение имени класса (работает и при наличии
            // нескольких имен классов
            var re = new RegExp("(^|\\s)"+name+"(\\s|$)");
//            console.log(re);
            if(e.constructor == HTMLCollection) {
                for(var j = 0; j < e.length; j++) {
                    if(AM.DOM.hasClass(name, e[j])){
                        e[j].className = e[j].className.replace(re,'');
                    }
                }
            } else {
                e.className = e.className.replace(re,' ');
            }
        },
        /**
         * Определение наличия у элемента атрибута
         * @param elem
         * @param name
         */
        hasAttributes: function(name, elem) {
//            console.log(elem);
            return elem.getAttribute(name) != null;
        },
        /**
         * Готовность DOM
         * Функция, предназначенная для отслеживания готовности DOM
         * @param f
         */
        domReady: function(f) {
//            console.log(f);
            //console.log("1");
            // Если DOM уже загружен, немедленно выполнить функцию
            if(AM.DOM.domReady.done) {
//                console.log("2");
                return f();
            }

            // Если мы уже дополнили функцию
            if(AM.DOM.domReady.timer)  {
                //console.log("3");
                // внести ее в список исполняемых
                AM.DOM.domReady.ready.push(f);
                //console.log("4");
            } else {
//                console.log("5");
                // Привязывание события завершения загрузки страницы,
                // на тот случай если ее загрузка закончится первой.
                // Здесь используется addEvent.
                AM.Event.addEvent(window, "load", AM.DOM.isDOMReady);
//                console.log("6");
                // Инициализация массива иполняемых функций
                AM.DOM.domReady.ready = [ f ];
                //console.log("7");
                // Проверка DOM на готовность, проводимая как можно быстрее
                AM.DOM.domReady.timer = setInterval(AM.DOM.isDOMReady, 13);
                //console.log("8");
            }
        },
        /**
         * Проверка на готовность DOM к перемещению по ее структуре
         * @returns {boolean}
         */
        isDOMReady: function() {
//            console.log(AM.DOM);
//            console.log("9");
            // Если мы уже определили готовность страницы - проигнорировать
            // дальнейшее выполнение
            if(AM.DOM.domReady.done) {
                //console.log("10");
                return false;
            }
            // Проверка доступности некоторых функций и элеметов
            if(document && document.getElementsByTagName &&
                document.getElementById && document.body) {
                //console.log("11");
                // Если они готовы, можно прекратить проверку
                clearInterval(AM.DOM.domReady.timer);
                AM.DOM.domReady.timer = null;
                //console.log("12");

                // Выполнение всех ожидавших функций
                for(var i = 0; i < AM.DOM.domReady.ready.length; i++)
                {
//                    console.log(AM.DOM.domReady.ready.length);
//                    console.log(AM.DOM.domReady.ready[i]);
//                    console.log('13');
                    AM.DOM.domReady.ready[i]();
                }

                //console.log("14");
                // Сохранение того, что только что было сделано
                AM.DOM.domReady.ready = null;
                AM.DOM.domReady.done = true;
                //console.log("15");
            }
        },
        /**
         * Проверяем на существование функции(метода) у заданного объекта
         * test for the existence of a function on any object in a browser environment
         * credit: Peter Michaux
         * @param object
         * @param property
         * @returns {boolean}
         */
        isHostMethod: function(object, property) {
            var t = typeof object[property];
            return t=="function" ||
                (!!(t=="object" && object[property])) || t=="unknown";
        },
        /**
         * Динамическая загрузка скрипта javascript
         * с сылкой на скрипт
         * @param url
         */
        loadScript: function(url){
            var script = document.createElement("script");
            script.type = "text/javascript";
            script.src = url;
            document.head.appendChild(script);
        },
        /**
         * Динамическая загрузка скрипта javascript
         * с пользовательской функцией
         * @param code
         */
        loadScriptString: function(code) {
            console.log(code);
            var script = document.createElement("script");
            script.type = "text/javascript";
            try {
                script.appendChild(document.createTextNode(code));
            } catch(ex) {
                script.text = code;
            }
            document.body.appendChild(script);
        },
        /**
         * Динамическая загрузка стилевой таблицы
         * с сылкой на стиль
         * @param url
         */
        loadStyles: function(url) {
            var link = document.createElement("link");
            link.rel = "stylesheet";
            link.type = "text/css";
            link.href = url;
            var head = document.getElementsByTagName("head")[0];
            head.appendChild(link);
        },
        /**
         * Динамеческая загрузка стилевой таблицы
         * с пользовательским стилем
         * @param css
         */
        loadStyleString: function(css) {
            var style = document.createElement("style");
            style.type = "text/css";
            try {
                style.appendChild(document.createTextNode(css));
            } catch(ex) {
                style.stylesheet.cssText = css;
            }
            var head = document.getElementsByTagName("head")[0];
            head.appendChild(style);
        },
        /**
         * MatchesSelector - проверяем, имеет ли указанный элемент
         * тот или иной селектор (тег)
         * @param element
         * @param selector
         */
        matchesSelector: function(element, selector) {
            if(element.matchesSelector) {
                return element.matchesSelector(selector);
            } else if (element.msMatchesSelector) {
                return element.msMatchesSelector(selector);
            } else if (element.mozMatchesSelector) {
                return element.mozMatchesSelector(selector);
            } else if (element.webkitMatchesSelector) {
                return element.webkitMatchesSelector(selector);
            } else {
                throw new Error("Not supported.");
            }
        },

        /**
         * Contains
         * @param refNode
         * @param otherNode
         */
        contains: function(refNode, otherNode) {
            if(typeof refNode.contains == "function" &&
                (!client.engine.webkit || client.engine.webkit >= 522)) {
                return refNode.contains(otherNode);
            } else if(typeof refNode.compareDocumentPosition == "function") {
                return !!(refNode.compareDocumentPosition(otherNode) & 16);
            } else {
                var node = otherNode.parentNode;
                do {
                    if(node === refNode) {
                        return true;
                    } else {
                        node = node.parentNode;
                    }
                }
                while(node !== null);
                return false;
            }
        },
        /**
         * Console logging
         * @param message
         */
        consoleLog: function(message) {
            if(typeof console == "object") {
                console.log(message);
            } else if(typeof opera == "object") {
                opera.postError(message);
            } else if(typeof java == "object" && typeof java.lang == "object") {
                java.lang.System.out.println(message);
            }
        },
        /**
         * Page logging
         * @param message
         */
        pageLog: function(message){
            var console = document.getElementById("droptarget");
            if(console === null) {
                console = document.createElement("div");
                console.id = "debuginfo";
                console.style.background = "#dedede";
                console.style.border = "1px solid silver";
                console.style.padding = "5px";
                console.style.width = "400px";
                console.style.position = "absolute";
                console.style.right = "0px";
                console.style.top = "0px";
                document.body.appendChild(console);
            }
            console.innerHTML += "<p>"+ message +"</p>";
        }


    },


    /**
     * Для определения различных позиций
     */
    Position: {
        /**
         * Для определения левой позиции окна браузера в окне монитора
         * screenLeft/screenTop - IE,Safari,Opera,Chrome
         * screenX/screenY - Firefox //
         * @returns {Number}
         */
        leftPosition: function() {
            return typeof window.screenLeft == "number" ? window.screenLeft : window.screenX;
        },
        /**
         * Для определения верхней позиции окна браузера в окне монитора
         * screenLeft/screenTop - IE,Safari,Opera,Chrome
         * screenX/screenY - Firefox //
         * @returns {Number}
         */
        topPosition: function() {
            return typeof window.screenTop == "number" ? window.screenTop : window.screenY;
        },
        /**
         * Define left offset
         * @param element
         */
        getElementLeft: function(element) {
            var actualLeft = element.offsetLeft,
                current = element.offsetParent;

            while(current !== null) {
                actualLeft += current.offsetLeft;
                current = current.offsetParent;
            }
            return actualLeft;
        },
        /**
         * Define top offset
         * @param element
         */
        getElementTop: function(element) {
            var actualTop = element.offsetTop,
                current = element.offsetParent;

            while(current !== null) {
                actualTop += current.offsetTop;
                current = current.offsetParent;
            }
            return actualTop;
        },
        /**
         * Dimension of viewport
         * size of window(<html> or <body> elements)
         */
        getViewport: function() {
            if(document.compatMode == "BackCompat") {
                return {
                    width: document.body.clientWidth,
                    height: document.body.clientHeight
                };
            } else {
                return {
                    width: document.documentElement.clientWidth,
                    height: document.documentElement.clientHeight
                };
            }
        },
        /**
         * Determine element dimensions
         * @param element
         */
        getBoundingClientRect: function(element) {
            var scrollTop = document.documentElement.scrollTop;
            var scrollLeft = document.documentElement.scrollLeft;
            if(element.getBoundingClientRect)  {
                if(typeof arguments.callee.offset != "number") {
                    var scrollTop = document.documentElement.scrollTop;
                    var temp = document.createElement("div");
                    temp.style.cssText = "position:absolute;left:0;top:0;";
                    document.body.appendChild(temp);
                    arguments.callee.offset = -temp.getBoundingClientRect().top - scrollTop;
                    document.body.removeChild(temp);
                    temp = null;
                }
                var rect = element.getBoundingClientRect();
                var offset = arguments.callee.offset;

                return {
                    left: rect.left + offset,
                    right: rect.right + offset,
                    top: rect.top + offset,
                    bottom: rect.bottom + offset
                }

            } else {
                var actualLeft = AM.Position.getElementLeft(element);
                var actualTop = AM.Position.getElementTop(element);

                return {
                    left: actualLeft - scrollLeft,
                    right: actualLeft + element.offsetWidth - scrollLeft,
                    top: actualTop - scrollTop,
                    bottom: actualTop + element.offsetHeight - scrollTop
                }
            }
        },
        /**
         * Определение высоты области просмотра
         * панель от firebug тоже учитывается
         * @returns {*|HTMLElement|number}
         */
        windowHeight: function() {
            // Сокращение на случай использования IE6 в строгом
            // (strict) режиме
            var de = document.documentElement;

            // Использование свойства браузера innerHeight, если оно доступно
            return self.innerHeight ||
                // в противном случае попытка получить высоту из корневого узла
                ( de && de.clientHeight ) ||
                // И наконец, попытка получить высоту из элемента body
                document.body.clientHeight;
        },
        /**
         * Определение ширины области просмотра
         * @returns {*|HTMLElement|number}
         */
        windowWidth: function() {
            // Сокращение на случай испльзования IE6 в строгом
            // (strict) режиме
            var de = document.documentElement;

            // Использование свойства браузера innerWidth, если оно доступно
            return self.innerWidth ||
                // в противном случае попытка получить ширину из корневого узла
                (de && de.clientWidth) ||
                // И наконец, попытка получить высоту из элемента body
                document.body.clientWidth;
        },
        /**
         * Функция для определения величины горизонтальной прокрутки браузера
         * @returns {*|HTMLElement|number}
         */
        scrollX: function() {
            // Сокращение на случай использования IE6 в строгом
            // (strict)режиме
            var de = document.documentElement;

            // Использование свойства браузера pageXOffset, если оно доступно
            return self.pageXOffset ||
                // в противном случае попытка получить прокрутку слева из
                // корневого узла
                (de && de.scrollLeft) ||
                // и наконец, попытка получить прокурутку слева из элемента body
                document.body.scrollLeft;
        },
        /**
         * Определение величины вертикальной прокрутки браузера
         * @returns {*|HTMLElement|number}
         */
        scrollY: function(){
            // Сокращение на случай использования IE6 в строгом
            // (strict) режиме
            var de = document.documentElement;

            // Использование свойства браузера pageYOffset, если оно доступно
            return self.pageYOffset ||
                // в противном случае попытка получить прокрутку сверху из
                // корневого узла
                ( de && de.scrollTop ) ||
                // и наконец, попытка получить прокрутку сверху из элемента body
                document.body.scrollTop;
        },
        /**
         * Определение высоты текущей веб-страницы
         * может изменяться при добавлении к странице нового содержимого
         * @returns {number}
         */
        pageHeight: function() {
            return document.body.scrollHeight;
        },
        /**
         * Возвращение ширины веб-страницы
         * @returns {number}
         */
        pageWidth: function() {
            return  document.body.scrollWidth;
        },
        /**
         * Получение X-позиции указателя мыши относительно целевого элемента,
         * который используется в объекте события 'е'
         * Т.е. указывает координаты X элемента, на страничке
         * @param e
         * @returns {*|Number|event.layerX|layerX}
         */
        getElementX: function(e) {
            // Определение соответствующего смещения элемента
            return ( e && e.layerX )|| window.event.offsetX;
        },
        /**
         * Получение Y-позиции указателя мыши относительно целевого элемента,
         * который используется в объекте события 'е'
         * Т.е. указывает координаты Y элемента, на страничке
         * @param e
         * @returns {*|Number|event.layerY|layerY}
         */
        getElementY: function (e) {
            // Определение соответствующего смещения элемента
            return ( e && e.layerY )|| window.event.offsetY;
        },
        /**
         * Получение горизонтальной позиции указателя мыши относительно всего
         * пространства страницы
         * @param e
         * @returns {Number|*}
         */
        getX: function(e) {
            // Нормализация объекта события
            e = e || window.event;

            // Сначала получение позиции из браузеров, не относящихся к IE,
            // а затем из IE
            return e.pageX || e.clientX + document.body.scrollLeft;
        },
        /**
         * Получение горизонтальной позиции указателя мыши относительно всего
         * пространства страницы
         * @param e
         * @returns {Number|*}
         */
        getY: function(e) {
            // Нормализация объекта
            e = e || window.event;

            // Сначала получение позиции из браузеров, не относящихся к IE,
            // затем из IE
            return e.pageY || e.clientY + document.body.scrollTop;
        },
        /**
         * Установка горизонтальной позиции элемента
         * @param elem
         * @param pos
         */
        setX: function( elem, pos ) {
            // Установка CSS-свойства 'left' с использование единицы измерения,
            // выраженной в пикселах
            elem.style.left = pos + "px";
        },
        /**
         * Установка вертикальной позиции элемента
         * @param elem
         * @param pos
         */
        setY: function( elem, pos ) {
            // Установка CSS-свойства 'top' с использованием единицы измерения,
            // выраженной в пикселах
            elem.style.top = pos + "px";
        },
        /**
         * Получение текущей высоты элемента(с использованием вычисляемого CSS
         * @param elem
         * @returns {Number}
         */
        getHeight: function( elem ){
            // Получение вычисялемого значения CSS и извлечения необходимого
            // числового значения
            return parseInt( AM.DOM.getStyle( elem, 'height' ));
        },
        /**
         * Получение текущей ширины элемента ( с использованием вычисляемого CSS)
         * @param elem
         * @returns {Number}
         */
        getWidth: function( elem ) {
            // Получение вычисляемого значения CSS и извлечение необходимого
            // числового значения
            return parseInt( AM.DOM.getStyle( elem, 'width' ));
        },
        /**
         * Получение полной возможной высоты элемента ( в отличие от фактической
         * текущей высоты )
         * @param elem
         * @returns {number|*}
         */
        fullHeight: function ( elem ) {
            // Если элемент отображен на экране, то сработает свойство
            // offsetHeight, а если оно не сработает, то сработает getHeight()
            if( AM.DOM.getStyle( elem, 'display') != 'none')
                return elem.offsetHeight || AM.Position.getHeight( elem );

            // В противном случае нам придется иметь дело с элементом,
            // у которого display имеет значение none, поэтому
            // нужно переустановить его CSS-свойства, чтобы считать более
            // точный результат
//            var old = AM.DOM.resetCSS( elem,
//                {
//                    display: '',
//                    visibility: 'hidden',
//                    position: 'absolute'
//                });

            var old = AM.DOM.resetCSS( elem,
                {
                    display: 'block',
                    visibility: 'visible',
                    position: 'absolute'
                });

            // Определяем полную высоту элемента, используя clientHeight,
            // а если это свойство не работает, используем getHeight
            var h = elem.clientHeight || AM.Position.getHeight( elem );

            // В завершение восстанавливаем прежние CSS-свойства
            AM.DOM.restoreCSS( elem, old );

            // и возвращаем полную высоту элемента
            return h;
        },
        /**
         * Получение полной возможной ширины элемента ( в отличие от фактической
         * текущей ширины )
         * @param elem
         * @returns {number|*}
         */
        fullWidth: function( elem ) {
            // Если элемент отображен на экране, то сработает свойство
            // offsetWidth а если оно не сработает, то сработает getWidth()
            if( AM.DOM.getStyle( elem, 'display') != 'none' )
                return elem.offsetWidth || AM.Position.getWidth( elem );
            // В противном случае нам предется иметь дело с элементом,
            // у которого display имеет значение none, поэтому
            // нужно переустановить его CSS-свойства, чтобы считать более
            // точный результат
//            var old = AM.DOM.resetCSS( elem,
//                {
//                    display: '',
//                    visibility: 'hidden',
//                    position: 'absolute'
//                });
            var old = AM.DOM.resetCSS( elem,
                {
                    display: 'block',
                    visibility: 'visible',
                    position: 'absolute'
                });
            // Определяем полную ширину элемента, используя clientWidth,
            // а если это свойство не работает, используем getWidth
            var w = elem.clientWidth || AM.Position.getWidth( elem );
            // В завершение восстанавливаем прежние CSS-свойства
            AM.DOM.restoreCSS( elem, old );
            // и возвращаем полную ширину элемента
            return w;
        },
        /**
         * Определение CSS-позиционирования элемента
         * Определение левой позиции элемента
         * @param elem
         * @returns {Number}
         */
        posX: function( elem ) {
            // Получение вычисляемого значения style и извлечение числа из значения
            return parseInt( AM.DOM.getStyle( elem, "left" ));
        },
        /**
         * Определение CSS-позиционирования элемента
         * Определение верхней позиции элемента
         * @param elem
         * @returns {Number}
         */
        posY: function( elem ) {
            // Получение вычисляемого значения style и извлечение числа из значения
            return parseInt( AM.DOM.getStyle( elem, "top" ));
        },
        /**
         * Установка позиции элемента относительно его текущей позиции
         * Функция добавления пикселов к горизонтальной позиции элемента
         * @param elem
         * @param pos
         */
        addX: function( elem, pos ) {
            // Получение текущей горизонтальной позиции и добавление к ней
            // смещения
            AM.Position.setX( elem, AM.Position.posX( elem ) + pos);
        },
        /**
         * Установка позиции элемента относительно его текущей позиции
         * Функция добавления писелов к вертикальной позиции элемента
         * @param elem
         * @param pos
         */
        addY: function( elem, pos ) {
            // Получение текущей вертикальной позиции и добавление к ней
            // мещения
            AM.Position.setY( elem, AM.Position.posY( elem ) + pos);
        },
        /**
         * Определение горизонтальной позиции элемента внутри его родителя
         * @param elem
         * @returns {Number|number}
         */
        parentX: function( elem ) {
            // Если offsetParent указывает на родителя элемента, то ранее
            // завершение работы
            return elem.parentNode == elem.offsetParent ?
                elem.offsetLeft:
                // В противном случае нужно найти позицию относительно всей страницы
                // для обоих элементов и вычислить разницу
                AM.Position.pageX( elem ) - AM.Position.pageX( elem.parentNode );
        },
        /**
         * Определение вертикальной позиции элемента внутри его родителя
         * @param elem
         * @returns {Number|number}
         */
        parentY: function( elem ) {
            // Если offsetParent указывает на родителя элемента, то ранее
            // завершение работы
            return elem.parentNode == elem.offsetParent ?
                elem.offsetTop :
                // В противном случае нужно найти позицию относительно всей страницы
                // для обоих элементов и вычислить разницу
                AM.Position.pageY( elem ) - AM.Position.pageY( elem.parentNode);

        },
        /**
         * Определение местоположения элемента (x и y)
         * Определение X (горизонтальной слева) позиции элемента
         * @param elem
         * @returns {*}
         */
        pageX: function( elem ) {
            // Проверка на достижение корневого элемента
            return elem.offsetParent ?
                // Если не дошли до самого верха, добавление текущего смещения и
                // продолжение движения вверх
                elem.offsetLeft + AM.Position.pageX( elem.offsetParent ) :
                // В противном случае, получение текущего смещения
                elem.offsetLeft;
        },
        /**
         * Определение местоположения элемента (x и y)
         * Определение Y (вертикальной сверху) позиции элемента
         * @param elem
         * @returns {*}
         */
        pageY: function( elem ) {
            // Проверка на достижение корневого элемента
            return elem.offsetParent ?
                // Если не дошли до самого верха, добавление текущего смещения и
                // продолжение движения вверх
                elem.offsetTop + AM.Position.pageY( elem.offsetParent ) :
                // В противном случае, получение текущего смещения
                elem.offsetTop;
        }






    },



    /**
     * Ajax object
     */
    Ajax: {

        // Извлечение правильных данных из ответа HTTP
        httpData: function(response, dataType) {
//            console.log('httpData:');
            // Получение заголовка content-type
            var ct = response.getResponseHeader("Content-Type");
            // Если не предоставлен тип по умолчанию, определение
            // не возвращена ли с сервера какая-либо форма XML
            var data = !dataType && ct && ct.indexOf("xml") >= 0;
            // Получение объекта XML-документа, если сервер вернул XML,
            // если нет - возвращение полученного с сервера текстового
            // содержимого
            data = dataType == "xml" || data ? response.responseXML : response.responseText;
            // Если указан тип "script", выполнение возвращенного текста,
            // реагируя на него, как на JavaScript
            if(dataType == "script")
                eval.call(window, data);
//            console.log('httpData: --- '.data);
            // Возвращение данных, полученных в ответе
            return data;
        },

        // Определение успешности получения ответа HTTP
        httpSuccess: function(response) {
            try {
//                console.log('httpSuccess:');
                // Если состояние сервера предоставлено не было, и мы
                // фактически сделали запрос к локальному файлу,
                // значит, он прошел успешно
                return !response.status && location.protocol == "file:" ||
                    // нас устраивает любой код состояния в диапазоне 200
                    (response.status >= 200 && response.status < 300) ||
                    // запрос прошел успешно, если документ не подвергся
                    // изменениям
                    response.status == 304 ||
                    // если файл не подвергался изменениям, Safari возвращает
                    // пустое состояние
                    navigator.userAgent.indexOf("Safari") >= 0
                        && typeof response.status == "undefined";
            }
            catch(e){}
            // Если проверка состояния не удалась, следует предположить,
            // что запрос тоже закончился неудачей
            return false;
        },

        ajax: function( options ){
//            console.log('ajax:');
            // Загрузка объекта параметров по умолчанию, если пользователь не
            // представил никаких значений
            options = {
                // Метод http-запроса
                method: options.mode || "POST",
                // URL на который должен быть послан запрос
                url: options.url || "",
                // Время ожидания ответа на запрос
                timeout: options.timeout || 50000,
                // Функция, запускаемая перед отправкой - типа прогресс
                onStart: options.onStart || function(){},
                // Функция, запускаемая после получения данных - типа прогресс
                onEnd: options.onEnd || function(){},
                // Функция, вызываемая, когда запрос неудачен, успешен
                // или завершен (успешно или нет)
                onComplete: options.onComplete || function(){},
                onError: options.onError || function(){},
                onSuccess: options.onSuccess || function(){},
                // Тип данных, которые будут возвращены с сервера
                // по умолчанию просто определить, какие данные были
                // возвращены, и действовать соответственно
                dataType: options.dataType || "",
                getParams: options.getParams || "",
                postParams: options.postParams || ""
            };
            // Создание объекта запроса
            var xhr = new XMLHttpRequest();
            options.onStart();
            // Если метод GET и параметры не пусты
            if( options.method == 'GET' && options.getParams != "" ) {
//                console.log('options.method==GET');
                // Открытие асинхронного запроса GET и добавляем параметры в строку запроса
                xhr.open(options.method, options.url+"?"+options.getParams, true);
            }
            // Если метод POST и параметры не пусты
            if( options.method == 'POST' && options.postParams != "" ) {
//                console.log('options.method==POST');
//                console.log('method-'+options.method+'url-'+options.url);
                // Открытие асинхронного запроса POST
                xhr.open(options.method, options.url, true);
            }
            // Ожидание отклика на запрос в течение 5 секунд
            // перед тем, как от него отказаться
            var timeoutLength = options.timeout;
            // Отслеживание факта успешного завершения запроса
            var requestDone = false;
            // Инициализация функции обратного вызова, которая будет запущена через
            // 5 секунд, отменяя запрос (если он не будет к тому времени выполнен)
            setTimeout(function()
            {
                requestDone = true;
            }, timeoutLength);

            var that = this;
            // Отслеживание обновления состояния документа
            xhr.onreadystatechange = function() {
//                console.log('onreadystatechange');
                // Ожидание, полной загрузки данных
                // и проверки, не истекло ли время запроса
                if(xhr.readyState == 4 && !requestDone) {
//                    console.log('xhr.readyState==4');
                    // Проверка успешности запроса
                    if(that.httpSuccess(xhr)) {
//                        console.log('httpSuccess()');
//                        console.log(that.httpData(xhr, options.dataType));
                        // Выполнение в случае успеха функции обратного вызова
                        // с данными, возвращенными с сервера
                        options.onSuccess(that.httpData(xhr, options.dataType));
                        options.onEnd();
                    }
                    else {
//                        console.log("error - onreadystatechange");
                        // В противном случае призошла ошибка, поэтому нужно
                        // выполнить функцию обратного вызова для обработки ошибки
                        options.onError();
                    }
                    // Выполнение функции обратного вызова, связанной с завершением
                    // запроса
                    options.onComplete();
                    // Подчистка соединения с сервером
                    xhr = null;
                }
            };
            // Если метод GET и параметры не пусты
            if( options.method == 'GET' && options.getParams != "" ) {
//                console.log('xhr.send()');
                // Установка соединения с сервером
                xhr.send();
            }
            // Если метод POST и параметры не пусты
            if( options.method == 'POST' && options.postParams != "" ) {
//                console.log('xhr.send()');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                // Установка соединения с сервером
                xhr.send(options.postParams);
            }
        },

        // Сериализация набора данных AJAX
        // Может воспринимать два различных пипа объектов:
        // - массив элементов ввода
        // - хэш, составленный из пар ключ-значение
        // Функция возвращает последовательную строку данных
        serialize: function(a) {
            // Набор результатов сериализации
            var s = [];
            // Если передан массив, предположение, что он является массивом
            // элементов формы
            if(a.constructor == Array)
            {
                // Сериализация элементов формы
                for(var i = 0; i < a.length; i++)
                    s.push(a[i].name + "=" + encodeURIComponent(a[i].value));
                // Если нет, предположение, что это объект, состоящий
                // из пар ключ-значение
            }
            else
            {
                // Сериализация пар ключ-значение
                for(var j in a)
                    s.push(j+ "=" + encodeURIComponent(a[j]));
            }
            // возврат результатов сериализации
            return s.join("&");
        }
    },


    /**
     * Event object
     */
    Event: {
        /**
         * Обработчик для mouseover
         * @param event
         */
        mouseoverHandler: function(event) {
            event = event || window.event;
            relatedTarget = event.relatedTarget || event.fromElement;
            // для mouseover
            // relatedTarget - элемент, с которого пришел курсор мыши
//            if (!event.relatedTarget && event.fromElement) {
//                event.relatedTarget = (event.fromElement==event.target) ? event.toElement : event.fromElement
//            }
            return relatedTarget;
        },

        /**
         *  Обработчик для mouseout
         * @param event
         */
        mouseoutHandler: function(event) {
            event = event || window.event;
            relatedTarget = event.relatedTarget || event.toElement;
            // для mouseout
            // relatedTarget - элемент, на который перешел курсор мыши
            return relatedTarget;
        },

        /**
         * Событие event кроссбраузерное
         * @param event
         * @returns event
         */
        getEvent: function(event){
            return event ? event : window.event;
        },

        /**
         * Кроссбраузерное определение target событияa
         * @param event
         * @returns target
         */
        getTarget: function(event){
            return event.target ? event.target : event.srcElement;
        },

        /**
         * Библиотека addEvent/removeEvent
         *
         * @param element
         * @param type
         * @param handler
         */
        // addEvent/removeEvent written by Dean Edwards, 2005
        // with input from Tino Zijdel
        // http://dian.edwards.name/weblog/2005/10/add-event/
        // Добавляем событие ( элемент, тип события, обработчик )
        addEvent: function(element, type, handler) {

            //console.log(element, type, handler);
//            console.log("16");
//            console.log(AM.Event);
            // присвоение каждому обработчику события уникального ID
            if(!handler.$$guid) {

                handler.$$guid = AM.Event.guid++;
            }
            //console.log("18");
            // создание хэш-таблицы видов событий для элемента
            if(!element.events) {
                // создаем объект 'events'
                element.events = {};
                //console.log('19');
            }
            // создание хэш-таблицы обработчиков событий для каждой пары
            // элемент-событие
            var handlers = element.events[type];
            //console.log("20");
            // если у элемента не было сохранено данного типа события, то
            if(!handlers) {
                // то к объекту 'events' добавляем свойство '[type]' - тип события
                handlers = element.events[type] = {};
                //console.log('21');
                // сохранение существующего обработчика события
                // (если он существует)
                if(element["on" + type]) {
                    // к обработчику(объект) добавляем тип события
                    handlers[0] = element["on" + type];
                    //console.log("22");
                }
            }

            // сохранение обработчика события в хэш-таблице
            // handlers[1] = window.events['load'][0] = window['load']
            handlers[handler.$$guid] = handler;
            //console.log("23");
            // назначение глобального обработчика события для выполнения
            // всей работы
            element["on" + type] = AM.Event.handleEvent;
            //console.log("24");
        },

        guid: 1,

        removeEvent: function(element, type, handler) {
            //console.log("25");
            // удаление обработчика события из хэш-таблицы
            if(element.events && element.events[type])
            {
                //console.log('26');
                delete element.events[type][handler.$$guid];
            }
        },

        // обработчик события (load)
        handleEvent: function( event ) {
            //console.log('27');
            var returnValue = true;
            // захват объекта события (IE использует глобальный объект события)
            event = event || AM.Event.fixEvent(window.event);
            //console.log("28");
            // получение ссылки на хэш-таблицу обработчиков событий
            var handlers = this.events[event.type];
            //console.log("29");
            // выполнение каждого обработчика события
            for(var i in handlers) {
                //console.log("30");
                AM.Event.$$handleEvent = handlers[i];
                if(AM.Event.$$handleEvent(event) === false) {
                    //console.log("31");
                    returnValue = false;
                }
            }
            //console.log('32');
            return returnValue;
        },


        fixEvent: function(event) {
            //console.log('33');
            // добавление стандартных методов событий W3C
            event.preventDefault = AM.Event.preventDefault;
            event.stopPropagation = AM.Event.stopPropagation;
            return event;
        },

        preventDefault: function() {
            AM.Event.returnValue = false;
        },

        stopPropagation: function() {
            AM.Event.cancelBubble = true;
        },
        /**
         * Предотвращение исходных действий браузера
         * @param event
         * @returns {boolean}
         */
        stopDefault: function(event) {
            event = AM.Event.getEvent(event);
            // Предотвращение исходных действий браузера (W3C)
            if(event && event.preventDefault) {
                event.preventDefault();
            } else {
                // Ссылка на остановку действия браузера в IE
                window.event.returnValue = false;
            }
            return false;
        },
        /**
         * Остановка всплытия события
         * @param event
         */
        stopBubble: function(event) {
            // Если предоставлен объект события, значит это не IE-браузер
            if(event && event.stopPropagation) {
                // и он поддерживает W3C-метод stopPropagation()
                event.stopPropagation();
            } else {
                // В противном случае нужно воспользоваться способом
                // прекращения всплытия события, существующим в IE
                window.event.cancelBubble = true;
            }
        }
    },

    Query: {
        /**
         * Parse query string
         * return object with entire for each argument
         * @param url (string)
         * @returns {{}}
         */
        getQueryStringArgs: function( url ){
            var qs = "";
            // Проверяем, передана строка для парсинга или нет
            if( url == null ) {
                // Получаем строку запроса без ?
                qs = (location.search.length > 0 ? location.search.substring(1) : "");
            } else {
                qs = ( url.indexOf("?") ? url.substring(url.indexOf("?")+1) : "" );
            }
            // Объект для хранения данных
            var args = {},
            // Получаем отдельные части
                items = qs.length ? qs.split("&"): [],
                item = null,
                name = null,
                value = null,
            // Используем для цикла
                i = 0,
                len = items.length;
            // Сохраняем каждую часть в объект args, как args[name][value]
            for(i=0;i<len;i++){
                item = items[i].split("=");
                name = decodeURIComponent(item[0]);
                value = decodeURIComponent(item[1]);

                if(name.length){
                    args[name] = value;
                }
            }
            return args;
        },

        /**
         * Возвращает полную строку запроса
         * @param url
         * @returns {string}
         */
        getQueryString: function(url){
            if(typeof url == "string"){
                var pos = url.indexOf("?");
                if(pos != -1){
                    return decodeURIComponent(url.substring(pos+1));
                }
            }
            return "";
        },

        /**
         * Собирает строку запроса
         * @param url
         * @param name
         * @param value
         * @returns {*}
         */
        addQueryStringArg: function(url,name,value){
            if(url.indexOf("?") == -1){
                url += "?";
            } else {
                url += "&";
            }
            url += encodeURIComponent(name)+ "=" + encodeURIComponent(value);
            return url;
        }
    },

    XML: {

        /**
         * XML Parser
         * @param xml
         */
        parseXml: function(xml) {
            var xmldom = null;

            if(typeof DOMParser != "undefined") {
                console.log(typeof DOMParser);
                xmldom = (new DOMParser()).parseFromString(xml, "text/xml");
                var errors = xmldom.getElementsByTagName("parsererror");
                if(errors.length)
                {
                    throw new Error("XML parsing error:" + errors[0].textContent);
                }
            }
            else if (typeof ActiveXObject() != "undefined")
            {
                xmldom = createDocument();
                xmldom.loadXml(xml);
                if(xmldom.parseError != 0)
                {
                    throw new Error("XML parsing error: " + xmldom.parseError.reason);
                }

            }
            else
            {
                throw new Error("No XML parser available.");
            }
            return xmldom;
        }
    }


};


/**
 * Rendering engines
 */
var client = function() {

    var engine = {
        ie: 0,
        gecko: 0,
        webkit: 0,
        khtml: 0,
        opera: 0,

        // complete version
        ver: null
    };

    // browsers
    var browser = {
        ie: 0,
        firefox: 0,
        safari: 0,
        konq: 0,
        opera: 0,
        chrome: 0,

        // specific version
        ver: null
    };

    // platform/device/OS
    var system = {
        win: false,
        mac: false,
        x11: false,

        // mobile devices
        iphone: false,
        ipod: false,
        ipad: false,
        ios: false,
        android: false,
        nokiaN: false,
        winMobile: false,

        // game systems
        wii: false,
        ps: false
    };

    // detect rendering engines/browsers
    var ua = navigator.userAgent;

    if(window.opera) {
        engine.ver = browser.ver = window.opera.version();
        engine.opera = browser.opera = parseFloat(engine.ver);
    } else if (/AppleWebKit\/(\S+)/.test(ua)) {
        engine.ver = RegExp["$1"];
        engine.webkit = parseFloat(engine.ver);

        // figure out if it's Chrome or Safari
        if (/Chrome\/(\S+)/.test(ua)) {
            browser.ver = RegExp["$1"];
            browser.chrome = parseFloat(browser.ver);
        } else if (/Version\/(\S+)/.test(ua)) {
            browser.ver = RegExp["$1"];
            browser.safari = parseFloat(browser.ver);
        } else {
            // approximate version
            var safariVersion = 1;
            if(engine.webkit < 100) {
                safariVersion = 1;
            } else if (engine.webkit < 312) {
                safariVersion = 1.2;
            } else if (engine.webkit < 412) {
                safariVersion = 1.3;
            } else {
                safariVersion = 2;
            }
            browser.safari = browser.ver = safariVersion;
        }
    } else if (/KHTML\/(\S+)/.test(ua)) {
        engine.ver = browser.ver = RegExp["$1"];
        engine.khtml = browser.khtml = parseFloat(engine.ver);
    } else if (/rv:([^\)]+)\) Gecko\/\d{8}/.test(ua)) {
        engine.ver = RegExp["$1"];
        engine.gecko = parseFloat(engine.ver);

        // determine if it's Firefox
        if (/Firefox\/(\S+)/.test(ua)) {
            browser.ver = RegExp["$1"];
            browser.firefox = parseFloat(browser.ver);
        }
    } else if (/MSIE ([^;]+)/.test(ua)) {
        engine.ver = browser.ver = RegExp["$1"];
        engine.ie = browser.ie = parseFloat(engine.ver);
    }

    // detect browsers
    browser.ie = engine.ie;
    browser.opera = engine.opera;

    // detect platform
    var p = navigator.platform;
    system.win = p.indexOf("Win") == 0;
    system.mac = p.indexOf("Mac") == 0;
    system.x11 = (p.indexOf("X11") == 0) || (p.indexOf("Linux") == 0);

    // detect windows operating system
    if (system.win) {
        if(/Win(?:dows)?([^do]{2})\s?(\d+\.\d+)?/.test(ua)) {
            if (RegExp["$1"] == "NT") {
                switch(RegExp["$2"]) {
                    case "5.0":
                        system.win = "2000";
                        break;
                    case "5.1":
                        system.win = "XP";
                        break;
                    case "6.0":
                        system.win = "Vista";
                        break;
                    case "6.1":
                        system.win = "7";
                        break;
                    default:
                        system.win = "NT";
                        break;
                }
            } else if (RegExp["$1"] == "9x") {
                system.win = "ME";
            } else {
                system.win = RegExp["$1"];
            }
        }
    }

    // mobile devices
    system.iphone = ua.indexOf("iPhone") > -1;
    system.ipod = ua.indexOf("iPod") > -1;
    system.ipad = ua.indexOf("iPad") > -1;
    system.nokiaN = ua.indexOf("NokiaN") > -1;

    // windows mobile
    if (system.win == "CE") {
        system.winMobile = system.win;
    } else if ( system.win == "Ph") {
        if(/Windows Phone OS (\d+.\d+)/.test(ua)) {
            system.win = "Phone";
            system.winMobile = parseFloat(RegExp["$1"]);
        }
    }
    // determine iOS version
    if (system.mac && ua.indexOf("Mobile") > -1) {
        if(/CPU (?:iPhone )?OS (\d+_\d+)/.test(ua)) {
            system.ios = parseFloat(RegExp.$1.replace("_","."));
        } else {
            system.ios = 2;
        }
    }

    // determine Android version
    if (/Android (\d+\.\d+)/.test(ua)) {
        system.android = parseFloat(RegExp.$1);
    }

    // gaming systems
    system.wii = ua.indexOf("Wii") > -1;
    system.ps = /playstation/i.test(ua);

    // return it
    return {
        engine: engine,
        browser: browser,
        system: system
    };

}();


