var Ajax = {
    // Извлечение правильных данных из ответа HTTP
    httpData: function(response, dataType) {
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
        // Возвращение данных, полученных в ответе
        return data;
    },

    // Определение успешности получения ответа HTTP
    httpSuccess: function(response) {
        try {
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
        // Загрузка объекта параметров по умолчанию, если пользователь не
        // представил никаких значений
        options = {
            // Метод http-запроса
            method: options.mode || "POST",
            // URL на который должен быть послан запрос
            url: options.url || "",
            // Время ожидания ответа на запрос
            timeout: options.timeout || 5000,
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
        // Если метод GET и параметры не пусты
        if( options.method == 'GET' && options.getParams != "" ) {
            // Открытие асинхронного запроса GET и добавляем параметры в строку запроса
            xhr.open(options.method, options.url+"?"+options.getParams, true);
        }
        // Если метод POST и параметры не пусты
        if( options.method == 'POST' && options.postParams != "" ) {
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
            // Ожидание, полной загрузки данных
            // и проверки, не истекло ли время запроса
            if(xhr.readyState == 4 && !requestDone) {
                // Проверка успешности запроса
                if(that.httpSuccess(xhr)) {
                    // Выполнение в случае успеха функции обратного вызова
                    // с данными, возвращенными с сервера
                    options.onSuccess(that.httpData(xhr, options.dataType));
                }
                else {
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
            // Установка соединения с сервером
            xhr.send();
        }
        // Если метод POST и параметры не пусты
        if( options.method == 'POST' && options.postParams != "" ) {
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
};
///////////////////////////////////////////////////////////////////////
//                                                                   //
///////////////////////////////////////////////////////////////////////
