
function submit_memory_table_form()
{

        let form=document.getElementById("memory_tableForm");
        var formData = new FormData(form); // Создаем объект FormData с данными формы
        console.log(formData);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'memory_table.php', true);

        xhr.onload = function() {
            if (xhr.status >=  200 && xhr.status <  400) 
            {
                // Обработка успешного ответа сервера
                document.getElementById('memory_tableForm_result').innerHTML = xhr.responseText;
            } 
            else 
            {
                // Обработка ошибки
                console.error('Ошибка:', xhr.statusText);
            }
        };

        xhr.onerror = function() {
            // Обработка ошибки соединения
            console.error('Ошибка соединения');
        };

        xhr.send(formData); // Отправляем данные формы
}
