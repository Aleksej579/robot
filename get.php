<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
   
<?php
if(!empty($_POST['URL'])) {
$getfile = $_POST['URL'] . '/robots.txt'; // добавляем имя файла
$file_headers = @get_headers($getfile); // подготавливаем headers страницы

//наличие файла robots.txt
$robots_true_false = $file_headers[0] == 'HTTP/1.1 404 Not Found' ? "Ошибки" : "Ok";
$robots_true_false_state = $file_headers[0] == 'HTTP/1.1 404 Not Found' ? "Файл robots.txt отсутствует" : "Файл robots.txt присутствует";
$robots_true_false_recommendation = $file_headers[0] == 'HTTP/1.1 404 Not Found' ? "Программист: Создать файл robots.txt и разместить его на сайте." : "Доработки не требуются";
    
//Проверка кода ответа сервера для файла robots.txt
$ok_200 = $file_headers[0] != 'HTTP/1.1 200 OK' ? "Ошибки" : "Ok";
$ok_200_state = $file_headers[0] != 'HTTP/1.1 200 OK' ? "При обращении к файлу robots.txt сервер возвращает код ответа (указать код)" : "Файл robots.txt отдаёт код ответа сервера 200";
$ok_200_recommendation = $file_headers[0] != 'HTTP/1.1 200 OK' ? "Программист: Файл robots.txt должны отдавать код ответа 200, иначе файл не будет обрабатываться. Необходимо настроить сайт таким образом, чтобы при обращении к файлу robots.txt сервер возвращает код ответа 200" : "Доработки не требуются";
    
if ($file_headers[0] == 'HTTP/1.1 404 Not Found') {
    //echo 'Возникла ошибка, при получении файла';
} else if ($file_headers[0] == 'HTTP/1.1 200 OK') {
    $file = fopen('robots.txt', 'w');
    $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $getfile);
          curl_setopt($ch, CURLOPT_FILE, $file);
          curl_exec($ch);
          fclose($file);
          curl_close($ch);
 
global $resultfile; // описываем как глобальную переменную
       $resultfile = 'robots.txt'; // файл, который получили
 
if (!file_exists($resultfile)) {
    echo "Ошибка обработки файла: $resultfile";  // Если файл отсутвует, сообщаем ошибку
 
} else {
    $textget = file_get_contents($resultfile);
               htmlspecialchars($textget); // при желании, можно вывести на экран через echo
    
    //директива host
    $host = preg_match("/Host/", $textget) ? "Ok" : "Ошибка";
    $host_included_state = preg_match("/Host/", $textget) ? "Директива Host указана" : "В файле robots.txt не указана директива Host";
    $host_included_recommendation = preg_match("/Host/", $textget) ? "Доработки не требуются" : "Программист: Для того, чтобы поисковые системы знали, какая версия сайта является основных зеркалом, необходимо прописать адрес основного зеркала в директиве Host. В данный момент это не прописано. Необходимо добавить в файл robots.txt директиву Host. Директива Host задётся в файле 1 раз, после всех правил.";
    
    //директива host количество
    $host_count = preg_match_all("/Host/", $textget) ? "Ok" : "Ошибка";
    $h_count_state = preg_match_all("/Host/", $textget) > 1 ? "В файле прописано несколько директив Host" : "В файле прописана 1 директива Host";
    $h_count_recommendation = preg_match_all("/Host/", $textget) > 1 ? "Программист: Директива Host должна быть указана в файле толоко 1 раз. Необходимо удалить все дополнительные директивы Host и оставить только 1, корректную и соответствующую основному зеркалу сайта" : "Доработки не требуются";
    
    //размер файла robots.txt
    $size = filesize($resultfile) . ' байт'; 
    $size_true = filesize($resultfile) ? "Ok" : "Ошибка";
    $size_state = filesize($resultfile) > 32000 ? "Размера файла robots.txt составляет $size , что превышает допустимую        норму" : "Размер файла robots.txt составляет $size , что находится в пределах допустимой нормы";
    $size_recommendation = filesize($resultfile) > 32000 ? "Программист: Максимально допустимый размер файла robots.txt        составляет более 32 кб. Необходимо отредактировть файл robots.txt таким образом, чтобы его размер не превышал 32 Кб" :      "Доработки не требуются";
    
    //Проверка указания директивы Sitemap
    $sitemap = preg_match("/Sitemap/", $textget) ? "Ok" : "Ошибка";
    $_included_state = preg_match("/Sitemap/", $textget) ? "Директива Sitemap указана" : "В файле robots.txt не указана директива Sitemap";
    $_included_recommendation = preg_match("/Sitemap/", $textget) ? "Доработки не требуются" : "Программист: Добавить в файл robots.txt директиву Sitemap";
    }
}
} else {
  echo 'Вы ничего не ввели :(';
}
?>
<table class="tableMain">
       <tr>
           <td class="num">№</td>
           <td class="name">Название проверки</td>
           <td class="state">Статус</td>
           <td class="state_0">Текущее состояние</td>
       </tr>
    </table> 
    
<br><!-- Проверка наличия файла robots.txt -->
   <table>
       <tr>
           <td class="num">1</td>
           <td class="name">Проверка наличия файла robots.txt</td>
           <td class="state" id="state1"><?php echo $robots_true_false; ?></td>
           <td class="state_0">
              <table>
               <tr>
                   <td>Состояние</td>
                   <td><?php echo $robots_true_false_state; ?></td>
               </tr>
               <tr>
                   <td>Рекомендации</td>
                   <td><?php echo $robots_true_false_recommendation; ?></td>
               </tr>
               </table>
           </td>
       </tr>
   </table> 
<br><!-- Проверка указания директивы Host -->
   <table>
       <tr>
           <td class="num">2</td>
           <td class="name">Проверка указания директивы Host</td>
           <td class="state" id="state2"><?php echo $host; ?></td>
           <td class="state_0">
              <table>
               <tr>
                   <td>Состояние</td>
                   <td><?php echo $host_included_state; ?></td>
               </tr>
               <tr>
                   <td>Рекомендации</td>
                   <td><?php echo $host_included_recommendation; ?></td>
               </tr>
               </table>
           </td>
       </tr>
   </table>
<br><!-- Проверка количества директив Host, прописанных в файле -->
   <table>
       <tr>
           <td class="num">3</td>
           <td class="name">Проверка количества директив Host, прописанных в файле</td>
           <td class="state" id="state3"><?php echo $host_count; ?></td>
           <td class="state_0">
              <table>
               <tr>
                   <td>Состояние</td>
                   <td><?php echo $h_count_state; ?></td>
               </tr>
               <tr>
                   <td>Рекомендации</td>
                   <td><?php echo $h_count_recommendation; ?></td>
               </tr>
               </table>
           </td>
       </tr>
   </table>
<br> <!-- Проверка размера файла robots.txt -->
<table>
       <tr>
           <td class="num">4</td>
           <td class="name">Проверка размера файла robots.txt</td>
           <td class="state" id="state4"><?php echo $size_true; ?></td>
           <td class="state_0">
              <table>
               <tr>
                   <td>Состояние</td>
                   <td><?php echo $size_state; ?></td>
               </tr>
               <tr>
                   <td>Рекомендации</td>
                   <td><?php echo $size_recommendation; ?></td>
               </tr>
               </table>
           </td>
       </tr>
   </table>
<br> <!-- Проверка указания директивы Sitemap -->
<table>
       <tr>
           <td class="num">5</td>
           <td class="name">Проверка указания директивы Sitemap</td>
           <td class="state" id="state5"><?php echo $sitemap; ?></td>
           <td class="state_0">
              <table>
               <tr>
                   <td>Состояние</td>
                   <td><?php echo $_included_state; ?></td>
               </tr>
               <tr>
                   <td>Рекомендации</td>
                   <td><?php echo $_included_recommendation; ?></td>
               </tr>
               </table>
           </td>
       </tr>
   </table>
<br> <!-- Проверка кода ответа сервера для файла robots.txt -->
<table>
       <tr>
           <td class="num">6</td>
           <td class="name">Проверка кода ответа сервера для файла robots.txt</td>
           <td class="state" id="state6"><?php echo $ok_200; ?></td>
           <td class="state_0">
              <table>
               <tr>
                   <td>Состояние</td>
                   <td><?php echo $ok_200_state; ?></td>
               </tr>
               <tr>
                   <td>Рекомендации</td>
                   <td><?php echo $ok_200_recommendation; ?></td>
               </tr>
               </table>
           </td>
       </tr>
   </table>
   <script src="main.js"></script>
</body>
</html>