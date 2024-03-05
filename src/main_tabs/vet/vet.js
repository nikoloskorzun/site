function create_medical_record(bid_cat_id) 
{  

    
    document.getElementById("summit_mf-button-id").onclick = () => {add_medical_record(bid_cat_id);};
    document.getElementById('cat-info-img-id').innerHTML = "";
    document.getElementById('cat-info-name-id').innerHTML = "";

    const xhr = new XMLHttpRequest();
    xhr.open('GET', "/cat_data?bid_cat_id="+bid_cat_id, true);
    // Установка заголовка Content-Type для отправки JSON
    xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
    xhr.onload = function() {
        if (this.status >=  200 && this.status <  400) 
        {
            let response = JSON.parse(this.response);
            if (response.status === "success") 
            {

                let message = JSON.parse(response.message);
                //console.log(message);
                let img = document.createElement('img');
                img.src="data:image/jpeg;base64,"+message.image;
                document.getElementById('cat-info-img-id').appendChild(img);
                
                document.getElementById('cat-info-name-id').innerHTML=message.Name;
                document.getElementById('cat_id').value = message.id;

                //bidItems.innerHTML = "Заявка отправлена";
            }



        }
         else
        {
            console.error('Ошибка при загрузке страницы');
        }
    };
    xhr.onerror = function() {
        console.error('Ошибка при загрузке страницы');
    };
    // Отправка данных в теле запроса
    xhr.send();





    let modal = document.getElementById("myModal");
    //var btn = document.getElementById("openModal");
    let span = document.getElementsByClassName("close")[0];


    modal.style.display = "block";

    span.onclick = ()=> {modal.style.display = "none"; }



    window.onclick = (event) => {
        if (event.target == modal) 
        {
           modal.style.display = "none";
        }
    }



}



function add_medical_record(bid_cat_id)
{
    form = document.getElementById("medicalForm");
    //let o = form2json_o(form);
    //let json = JSON.stringify(o);
    let form_data = new FormData(form);
    form_data.append('cat_id', document.getElementById('cat_id').value);
    form_data.append('bid_cat_id', bid_cat_id);

    let xhr = new XMLHttpRequest();
    xhr.open('POST', '/main_tabs/vet/add_medical_records.php', true);
    //xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function(){
        if (this.status >= 200 && this.status < 400) 
        {
            let response = JSON.parse(this.response);
            if (response.status === "success") 
            {
                console.log("success");
                let modal = document.getElementById("myModal");

                modal.style.display = "none";

                document.getElementById("bids_healer-id").click();
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