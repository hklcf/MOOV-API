<?php
/*
    Version: 1.0
    Copyright: HKLCF
    Last Modified: 04/12/2018
*/

$pid = $_GET['pid']; //VAHM01352733
if(!empty($pid)) {
  $api = "https://mtg.now.com/moov/api/lyric/getLyric?pid={$pid}";
  $cookie = file_get_contents('https://eservice-hk.net/moov/cookies.php?_='.microtime(true));
  $login_ch = curl_init();
  curl_setopt($login_ch, CURLOPT_URL, $api);
  curl_setopt($login_ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($login_ch, CURLOPT_COOKIE, "MTGSESSIONID={$cookie}");
  $m3u8_json = curl_exec($login_ch);
  curl_close($login_ch);
  $lyric_result = json_decode($m3u8_json, true);
  if(empty($lyric_result['dataObject'])) {
    echo 'Oops, something went wrong, please try again later.';
  } else {
    echo $lyric_result['dataObject']['lyric'];
  }
} else {
  echo 'error';
}
?>
