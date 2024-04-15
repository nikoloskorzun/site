


function submit_admin1_form()
{
    form = document.getElementById("admin1_form-id");

    let form_data = new FormData(form);


    let xhr = new XMLHttpRequest();
    xhr.open('POST', '/main_tabs/work_hours/work_hours.html', true);
    //xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function(){
        if (this.status >= 200 && this.status < 400) 
        {
            
            
            console.log("success");
            let el = document.getElementById("content");

            el.innerHTML = this.response;

               
               
            
                    
        } 
        else 
        {
            console.error('Ошибка при отправке запроса: ' + this.status);
        }
    };

    xhr.send(form_data);
    
}