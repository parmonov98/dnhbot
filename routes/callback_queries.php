<?php

$callback_data = $request['callback_query']['data'];
// Retrieve the author by its last name
$user = $entityManager->getRepository('entities\User')->findOneBy(['telegram_id' => $request['callback_query']['from']['id']]);
$entityManager->persist($user);

switch ($callback_data) {
  case '/en':
    
    $user->setLang('en');
    $entityManager->flush();
    $entityManager->refresh($user);
    // $user->refresh();
    setLanguage($user, 'en');


    $data = [
      'chat_id' => $request['callback_query']['from']['id'],
      'text' => gettext("WELL! you have changed your language. This bot helps you to check if a domain name is busy or not. to check a domain send domain name as example.com"),
    ];

    sendMessage($data, 'sendMessage');

    answerCallbackQuery(_("Language changed"), false);
    die;
    break;
  case '/ru':
    

    $user->setLang('ru');
    $entityManager->flush();
    $entityManager->refresh($user);
    // $user->refresh();
    setLanguage($user, 'ru');


    $data = [
      'chat_id' => $request['callback_query']['from']['id'],
      'text' => gettext("WELL! you have changed your language. This bot helps you to check if a domain name is busy or not. to check a domain send domain name as example.com"),
    ];

    sendMessage($data, 'sendMessage');
    answerCallbackQuery(_("Language changed"), false);
    die;
    break;
  case '/uz':

    $user->setLang('uz');
    $entityManager->flush();
    $entityManager->refresh($user);
    // $user->refresh();
    setLanguage($user, 'uz');


    $data = [
      'chat_id' => $request['callback_query']['from']['id'],
      'text' => gettext("WELL! you have changed your language. This bot helps you to check if a domain name is busy or not. to check a domain send domain name as example.com"),
    ];

    sendMessage($data, 'sendMessage');
    answerCallbackQuery(_("Language changed"), false);
    die;
    break;
  
  default:
    # code...
    break;
}