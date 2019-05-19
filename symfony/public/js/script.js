/**
 * Чтение и загрузка множества файлов
 * @param files
 */
function upload(files) {
    var http = new XMLHttpRequest(); // Создаем объект XHR, через который далее скинем файлы на сервер.
    // Процесс загрузки
    if (http.upload && http.upload.addEventListener) {
        http.onreadystatechange = function () {
            // Действия после загрузки файлов
            if (this.readyState === 4) { // Считываем только 4 результат, так как их 4 штуки и полная инфа о загрузке находится
                if(this.status === 200) { // Если все прошло гладко
                    alert('Файлы успешно загружены.');
                } else {
                    alert('Ошибка загрузки файлов');
                }
            }
        };
        http.upload.addEventListener(
            'error',
            function(e) {
                alert('!! Ошибка загрузки файлов');
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

/**
 * Загрузка файла через URL
 */
function uploadUrl() {
    var http = new XMLHttpRequest();
    if (http.upload && http.upload.addEventListener) {
        http.onreadystatechange = function () {
            try {
                if (this.readyState === XMLHttpRequest.DONE) {
                    if (this.status === 200) {
                        alert('Успешный запрос');
                    } else {
                        alert('Ошибка запроса');
                    }
                }
            } catch (e) {
                alert('Caught Exception: ' + e.description);
            }
        };
        http.upload.addEventListener(
            'error',
            function (e) {
                alert('!! Ошибка запроса');
            }
        );
    }
    var url = document.querySelector('#url').value;
    http.open('POST', '/api/upload'); // Открываем коннект до сервера.
    http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    http.send('url=' + encodeURIComponent(url));
}
