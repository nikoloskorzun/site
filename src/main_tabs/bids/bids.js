
function update_select() 
{
    console.log(1);


    fetch('https://localhost:8043/main_tabs/bids/get_time.php')
                .then(response => response.json())
                .then(times => {
                console.log(times);
                    const select = document.getElementById("data-select-id");
                    times.forEach(data => {
                        
                    
                                const option = document.createElement('option');
                                option.value = data;
                                option.textContent = data;
                                select.appendChild(option);


                })});
            
    
}
