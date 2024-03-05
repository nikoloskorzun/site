function submit_add_cat() 
{


    form = document.getElementById("add_cat-form-id");
    //let o = form2json_o(form);
    //let json = JSON.stringify(o);
    let form_data = new FormData(form);
    let xhr = new XMLHttpRequest();
    xhr.open('POST', '/main_tabs/add_cat/add_cat.php', true);
    //xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function(){
        if (this.status >= 200 && this.status < 400) 
        {
            let response = JSON.parse(this.response);
            if (response.status === "success") 
            {
                console.log("success");

                document.getElementById('result-addcat-div-id').innerHTML = "Котик добавлен";
            } 
            else 
            {

                
                        
            }
                    
        } 
        else 
        {
            console.error('Ошибка при отправке запроса: ' + this.status);
        }
    };

    xhr.send(form_data);







}