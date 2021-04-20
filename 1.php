<?php


// Возьмем произвольный массив
$foo = array( 
    'number' => 1, 
    'float'  => 1.5, 
    'array'  => array(1,2), 
    'string' => 'bar', 
    'function'=> 'function(){return "foo bar";}' 
); 
// Теперь преобразуем массив в JSON
$json = json_encode($foo); 
// Отдадим клиенту
echo $json;

?>