window.onload = function () {
    if (window.FormData === undefined) {
        alert('Ваш браузер устарел');
        return;
    }
    // Описываем все события: перетаскивание файлов, «бросание» их в блок для загрузки и т. п.
    $("#drop-block").bind( // #drop-block блок куда мы будем перетаскивать наши файлы
        'dragenter',
        function(e) {
            console.log('dragenter');
            // Действия при входе курсора с файлами  в блок.
        }) .bind(
        'dragover',
        function(e) {
            console.log('dragover');
            // Действия при перемещении курсора с файлами над блоком.
        }).bind(
                'dragleave',
                function(e) {
            console.log('dragleave');
            // Действия при выходе курсора с файлами за пределы блока.
        }).bind(
        'drop',
        function(e) { // Действия при «вбросе» файлов в блок.
            console.log('drop');
            // console.log(e.originalEvent.dataTransfer.files);
                    if (e.originalEvent.dataTransfer.files.length) {
                        // Отменяем реакцию браузера по-умолчанию на перетаскивание файлов.
                        e.preventDefault();
                        e.stopPropagation();
                        // e.originalEvent.dataTransfer.files — массив файлов переданных в браузер.
                        // e.originalEvent.dataTransfer.files[i].size — размер отдельного файла в байтах.
                        // e.originalEvent.dataTransfer.files[i].name — имя отдельного файла.
                        // Что какбэ намекает :-)
                        upload(e.originalEvent.dataTransfer.files); // Функция загрузки файлов.
                    }
        });
};

/**
 * Чтение и загрузку файлов
 * @param files
 */
function upload(files) {
    var http = new XMLHttpRequest(); // Создаем объект XHR, через который далее скинем файлы на сервер.

    // Процесс загрузки
    if (http.upload && http.upload.addEventListener) {

        http.upload.addEventListener( // Создаем обработчик события в процессе загрузки.
            'progress',
            function(e) {
                if (e.lengthComputable) {
                    // e.loaded — сколько байтов загружено.
                    // e.total — общее количество байтов загружаемых файлов.
                    // Кто не понял — можно сделать прогресс-бар :-)
                }
            },
            false
        );

        http.onreadystatechange = function () {
            // Действия после загрузки файлов
            if (this.readyState == 4) { // Считываем только 4 результат, так как их 4 штуки и полная инфа о загрузке находится
                if(this.status == 200) { // Если все прошло гладко

                    // Действия после успешной загрузки.
                    // Например, так
                    // var result = $.parseJSON(this.response);
                    // можно получить ответ с сервера после загрузки.

                } else {
                    // Сообщаем об ошибке загрузки либо предпринимаем меры.
                }
            }
        };

        http.upload.addEventListener(
            'load',
            function(e) {
                // Событие после которого также можно сообщить о загрузке файлов.
                // Но ответа с сервера уже не будет.
                // Можно удалить.
            });

        http.upload.addEventListener(
            'error',
            function(e) {
                // Паникуем, если возникла ошибка!
            });
    }

    var form = new FormData(); // Создаем объект формы.
    form.append('path', '/'); // Определяем корневой путь.
    for (var i = 0; i < files.length; i++) {
        form.append('file[]', files[i]); // Прикрепляем к форме все загружаемые файлы.
    }
    http.open('POST', '/api/upload'); // Открываем коннект до сервера.
    http.send(form); // И отправляем форму, в которой наши файлы. Через XHR.
}