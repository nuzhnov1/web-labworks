<?php
    $note = (isset($_GET['Уведомление'])) ? 'checked' : 'unchecked';
    $warn = (isset($_GET['Предупреждение'])) ? 'checked' : 'unchecked';
    $error = (isset($_GET['Ошибка'])) ? 'checked' : 'unchecked';
    $fatal = (isset($_GET['Критическая'])) ? 'checked' : 'unchecked';

    $text = date("d F Y H:i:s ") . 'были выбраны следующие флаги:';
    $text .= ($note == 'checked') ? ' "Уведомление"' : '';
    $text .= ($warn == 'checked') ? ' "Предупреждение"' : '';
    $text .= ($error == 'checked') ? ' "Ошибка"' : '';
    $text .= ($fatal == 'checked') ? ' "Критическая ошибка"' : '';

    $result =  $note . ' ' . $warn . ' ' . $error . ' ' . $fatal . ' ' . $text;
    setcookie('visited', $result, time()+7 * 24 * 60 * 60);
    header('location:index.php');
?>
