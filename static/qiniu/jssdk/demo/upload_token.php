<?php
require_once __DIR__ . './autoload.php';

use Qiniu\Auth;

$accessKey = 'ixJ6FoVYJvSK3eO8IXsKkTZiWMGGxRISMuQDeCgx';
$secretKey = 'W45phNfCsu-3zVZO2qf1mJZbRT_0McSQYoXTyFHB';
$auth = new Auth($accessKey, $secretKey);

$bucket = 'demo';
$upToken = $auth->uploadToken($bucket);

//echo $upToken;
echo json_encode(array('uptoken'=>$upToken));

