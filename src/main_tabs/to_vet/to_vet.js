function sortTable(n) 
{
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("to_vet-table-id");
    switching = true;
    dir = "asc"; 

    while (switching) {
        switching = false;
        rows = table.rows;
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];
            if (dir == "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            } else if (dir == "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            switchcount ++;
        } else {
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}

function send_bid()
{
    let data = {};
    for (let key in sessionStorage) {
        if (sessionStorage.hasOwnProperty(key)) {
            data[key.split(":")[0]] = sessionStorage.getItem(key);
        }
    }

    // Преобразуем объект в JSON
    let jsonData = JSON.stringify(data);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', "/send_bid", true);
    // Установка заголовка Content-Type для отправки JSON
    xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
    xhr.onload = function() {
        if (this.status >=  200 && this.status <  400) 
        {
            let response = JSON.parse(this.response);
            if (response.status === "success") 
            {
                sessionStorage.clear();
                let bidItems = document.getElementById('bidItems');

                bidItems.innerHTML = "Заявка отправлена. Уточните удобное время в заявках";
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
    xhr.send(jsonData);






}


function to_vet(cat_id)
{
    
    //console.log(cat_id);
    let el = sessionStorage.getItem(cat_id);
    if(el)
    {
        let vvv= Number(el)+1;
        if(vvv > 1) 
            vvv = 1;
        sessionStorage.setItem(cat_id, vvv);
    }
    else
        sessionStorage.setItem(cat_id, 1);

        bid_draw();
}




function decrease_cat_bids(key)
{
    let el = sessionStorage.getItem(key);
    if(el == 1)

        sessionStorage.removeItem(key);
    else
        sessionStorage.setItem(key, Number(el)-1);

        bid_draw();
}


function bid_draw() 
{
    let bidItems = document.getElementById('bidItems');
    bidItems.innerHTML = "";
    for (let key in sessionStorage) 
    {
        // Проверяем, что ключ действительно принадлежит sessionStorage,
        // так как в JavaScript ключи объекта могут быть перечислены из прототипа
        if (sessionStorage.hasOwnProperty(key)) 
        {
            // Получаем значение по ключу
            let value = sessionStorage.getItem(key);
            
            
            const listItem = document.createElement('li');
            
            listItem.onclick = () => {decrease_cat_bids(key)};
            let name = key.split(":")[1];
            listItem.textContent = `${name} записан`;
            bidItems.appendChild(listItem);


            //console.log(key + ": " + value);
        }
    }
    

    
    
}


if (document.getElementById('bidItems')) {
    // Если элемент уже присутствует, запускаем функцию
    //console.log(111);
    bid_draw();
} else {
    // Если элемент еще не появился, начинаем наблюдение за изменениями в DOM
    let observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                let el = document.getElementById('bidItems');
                if (el) {
                    // Если элемент появился, запускаем функцию
                    //console.log(222);
                    bid_draw();
                    // Отключаем наблюдение, чтобы функция не запускалась повторно
                    observer.disconnect();
                }
            }
        });
    });

    // Начинаем наблюдение за изменениями в DOM
    observer.observe(document.body, { childList: true, subtree: true });
}