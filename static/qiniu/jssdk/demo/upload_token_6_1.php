<?php
require_once('src/qiniu6_1/io.php');
# @endgist
# @gist require_rs
require_once('src/qiniu6_1/rs.php');
# @endgist
# @gist require_fop
require_once('src/qiniu6_1/fop.php');
# @endgist
# @gist require_rsf
require_once('src/qiniu6_1/rsf.php');
# @endgist
# @gist require_rio
require_once('src/qiniu6_1/resumable_io.php');
# @endgist
# @gist bucket
//$bucket = 'ljjtest';
$bucket = 'images';
# @endgist
# @gist key1
$key1 = 'docs/gist/logo.jpg';
# @endgist
# @gist key2
$key2 = 'file_name_2';
# @endgist
# @gist file
$file = 'docs/gist/logo.jpg';
# @endgist
# @gist domain
//$domain = 'ljjtest.qiniudn.com';
//$domain = '7vil7f.com2.z0.glb.qiniucdn.com';
# @endgist

# @gist set_keys
$accessKey = 'IYvupfDPqlaSvSVWVqD-dsYDvY40CLSMElmn8mts';//'ixJ6FoVYJvSK3eO8IXsKkTZiWMGGxRISMuQDeCgx';
$secretKey = '_hrfbdvcBDf45-PKfgYkAIF7ihEBs8s2825IxOXQ';//'W45phNfCsu-3zVZO2qf1mJZbRT_0McSQYoXTyFHB';
Qiniu_setKeys($accessKey, $secretKey);
# @endgist
# @gist mac_client
$client = new Qiniu_MacHttpClient(null);
# @endgist

//Qiniu_RS_Delete($client, $bucket, $key1);
//Qiniu_RS_Delete($client, $bucket, $key2);

//------------------------------------io-----------------------------------------
# @gist putpolicy
$putPolicy = new Qiniu_RS_PutPolicy($bucket);
$putPolicy->Expires = 3600;
$upToken = $putPolicy->Token(null);
//var_dump($upToken);exit();
# @endgist
echo json_encode(array('uptoken'=>$upToken));exit();

