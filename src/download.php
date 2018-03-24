<?php
/*
    Version: 1.0
    Copyright: HKLCF
    Last Modified: 24/03/2018
*/

$data = $_POST['data'];
if(!empty($data)) {
  echo base64_encode($data);
} else {
  echo 'error';
}
?>
