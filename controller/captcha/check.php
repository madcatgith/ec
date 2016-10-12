<?php
// Файл для проверки капчи. Возвращает да или нет для форм
    if(session_id() == "") session_start();
    
    reset($_REQUEST); //Safety - sets pointer to top of array
    $firstKey = key($_REQUEST['form_word']);    

    if ($_REQUEST['form_word'][$firstKey] == $_SESSION['form_word['.$firstKey.']'])
        echo json_encode(true);
    else 
        echo json_encode(false);
    
?>
