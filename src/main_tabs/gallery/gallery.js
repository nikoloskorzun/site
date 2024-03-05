function initGallery() {
    let gallery = document.getElementById('gallery');
    let modal = document.getElementById('myModal');
    let modalImg = document.getElementById('img01');
    let downloadLink = document.getElementById('download');
    let modalTitle = document.getElementById('modalTitle');
   
    // Запрос к PHP-скрипту для получения изображений
    fetch('get_images')
       .then(response => response.json())
       .then(images => {
         images.forEach((image, index) => {
           // Создание URL для изображения
           let imageUrl = "data:image/jpeg;base64," + image.image_data;
   
           // Создание элементов для отображения изображения
           let figure = document.createElement('figure');
           let img = document.createElement('img');
           let caption = document.createElement('figcaption');
           img.src = imageUrl;
           img.alt = image.image_name;
           caption.textContent = image.image_name;
           figure.appendChild(img);
           figure.appendChild(caption);
           gallery.appendChild(figure);
   
           img.onclick = function() {
             modal.style.display = "block";
             modalImg.src = this.src;
             downloadLink.href = this.src;
             modalTitle.textContent = this.alt; // Установка названия изображения
           }
         });
       })
       .catch(error => console.error('Error fetching images:', error));
   
    let span = document.getElementsByClassName("close")[0];
   
    span.onclick = function() {
       modal.style.display = "none";
    }
   
    window.onclick = function(event) {
       if (event.target == modal) {
         modal.style.display = "none";
       }
    }
   }
   




if (document.getElementById('gallery')) {
    // Если элемент уже присутствует, запускаем функцию
    //console.log(111);
    initGallery();
} else {
    // Если элемент еще не появился, начинаем наблюдение за изменениями в DOM
    let observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                let galleryElement = document.getElementById('gallery');
                if (galleryElement) {
                    // Если элемент появился, запускаем функцию
                    //console.log(222);
                    initGallery();
                    // Отключаем наблюдение, чтобы функция не запускалась повторно
                    observer.disconnect();
                }
            }
        });
    });

    // Начинаем наблюдение за изменениями в DOM
    observer.observe(document.body, { childList: true, subtree: true });
}