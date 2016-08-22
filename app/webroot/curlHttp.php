<?php
function request_by_curl($remote_server,$post_string,$post_type){
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$remote_server);
curl_setopt($ch,CURLOPT_POSTFIELDS,'resource_id='.$post_string.'&resource_type='.$post_type);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
$data = curl_exec($ch);
curl_close($ch);
return $data;
}