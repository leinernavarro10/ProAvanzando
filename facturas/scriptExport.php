<?php

//file_put_contents('pictures/'.$_REQUEST['id'].'.png', base64_decode($_REQUEST['imgBase64']));

$data = $_REQUEST['imgBase64'];

list($type, $data) = explode(';', $data);
list(, $data)      = explode(',', $data);
$data = base64_decode($data);

file_put_contents('exports/'.$_POST['id'].'.png', $data);

?>