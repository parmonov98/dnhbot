<?php

$callback_data = $request['callback_query']['data'];
// Retrieve the author by its last name
$user = $entityManager->getRepository('entities\User')->findOneBy(['telegram_id' => $request['callback_query']['from']['id']]);
$entityManager->persist($user);


if ($user) {
    setLanguage($user->getLang());
} else {
    setLanguage('en');
}

switch ($callback_data) {
    case '/en':

        $user->setLang('en');
        $entityManager->flush();
        $entityManager->refresh($user);
        // $user->refresh();
        setLanguage('en');

        deleteMessage($request['callback_query']['from']['id'], $request['callback_query']['message']['message_id']);

        $data = [
            'chat_id' => $request['callback_query']['from']['id'],
            'text' => __("Well! you have changed your language. This bot helps you to check if a domain name is busy or not. to check a domain send domain name as example.com"),
        ];
        sendMessage($data, 'sendMessage');


        answerCallbackQuery(__("Language changed"), false);
        die;
        break;
    case '/ru':


        $user->setLang('ru');
        $entityManager->flush();
        $entityManager->refresh($user);
        // $user->refresh();
        setLanguage('ru');

        deleteMessage($request['callback_query']['from']['id'], $request['callback_query']['message']['message_id']);
        $data = [
            'chat_id' => $request['callback_query']['from']['id'],
            'text' => __("Well! you have changed your language. This bot helps you to check if a domain name is busy or not. to check a domain send domain name as example.com"),
        ];

        sendMessage($data, 'sendMessage');
        answerCallbackQuery(__("Language changed"), false);
        die;
        break;
    case '/uz':

        $user->setLang('uz');
        $entityManager->flush();
        $entityManager->refresh($user);
        setLanguage('uz');
        deleteMessage($request['callback_query']['from']['id'], $request['callback_query']['message']['message_id']);
        $data = [
            'chat_id' => $request['callback_query']['from']['id'],
            'text' => __("Well! you have changed your language. This bot helps you to check if a domain name is busy or not. to check a domain send domain name as example.com"),
        ];

        sendMessage($data, 'sendMessage');
        answerCallbackQuery(__("Language changed"), false);
        die;
        break;

    default:

        if (strpos($callback_data, '/') !== false) {
            $pieces = explode('/', $callback_data);
            if ($pieces[0] == 'notify') {


                // echo _("You'll be notified about domain changes");
                echo _("Test");
                answerCallbackQuery(__('You`ll be notified about domain changes'), false);

            }
            if ($pieces[0] == 'send') {

                answerCallbackQuery(__('-'), false);

                __("Reminder: expiration date is soon. DOMAIN: ");
                $data = [
                    'chat_id' => $user->getTelegramId(),
                    'text' => __("Reminder: expiration date is soon. DOMAIN: ") . strtoupper($pieces[1]),
                    'parse_mode' => 'HTML',
                ];

                sendMessage($data, 'sendMessage');
            }
        }

        break;
        $data = [
            'chat_id' => $user->getTelegramId(),
            'text' => "PING"
        ];
        sendMessage($data, 'sendMessage');
}
