function get_new_captcha()
{
    let el = document.getElementById('captcha-img-id');
    el.src = "captcha.php" + "?t=" + (new Date().getTime());

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


function register()
{

    reg_form = document.getElementById('registration-form');
    
    console.log("submit registration-form");

        
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

                    action_after_auth();
                    document.getElementById('registerModal').style.display = 'none';

                    //loadPage("profile");
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
        
}



function register_close_button_press() 
{
      
    remove_element("reg-scr-id");
    remove_element("reg-css-id");
    document.getElementById('content').innerHTML = "";
};


