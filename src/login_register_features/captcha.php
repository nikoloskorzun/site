<?php

//session_set_cookie_params(180);
session_start();
// Функция для генерации изображения с капчей
function generateCaptcha() 
{


    $image_height = 32;
    $image_width = 120;
    $length = 5;
    $characters = '0123456789';//abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i =  0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength -  1)];
    }
    $_SESSION['captcha'] = $randomString;

    $image = imagecreatetruecolor($image_width,  $image_height);
    $font_size =  28;
    $font_file = '/usr/share/fonts/captchacode.ttf'; // Укажите путь к файлу шрифта

    $color = imagecolorallocate($image,  200,  100,  90); // Random color
    $white = imagecolorallocate($image,  255,  255,  255);
    imagefilledrectangle($image,  0,  0,  $image_width,  $image_height, $color);
    imagettftext($image, $font_size,  0,  5,  28, $white, $font_file, $_SESSION['captcha']);

    header("Content-type: image/png");
    imagepng($image);
    imagedestroy($image);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //echo "success";
    //exit();
// Проверка капчи
    if (isset($_POST['captcha'])) {

        
        if ($_POST['captcha'] == $_SESSION['captcha']) 
        {
            echo "success";
            $_SESSION['captcha'] = "success";
        } else 
        {
            echo "not success";
        }
    }
    
}
else {
    // Если запрос не является POST-запросом, генерируем изображение капчи
    generateCaptcha();
}

