
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