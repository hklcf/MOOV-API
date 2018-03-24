<?php
/*
    Version: 1.0
    Copyright: HKLCF
    Last Modified: 24/03/2018
*/

$uuid = $_GET['uuid'];
$pid = $_GET['pid']; //VAHM01352733
if(!empty($uuid) && !empty($pid)) {
  $api = "https://mtg.now.com/moov/api/content/checkout?deviceid={$uuid}&devicetype=web&cat=playlist&reftype=&pid={$pid}&preview=F&connect=web&streamtype=stdhls&quality=HD";
  $cookie = file_get_contents('https://eservice-hk.net/moov/cookies.php?_='.microtime(true));
  $login_ch = curl_init();
  curl_setopt($login_ch, CURLOPT_URL, $api);
  curl_setopt($login_ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($login_ch, CURLOPT_COOKIE, "MTGSESSIONID={$cookie}");
  $m3u8_json = curl_exec($login_ch);
  curl_close($login_ch);
  $m3u8_result = json_decode($m3u8_json, true);
  if(empty($m3u8_result['result']['dataObject'])) {
    echo 'Oops, something went wrong, please try again later.';
  } else {
    header("Location: {$m3u8_result['result']['dataObject']['playUrl']}");
  }
} else {
  echo 'error';
}
?>
