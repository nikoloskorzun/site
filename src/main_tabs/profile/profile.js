function edit_user()
{

    //remove_element('profile-script-id');

    form = document.getElementById("profile-form-id");
    
    let o = form2json_o(form);
    el = document.getElementById("cat-visible-id")
    el.value = 0;
    if(el.checked)

        el.value = 1;
    
    o.cat_visible = el.value;
    console.log(o)
    let json = JSON.stringify(o);
    let xhr = new XMLHttpRequest();
    xhr.open('POST', '/update_user_info', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function(){
        if (this.status >= 200 && this.status < 400) 
        {
            let response = JSON.parse(this.response);
            if (response.status === "success") 
            {
                loadPage("main_tabs/profile/profile.html");
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

    xhr.send("text="+encodeURIComponent(json));


}


//let form_data_old = (form2json_o(document.getElementById("profile-form-id")));