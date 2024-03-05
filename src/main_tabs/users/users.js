


function search_users()
{
    form = document.getElementById("users-form-id");

    let form_data = new FormData(form);


    let xhr = new XMLHttpRequest();
    xhr.open('POST', '/main_tabs/users/users.php', true);
    //xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function(){
        if (this.status >= 200 && this.status < 400) 
        {
            
            
            console.log("success");
            let el = document.getElementById("users-output-id");

            el.innerHTML = this.response;

               
               
            
                    
        } 
        else 
        {
            console.error('Ошибка при отправке запроса: ' + this.status);
        }
    };

    xhr.send(form_data);
    
}