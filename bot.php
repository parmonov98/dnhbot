<?php

// echo phpinfo();
// die;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);
// echo 1;


require 'bootstrap.php';

require_once('src/whois.main.php');
require_once('libs/functions.php');
require "entities/User.php";
require "entities/Pattern.php";

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

// define("MAIN_URL", 'https://api.telegram.org/bot1731382985:AAGjQCZ2ZXCw5tWVJGwpC04AZeQQFuN8DVg/');
define("MAIN_URL", 'http://localhost:5555/bot1731382985:AAGjQCZ2ZXCw5tWVJGwpC04AZeQQFuN8DVg/');


$update = file_get_contents("php://input");

file_put_contents("bot_test.json", $update);


$request = json_decode($update, 1);

file_put_contents("updates/" . $request['update_id'] . ".json", $update);
// print_r($request);

if (isset($request['message'])) {
  require_once('routes/messages.php');
  die;
}
if (isset($request['callback_query'])) {
  require_once('routes/callback_queries.php');
  die;
}
