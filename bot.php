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
require_once('src/whois.main.php');


require 'bootstrap.php';
use mikehaertl\shellcommand\Command;

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

if (!isset($request['message'])) {
  $data = [
    'chat_id' => $request['message']['from']['id'],
    'text' => gettext("This bot helps you to check if a domain name is busy or not. to check a domain send domain name as example.com")
  ];

  sendMessage($data, 'sendMessage');
  die;
}
if (!isset($request['message']['text'])) {
  $data = [
    'chat_id' => $request['message']['from']['id'],
    'text' => gettext("This bot helps you to check if a domain name is busy or not. to check a domain send domain name as example.com")
  ];

  sendMessage($data, 'sendMessage');
  die;
}

// start => 
if ($request['message']['text'] === '/start') {

  // Create and persist a new Author
  $user = (new entities\User())
    ->setTelegramId($request['message']['from']['id'])
    ->setType('user')
    ->setStatus('active')
    ->setLang('en')
    ->setFirstName($request['message']['from']['first_name'])
    ->setLastName($request['message']['from']['last_name']);
  $entityManager->persist($user);

  // Finally flush and execute the database transaction
  try {
    $entityManager->flush();
  } catch (Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
    if ($e->getSQLState() == '23000') {
      $data = [
        'chat_id' => $request['message']['from']['id'],
        'text' => "Welcome back!"
      ];

      sendMessage($data, 'sendMessage');
      die;
    }

    $data = [
      'chat_id' => $request['message']['from']['id'],
      'text' => gettext("undefined error occured!")
    ];

    sendMessage($data, 'sendMessage');
    die;
  }


  $data = [
    'chat_id' => $request['message']['from']['id'],
    'text' => gettext("Welcome. This bot helps you to check if a domain name is busy or not. to check a domain send domain name as example.com")
  ];

  sendMessage($data, 'sendMessage');
  die;
}

//setting en lang
if ($request['message']['text'] === '/en') {

  // Retrieve the author by its last name
  $user = $entityManager->getRepository('entities\User')->findOneBy(['telegram_id' => $request['message']['from']['id']]);

  $entityManager->persist($user);

  $user->setLang('en');
  $entityManager->flush();
  $entityManager->refresh($user);
  // $user->refresh();
  setLanguage($user, 'en');

  // dump($user);

  $inline_keyboard = [
    'chat_id' => $request['message']['from']['id'],
    'text' => $request['message']['text']
  ];

  $data = [
    'chat_id' => $request['message']['from']['id'],
    'text' => gettext("WELL! you have changed your language. This bot helps you to check if a domain name is busy or not. to check a domain send domain name as example.com"),
    "reply_markup" => json_encode($inline_keyboard)
  ];

  sendMessage($data, 'sendMessage');
  die;
}
//setting uz lang
if ($request['message']['text'] === '/uz') {
  // Retrieve the author by its last name
  $user = $entityManager->getRepository('entities\User')->findOneBy(['telegram_id' => $request['message']['from']['id']]);
  setLanguage($user, 'uz');
  // die;

  $entityManager->persist($user);

  $user->setLang('uz');
  $entityManager->flush();
  $entityManager->refresh($user);
  

  // dump($user);

  $inline_keyboard = [
    'chat_id' => $request['message']['from']['id'],
    'text' => $request['message']['text']
  ];

  $data = [
    'chat_id' => $request['message']['from']['id'],
    'text' => _("you have changed your language. This bot helps you to check if a domain name is busy or not. to check a domain send domain name as example.com"),
    // "reply_markup" => json_encode($inline_keyboard)
  ];

  sendMessage($data, 'sendMessage');
  die;
}
//setting ru lang
if ($request['message']['text'] === '/ru') {

  // Retrieve the author by its last name
  $user = $entityManager->getRepository('entities\User')->findOneBy(['telegram_id' => $request['message']['from']['id']]);

  $entityManager->persist($user);

  $user->setLang('ru');
  setLanguage($user, 'ru');

  $entityManager->flush();
  dump($user);

  $inline_keyboard = [
    'chat_id' => $request['message']['from']['id'],
    'text' => $request['message']['text']
  ];

  $data = [
    'chat_id' => $request['message']['from']['id'],
    'text' => gettext("WELL! you have changed your language. This bot helps you to check if a domain name is busy or not. to check a domain send domain name as example.com"),
    "reply_markup" => json_encode($inline_keyboard)
  ];

  sendMessage($data, 'sendMessage');
  die;
}


if (!isset($request['message']['entities']) || $request['message']['entities'][0]['type'] !== 'url') {
  $data = [
    'chat_id' => $request['message']['from']['id'],
    'text' => gettext("Invalid domain or url. send you the domain as example.com")
  ];

  sendMessage($data, 'sendMessage');
  die;

}

$query = substr($request['message']['text'], $request['message']['entities'][0]['offset'], $request['message']['entities'][0]['length']);

if (strripos($query, "://") !== false) {

  $parsed_tokens = parse_url($query);
  // dump($parsed_tokens);
  
  if (!isset($parsed_tokens['host'])) {
    $data = [
      'chat_id' => $request['message']['from']['id'],
      'text' => gettext("Invalid domain or url. send you the domain as example.com")
    ];
  
    sendMessage($data, 'sendMessage');
    die;
  
  }
  
  $query = $parsed_tokens['host'];
}




// Retrieve the author by its last name
$user = $entityManager->getRepository('entities\User')->findOneBy(['telegram_id' => $request['message']['from']['id']]);

setLanguage($user);
// dump($user);
// die;

$raw = null;
$command = new Command('/usr/bin/whois ' . $query);
if ($command->execute()) {
    $raw = $command->getOutput();
} else {
    echo $command->getError();
    $exitCode = $command->getExitCode();
}

// die;
// Getting parsed domain info
// try {
//   // $result = $whois->Lookup($query, true);
// } catch (Exception $e) {
//   // dump($e);
//   // dump($e->getMessage());
//   $data = [
//     'chat_id' => $request['message']['from']['id'],
//     'text' => gettext("Unknown problem occured while checking your domain. Please, try later again")
//   ];

//   sendMessage($data, 'sendMessage');
//   die;
// }

if ($raw) {
  dump($raw);
  // $rows = $result['rawdata'];
  // $raw = implode("\n", $rows);
// echo($raw);die;

  preg_match("/domain name:.+\n/i", $raw, $domains);
  // dump($domains);die;
  if (isset($domains[0])) {
    $domainName = strtoupper(str_replace([" ", "\n"], ["", ""], explode(":", $domains[0])[1]));
    
  }else{
    preg_match("/domain:.+\n/i", $raw, $domains);
    $domainName = strtoupper(str_replace([" ", "\n"], ["", ""], explode(":", $domains[0])[1]));  
  }
  preg_match("/registrar:.+\n/i", $raw, $registrars);    
  if (isset($registrars[0])) {
    $registrar = strtoupper(str_replace([" ", "\n"], ["", ""], explode(":", $registrars[0])[1]));
  }else{
    preg_match("/registrant:.+\n/i", $raw, $registrars);
    $registrar = strtoupper(str_replace([" ", "\n"], ["", ""], explode(":", $registrars[0])[1]));
  }
  preg_match("/creation date:.+\n/i", $raw, $creationDates);
  if (isset($creationDates[0])) {
    $creationDate = strtoupper(str_replace([" ", "\n"], ["", ""], explode(":", $creationDates[0])[1]));
  }else{
    preg_match("/created:.+\n/i", $raw, $creationDates);
    $creationDate = strtoupper(str_replace([" ", "\n"], ["", ""], explode(":", $creationDates[0])[1]));
  }
  
  preg_match("/expiration date:.+\n/i", $raw, $expirationDates);
  if (isset($expirationDates[0])) {
    $creationDate = strtoupper(str_replace([" ", "\n"], ["", ""], explode(":", $expirationDates[0])[1]));
  }else{
    preg_match("/paid-till:.+\n/i", $raw, $expirationDates);
    $expirationDate = strtoupper(str_replace([" ", "\n"], ["", ""], explode(":", $expirationDates[0])[1]));
  }
  preg_match("/(?<=Registrant:)(?s)(.*)(?=Creation Date)/im", $raw, $owners);
  if (isset($owners[0])) {
    $owner = strtoupper(str_replace(["\n"], [""], $owners[0]));
  }else{
    preg_match("/(?<=org:)(?s)(.*)(?=registrar)/im", $raw, $owners);
    $owner = strtoupper(str_replace(["\n"], [""], $owners[0]));
  }

  
  // dump($owners);die;

  
  // $domainName = strtoupper($result['regrinfo']['domain']['name']);
  // $domainName = $info->domainName;
  // $expirationDate = date("Y-m-d", $info->expirationDate);
  // $owner = $info->owner != null ? $info->owner : _('hidden');
}



$domainText = gettext("Domain");
$creationDateText = gettext("Created");
$expirationDateText = gettext("Expires");
$registrarText = gettext("Registrar");
$ownerText = gettext("Owner");

// echo setlocale(LC_ALL, 0);
// die;

$data = [
  'chat_id' => $request['message']['from']['id'],
  'text' => "{$domainText}: {$domainName} ;\n{$creationDateText}: {$creationDate} ;\n{$expirationDateText}: {$expirationDate};\n{$registrarText}: {$registrar} ;\n{$ownerText}: {$owner}."
];

sendMessage($data, 'sendMessage');
die;


function setLanguage($user, $lang = null)
{
  // dump($lang);
  if ($lang !== null) {
    // dump($user->getLang());
    $currentLang = $lang;
    // if ($user->getLang() !== $lang) {
    // }
  } else {
    if (!$user) {
      $currentLang = 'en';
    } else {
      $currentLang = $user->getLang();
    }
  }

  // echo ($currentLang); die;

  // echo $user->getLang();die;

  $langLocale = strtolower($currentLang) . '_' . strtoupper($currentLang);
  // dump($langLocale);
  putenv("LC_ALL={$langLocale}");
  putenv("LANG=$langLocale");
  putenv("LANGUAGE=$currentLang");

  $encoding = '.UTF-8';
  $setLocaleText = strtolower($currentLang) . '_' . strtoupper($currentLang) . $encoding;
  // dump($setLocaleText);
  if ($currentLang == 'ru') {
    $encoding = '.utf-8';
    $setLocaleText = strtolower($currentLang) . '_' . strtoupper($currentLang) . $encoding;  
  }
  if ($currentLang == 'en') {
    $encoding = '.utf-8';
    $setLocaleText = strtolower($currentLang) . '_US'  . $encoding;  
  }
  if ($currentLang == 'uz') {
    $encoding = '.utf-8';
    $setLocaleText = strtolower($currentLang) . '_UZ'  . $encoding;  
  }
  setlocale(LC_ALL, $setLocaleText);
  // echo setlocale(LC_ALL, 0);
  // die;

  $domain = 'messages';
  textdomain($domain);
  bindtextdomain($domain, 'locales');
  bind_textdomain_codeset($domain, 'utf8');
  return setlocale(LC_ALL, 0);
}
function getLanguage()
{
  // $langLocale = strtolower($currentLang) . '_' . strtoupper($currentLang);
  // dump($langLocale);
  
    // die;
  echo setlocale(LC_ALL, 0);
  // die;

  return setlocale(LC_ALL, 0);
}

function startsWith($string, $startString)
{
  $len = strlen($startString);
  return (substr($string, 0, $len) === $startString);
}

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
