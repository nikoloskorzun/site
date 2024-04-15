async function select(keySelectId, targetSelectId) {
    try {
        // Выполняем асинхронный запрос к указанному URL
        const response = await fetch('https://localhost:8043/main_tabs/bids/get_time.php');
        // Преобразуем ответ в JSON
        const dictionary = await response.json();

        // Теперь, когда у нас есть словарь, мы можем использовать его для заполнения второго select
        populateSelectWithOptions(dictionary, keySelectId, targetSelectId);
    } catch (error) {
        console.error('Ошибка при получении словаря:', error);
    }
}

function populateSelectWithOptions(dictionary, keySelectId, targetSelectId) {
    // Получаем выбранный ключ из первого select элемента
    const keySelect = document.getElementById(keySelectId);
    const selectedKey = keySelect.value;

    // Получаем массив значений по выбранному ключу
    const valuesArray = dictionary[selectedKey];

    // Получаем целевой select элемент
    const targetSelect = document.getElementById(targetSelectId);

    // Очищаем текущие option элементы в целевом select
    targetSelect.innerHTML = '';

    // Создаем и добавляем option элементы в целевой select
    valuesArray.forEach(value => {
        const option = document.createElement('option');
        option.value = value;
        option.text = value;
        targetSelect.appendChild(option);
    });
}
