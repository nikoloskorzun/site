// script.js



update_login_top_bar();

document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', function (e) {
        e.preventDefault();
        const page = this.getAttribute('data-page');
        const script = this.getAttribute('script-page');


            loadPage(page);
            
            if(script)
            {
                loadScript(script);
            }
    });
});

function loadPage(page, params = {}) {
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



 
function register_button_press(el) 
{
      
    switch (el.id) 
    {
        case "register-btn":
            document.getElementById('registerModal').style.display = 'block';
            break;

        case "close-btn-reg":
            document.getElementById('registerModal').style.display = 'none';
            break;
        case "submit-btn-reg":
            
            
            //document.getElementById('registerModal').style.display = 'none';
            break; 
        default:
            break;
    }
};

window.onclick = function(event) {
    if (event.target == document.getElementById('registerModal')) 
    {
        document.getElementById('registerModal').style.display = 'none';
    }
}



function checkCaptcha() 
{
    let captchaInput = document.getElementById('captchaInput').value;
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'captcha.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if(this.responseText == "success")
        {
            document.getElementById('submit-btn-reg').style = "";
        }
        
    };
    xhr.send('captcha=' + captchaInput);
}


function create_SHA256_hash(message) 
{
    return CryptoJS.SHA256(message).toString();
}


const Layout_mapping = {
    "ru" : "йцукенгшщзхъфывапролджэ\\ячсмитьбю.ёЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭ/ЯЧСМИТЬБЮ,Ё",
    "en" : "qwertyuiop[]asdfghjkl;'\\zxcvbnm,./`QWERTYUIOP{}ASDFGHJKL:\"|ZXCVBNM<>?~"
};


let arr_ru2en_elems_counting = [];

let arr_ru2en_elems_flags = {};

document.querySelectorAll('.ru2en').forEach(item => {
    item.addEventListener('input', function (e) {
        e.preventDefault();


        if(e.target.value.length === 0)
        {
            
            arr_ru2en_elems_counting = arr_ru2en_elems_counting.filter(function(item) {
                return item !== item.id
            });

            delete arr_ru2en_elems_flags[item.id];
            

        }


        const rusLower = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюя'
        const rusUpper = rusLower.toUpperCase()
        
        const rus = rusLower + rusUpper
        
        if (!arr_ru2en_elems_counting.includes(item.id))
        {
        for (let i = 0; i < e.target.value.length; i++) 
        {
            if (rus.includes(e.target.value[i])) 
            {
                arr_ru2en_elems_flags[item.id] = true;

            }
    
        }

        }
        if(arr_ru2en_elems_flags[item.id])
        {

        let k = 1;
        if (!arr_ru2en_elems_counting.includes(item.id))
        {
            arr_ru2en_elems_counting.push(item.id);
            
        }
        else
        {
            k = 2;
        }


        let result = '';
        for (let i = 0; i < k; i++) 
        {
            result = '';
       
       
        for (let i = 0; i < e.target.value.length; i++) 
        {
            let index = Layout_mapping["ru"].indexOf(e.target.value[i]);
            if (index !== -1) 
            {
                result += Layout_mapping["en"][index];
            } 
            else 
            {
                result += e.target.value[i];
            }
        }
        e.target.value = result;

        }
    
        

    }

    });
});




function update_login_top_bar() 
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




document.addEventListener('DOMContentLoaded', function()
{
    let login_form = document.getElementById('login-form');
    
    let reg_form = document.getElementById('registration-form');

    login_form.addEventListener('submit', function(event) 
    {
        console.log("submit login-form");
        event.preventDefault(); // Предотвращаем стандартную отправку формы

        

        let o = form2json_o(login_form);
        o["password"] = create_SHA256_hash(o["password"]);
        let json = JSON.stringify(o);
        

        
            // AJAX запрос для отправки данных формы
            let xhr = new XMLHttpRequest();
            xhr.open('POST', '/login', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function(){
                if (this.status >= 200 && this.status < 400) 
                {
                    let response = JSON.parse(this.response);
                    if (response.status === "success") 
                    {

                        update_login_top_bar();


                        loadPage("profile");
                    } 
                    else 
                    {

                        if(response.message == "Username already exists.")
                        {


                        }
                        
                    }
                    
                } 
                else 
                {
                    console.error('Ошибка при отправке запроса: ' + this.status);
                }
            };

            xhr.send("text="+encodeURIComponent(json));
        
            

    });

    reg_form.addEventListener('submit', function(event) 
    {
        console.log("submit registration-form");
        event.preventDefault(); // Предотвращаем стандартную отправку формы

        

        let o = form2json_o(reg_form);
        o["password"] = create_SHA256_hash(o["password"]);
        let json = JSON.stringify(o);
        

        
            // AJAX запрос для отправки данных формы
            let xhr = new XMLHttpRequest();
            xhr.open('POST', '/register', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function(){
                if (this.status >= 200 && this.status < 400) 
                {
                    let response = JSON.parse(this.response);
                    if (response.status === "success") 
                    {

                        update_login_top_bar();
                        document.getElementById('registerModal').style.display = 'none';

                        loadPage("profile");
                    } 
                    else 
                    {

                        if(response.message == "Username already exists.")
                        {
                            let input_el = document.getElementById('regUsername');
                            input_el.value = "";
                            input_el.placeholder="Такой пользователь уже существует";
                            input_el.focus();

                        }
                        
                    }
                    
                } 
                else 
                {
                    console.error('Ошибка при отправке запроса: ' + this.status);
                }
            };

            xhr.send("text="+encodeURIComponent(json));
        
            

    });


});

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

function loadScript(script)
{
    var xhrScript = new XMLHttpRequest();
    xhrScript.open('GET', script, true);
    xhrScript.onload = function() {
        if (xhrScript.status >=  200 && xhrScript.status <  400) 
        {
            // Создание и добавление элемента script в DOM
            var script = document.createElement('script');
            script.textContent = xhrScript.responseText;
            document.body.appendChild(script);
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


}
