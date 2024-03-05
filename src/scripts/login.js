

function login()
    
{
        
    let login_form = document.getElementById('login-form');
        
    console.log("submit login-form");
        
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

                action_after_auth();


                //loadPage("profile");
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
        
            

}

