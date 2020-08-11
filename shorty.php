<?php

$post = $_POST;

function valid_url($url){
  $url = trim($url);
  if (!filter_var($url, FILTER_VALIDATE_URL)){
    return 'Wrong link';
  }
  if (!$url){
    return 'Put new link';
  }
  return url_record($url);
}

function url_record($url){
  $file = 'url.txt';
  $h = fopen($file, 'a+');
  $url_arr = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  if (empty($url_arr)){
    $cut_url = get_cut_url($url);
    fwrite($h,$url.':'.$cut_url."<br>");
    fclose($h);
    return "Result: $cut_url";
  }
  foreach ($url_arr as $str) {
    $arr_delim = explode(':', $str);
    if ($url === $arr_delim[0].':'.$arr_delim[1]) {
        return "Result: ".$arr_delim[2].':'.$arr_delim[3]."<br>";
    }
  }
  $cut_url = get_cut_url($url, $url_arr);
  fwrite($h,$url.':'.$cut_url."<br>");
  fclose($h);
  return "Result: ".$cut_url;
}

function get_cut_url($url, $url_arr=[]){
  $url_hash = '';
  for ($i=0; $i<5; $i++){
    $url_hash .= md5($url.microtime())[$i];
  }
  foreach ($url_arr as $str){
    $arr_delim = explode(':', $str);
    if('http:/cut/'.$url_hash === $arr_delim[2].$arr_delim[3]){
      get_cut_url($url,$url_arr);
    }
  }
  return 'http:/cut/'.$url_hash;
}

echo valid_url($post['url']);
