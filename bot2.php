<?php
define("MAIN_URL", 'http://localhost:5555/bot1731382985:AAGjQCZ2ZXCw5tWVJGwpC04AZeQQFuN8DVg/');

$update = file_get_contents("php://input");

$request = json_decode($update, 1);

$data = [
  'chat_id' => $request['message']['from']['id'],
  'text' => "ping"
];

echo sendMessage($data, 'sendMessage');
die;

function sendMessage($content, $method, $type = '')
{

  $curl = curl_init();

  // set url
  curl_setopt($curl, CURLOPT_URL, MAIN_URL . $method);

  //return the transfer as a string
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

  curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
  // $output contains the output string
  $output = curl_exec($curl);

  curl_close($curl);

  file_put_contents("return_sent.txt", $output);
  return $output;
}
