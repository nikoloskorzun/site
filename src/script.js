// script.js

function create_SHA256_hash(message) 
{
    return CryptoJS.SHA256(message).toString();
}

function remove_element(id) 
{
    let el = document.getElementById(id);
    el.remove();
}

let nav_elements = [];


document.addEventListener('DOMContentLoaded', function() {

    action_after_auth();
    
    loadPage('main_tabs/default.html');

    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            console.log(nav_elements);
            document.getElementById('content').innerHTML = ""
            nav_elements.forEach(element => {
                nav_elements.shift();
                remove_element(element);
            });

            const page = this.getAttribute('data-page');
            const script = this.getAttribute('script-page');
            const script_id = this.getAttribute('script-id');
            if(page)
            {
                loadPage(page);
            }
            if(script)
            {
                loadScript(script, script_id);
                nav_elements.push(script_id);    
            }
        });
    });

});






function loadPage(page, params = {}) 
{
    // Функция для преобразования объекта параметров в JSON
    function objectToJson(params) {
        return JSON.stringify(params);
    }

    // Преобразование объекта параметров в JSON
    const jsonData = objectToJson(params);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', page, true);
    // Установка заголовка Content-Type для отправки JSON
    xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
    xhr.onload = function() {
        if (this.status >=  200 && this.status <  400) {
            document.getElementById('content').innerHTML = this.response;
        } else {
            console.error('Ошибка при загрузке страницы');
        }
    };
    xhr.onerror = function() {
        console.error('Ошибка при загрузке страницы');
    };
    // Отправка данных в теле запроса
    xhr.send(jsonData);
}


function loadScript(script, id="")
{
    let xhrScript = new XMLHttpRequest();
    let scr;
    xhrScript.open('GET', script, true);
    xhrScript.onload = function() {
        if (xhrScript.status >= 200 && xhrScript.status < 400) 
        {
            // Создание и добавление элемента script в DOM
            scr = document.createElement('script');
            scr.id = id;
            scr.textContent = xhrScript.responseText;
            document.body.appendChild(scr);
        } 
        else 
        {
            console.error('Ошибка загрузки скрипта:', xhrScript.statusText);
        }
    };
    xhrScript.onerror = function() {
        console.error('Ошибка соединения при загрузке скрипта');
    };
    xhrScript.send();

    return scr;

}

function loadCSS(url, id="") 
{
    let link = document.createElement("link");
    link.type = "text/css";
    link.id = id;
    link.rel = "stylesheet";
    link.href = url;

    document.getElementsByTagName("head")[0].appendChild(link);
    return link;
}









function action_after_auth() 
{
    let username = null;
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "/user_data", true);
    xhr.onreadystatechange = function(){
        if (xhr.readyState === 4 && xhr.status === 200) 
        {
            // Если запрос успешно выполнен, записываем результат в контейнер
            let response = JSON.parse(this.response);
            if (response.status === "success") 
            {
                
                username = response.username; 
                
                  

                switch (response.access_rights) {
                    case 1:
                        
                        document.querySelectorAll('.admin_').forEach(item => {
                            
                            item.hidden = false;
                        })

                        break;
                
                    case 2:
                        
                        document.querySelectorAll('.user_').forEach(item => {
                                
                            item.hidden = false;
                        })
    
                        break;

                    case 3:
                        
                        document.querySelectorAll('.vet_').forEach(item => {
                                
                            item.hidden = false;
                        })
        
                        break;

                    default:
                        break;
                }

           




                // Получаем элемент top-bar
                let topBar = document.querySelector('.top-bar');

                // Очищаем содержимое top-bar
                topBar.innerHTML = '';

                // Создаем элемент для отображения имени пользователя
                let userNameElement = document.createElement('div');
                userNameElement.textContent = username;
                topBar.appendChild(userNameElement);

                // Создаем кнопку "Выйти"
                let logoutButton = document.createElement('button');
                logoutButton.textContent = 'Выйти';
                logoutButton.onclick = function() 
                
                
                {
                    // Здесь должен быть код для выхода из системы
                    let xhr = new XMLHttpRequest();
                    xhr.open("GET", "/logout", true);
                    xhr.onreadystatechange = function(){
                        if (xhr.readyState === 4 && xhr.status === 200) 
                        {
                            // Если запрос успешно выполнен, записываем результат в контейнер
                            location.reload();

                        } 
                        else if (xhr.readyState ===  4) 
                        {
                            // Если произошла ошибка, выводим сообщение об ошибке
                            
                        }
                    };
                    xhr.send();
                    
                };
                topBar.appendChild(logoutButton);



            }

        } 
        else if (xhr.readyState ===  4) 
        {
            // Если произошла ошибка, выводим сообщение об ошибке
            
        }
    };
    xhr.send();


}


function form2json_o(form) {
    let o = {};
    let formData = new FormData(form);


    let unique = [...new Set(Array.from(formData.keys()))];

    for(let k in unique)
        {
        let arr = formData.getAll(unique[k]);

        if(arr.length == 1)
            o[unique[k]]=formData.get(unique[k]);
        else
            o[unique[k]]=formData.getAll(unique[k]);
           
        }
        

    return o;

}