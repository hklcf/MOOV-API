<?php
/*
    Version: 1.0
    Copyright: HKLCF
    Last Modified: 24/03/2018
*/

$playlist = $_POST['playlist'];
if(!empty($playlist)) {
  echo base64_encode($playlist);
} else {
  echo 'error';
}
?>
