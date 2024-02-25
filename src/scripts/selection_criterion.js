
    function submit_cat_form()
    {

            let form=document.getElementById("catForm");
            var formData = new FormData(form); // Создаем объект FormData с данными формы
            console.log(formData);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'select_cats.php', true);
    
            xhr.onload = function() {
                if (xhr.status >=  200 && xhr.status <  400) 
                {
                    // Обработка успешного ответа сервера
                    document.getElementById('catForm_result').innerHTML = xhr.responseText;
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
