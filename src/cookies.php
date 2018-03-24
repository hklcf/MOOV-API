<?php
/*
    Version: 1.0
    Copyright: HKLCF
    Last Modified: 24/03/2018
*/

$device_id = '829A9686-A789-4133-88F7-56C9BF26E51A'; // UUID
$moov_id = urldecode('demo@demo.com'); // MOOV Acc
$moov_pw = 'password'; // MOOV PW

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://mtg.now.com/moov/api/user/loginstatuscheck");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "deviceid={$device_id}&devicetype=web&clientver=2.0.5&brand=Apple&model=iPhone+Simulator&os=iPhone+OS&osver=8.2&devicename=web&connect=web&lang=zh_HK&loginid={$moov_id}&notifyid=&password={$moov_pw}&rememberPwdCheckbox=on&rememberPwd=true&autologin=true");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
curl_setopt($ch, CURLOPT_HEADER, 1);
$result = curl_exec($ch);
preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
$cookies = array();
foreach($matches[1] as $item) {
  parse_str($item, $cookie);
  $cookies = array_merge($cookies, $cookie);
}
echo $cookies['MTGSESSIONID'];
if (curl_errno($ch)) {
  echo 'Error:' . curl_error($ch);
}
curl_close ($ch);
?>
