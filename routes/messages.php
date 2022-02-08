<?php

use mikehaertl\shellcommand\Command;
use Carbon\Carbon;

if (!isset($request['message']['text'])) {
    $data = [
        'chat_id' => $request['message']['from']['id'],
        'text' => __("This bot helps you to check if a domain name is busy or not. to check a domain send domain name as example.com")
    ];

    sendMessage($data, 'sendMessage');
    die;
}

// start => 
if ($request['message']['text'] === '/start') {

    $first_name = isset($request['message']['from']['first_name']) ? $request['message']['from']['first_name'] : null ;
    $last_name = isset($request['message']['from']['last_name']) ? $request['message']['from']['first_name'] : null ;
    // Create and persist a new Author
    $user = (new entities\User())
        ->setTelegramId($request['message']['from']['id'])
        ->setType('user')
        ->setStatus('active')
        ->setLang('en')
        ->setFirstName($first_name)
        ->setLastName($last_name);
    $entityManager->persist($user);

    // Finally flush and execute the database transaction
    try {
        $entityManager->flush();
    } catch (Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
        if ($e->getSQLState() == '23000') {
            $data = [
                'chat_id' => $request['message']['from']['id'],
                'text' => _("Welcome back! If you want to change language choose one of languages below."),
                'reply_markup' => json_encode(getLanguageInlineKeyboard())
            ];

            sendMessage($data, 'sendMessage');
            die;
        }

        $data = [
            'chat_id' => $request['message']['from']['id'],
            'text' => __("undefined error occured!")
        ];

        sendMessage($data, 'sendMessage');
        die;
    }


    $data = [
        'chat_id' => $request['message']['from']['id'],
        'text' => __("Welcome. This bot helps you to check if a domain name is busy or not. to check a domain send domain name as example.com"),
        'reply_markup' => json_encode(getLanguageInlineKeyboard())
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
    setLanguage('en');


    $text = __("Well! you have changed your language. This bot helps you to check if a domain name is busy or not. to check a domain send domain name as example.com");
//    dd($text);
    $data = [
        'chat_id' => $request['message']['from']['id'],
        'text' => $text,
    ];

    sendMessage($data, 'sendMessage');
    die;
}
//setting uz lang
if ($request['message']['text'] === '/uz') {
    // Retrieve the author by its last name
    $user = $entityManager->getRepository('entities\User')->findOneBy(['telegram_id' => $request['message']['from']['id']]);

    $entityManager->persist($user);

//    dd($user);
    $user->setLang('uz');
    $entityManager->flush();
    $entityManager->refresh($user);

    setLanguage('uz');
    _("Well! you have changed your language. This bot helps you to check if a domain name is busy or not. to check a domain send domain name as example.com");
    $text = __("Well! you have changed your language. This bot helps you to check if a domain name is busy or not. to check a domain send domain name as example.com");

//    dd($text);
    $data = [
        'chat_id' => $request['message']['from']['id'],
        'text' => $text,
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
    setLanguage('ru');

    $entityManager->flush();
//    dump($user);

    $inline_keyboard = [
        'chat_id' => $request['message']['from']['id'],
        'text' => $request['message']['text']
    ];

    $data = [
        'chat_id' => $request['message']['from']['id'],
        'text' => __("Well! you have changed your language. This bot helps you to check if a domain name is busy or not. to check a domain send domain name as example.com"),
        "reply_markup" => json_encode($inline_keyboard)
    ];

    sendMessage($data, 'sendMessage');
    die;
}


if (!isset($request['message']['entities']) || $request['message']['entities'][0]['type'] !== 'url') {
    $data = [
        'chat_id' => $request['message']['from']['id'],
        'text' => __("Invalid domain or url. send you the domain as example.com")
    ];

    sendMessage($data, 'sendMessage');
    die;
}

$query = substr($request['message']['text'], $request['message']['entities'][0]['offset'], $request['message']['entities'][0]['length']);
if (strripos($query, "://") !== false) {

    $parsed_tokens = parse_url($query);

    if (!isset($parsed_tokens['host'])) {
        $data = [
            'chat_id' => $request['message']['from']['id'],
            'text' => __("Invalid domain or url. send you the domain as example.com")
        ];

        sendMessage($data, 'sendMessage');
        die;
    }

    $query = $parsed_tokens['host'];
}


// Retrieve the author by its Telegram_id
$user = $entityManager->getRepository('entities\User')->findOneBy(['telegram_id' => $request['message']['from']['id']]);

if ($user) {
    setLanguage($user->getLang());
} else {
    setLanguage('en');
}
//override_function('_', '$arg', 'return __($arg);');

$raw = null;
$command = new Command('/usr/bin/whois ' . $query);
if ($command->execute()) {
    $raw = $command->getOutput();
} else {
    echo $command->getError();
    $exitCode = $command->getExitCode();
}


if ($raw) {
//     dump($raw);

    $patternsOfDomain = $entityManager->getRepository('entities\Pattern')->findBy(['type' => 'domain']);
    $domain = "";
    foreach ($patternsOfDomain as $pattern) {
        $patternText = $pattern->getValue();
        preg_match($patternText, $raw, $domains);
        if (isset($domains[0])) {
            $domain = trim($domains[0]);
        }
    }
    if ($domain == "") {
        $domain = _("-");
    }

    $patternsOfCreationDate = $entityManager->getRepository('entities\Pattern')->findBy(['type' => 'creation_date']);
    $creationDate = "";
    foreach ($patternsOfCreationDate as $pattern) {
        $patternText = $pattern->getValue();
        preg_match($patternText, $raw, $creationDates);
        if (isset($creationDates[0])) {
            $creationDate = Carbon::parse(trim($creationDates[0]))->format('d-M-Y');
        }
    }
    if ($creationDate == "") {
        $creationDate = _("-");
    }

    $patternsOfExpiryDate = $entityManager->getRepository('entities\Pattern')->findBy(['type' => 'expiration_date']);
    // dump($patternsOfExpiryDate);die;
    $expirationDate = "";
    foreach ($patternsOfExpiryDate as $pattern) {
        $patternText = $pattern->getValue();
        preg_match($patternText, $raw, $expirationDates);
        if (isset($expirationDates[0])) {
            $expirationDate = Carbon::parse(trim($expirationDates[0]))->format('d-M-Y');
        }
    }
    if ($expirationDate == "") {
        $expirationDate = _("-");
    }


    $patternsOfRegistrar = $entityManager->getRepository('entities\Pattern')->findBy(['type' => 'registrar']);
    $registrar = "";
    foreach ($patternsOfRegistrar as $pattern) {
        $patternText = $pattern->getValue();
        preg_match($patternText, $raw, $registrars);
        if (isset($registrars[0])) {
            $registrar = trim($registrars[0]);
        }
    }
    if ($registrar == "") {
        $registrar = _("-");
    }

    $patternsOfOwner = $entityManager->getRepository('entities\Pattern')->findBy(['type' => 'owner']);
    $owner = "";
    foreach ($patternsOfOwner as $pattern) {
        $patternText = $pattern->getValue();
        preg_match($patternText, $raw, $owners);
        if (isset($owners[0])) {
            $owner = trim($owners[0]);
        }
    }
    if ($owner == "") {
        $owner = _("-");
    }

    // echo $query;
    $rawIP = null;
    $command = new Command('/usr/bin/host ' . $query);
    if ($command->execute()) {
        $rawIP = $command->getOutput();
    } else {
        echo $command->getError();
        $exitCode = $command->getExitCode();
    }
    $patternsOfIPAddress = $entityManager->getRepository('entities\Pattern')->findBy(['type' => 'ip']);
    $IPAdress = "";
    foreach ($patternsOfIPAddress as $pattern) {
        $patternText = $pattern->getValue();
        preg_match($patternText, $rawIP, $IPAdresses);
        if (isset($IPAdresses[0])) {
            $IPAdress = trim($IPAdresses[0]);
        }
    }
    if ($IPAdress == "") {
        $IPAdress = _("-");
    }
}


$domainText = __("Domain");
$creationDateText = __("Created");
$expirationDateText = __("Expires");
$registrarText = __("Registrar");
$ownerText = __("Owner");

// echo setlocale(LC_ALL, 0);
// die;

$registrar = str_ireplace(["<", '>'], ['', ''], $registrar);
$owner = addcslashes($owner, "><");
$text = "<u><b>{$domainText}</b></u>: {$domain} => $IPAdress ;\n<u><b>{$creationDateText}</b></u>: {$creationDate} ;\n<u><b>{$expirationDateText}</b></u>: {$expirationDate};\n<u><b>{$registrarText}</b></u>: {$registrar} ;\n<u><b>{$ownerText}</b></u>: {$owner}.";
//dd($text);
$data = [
    'chat_id' => $request['message']['from']['id'],
    'text' => $text,
    'parse_mode' => 'HTML',
    'disable_web_page_preview' => true,
    'reply_markup' => json_encode(getNotifyMeButtons($query))
];

$res = sendMessage($data, 'sendMessage');
dd($res);

if ($domain == '' || $creationDate == '-' || $expirationDate == '-' || $registrar == '-' || $IPAdress == '-' || $owner == '-') {
    // echo $DotEnv->get('BOT_OWNER');
    $data = [
        'chat_id' => $DotEnv->get('BOT_DEVELOPER'),
        'text' => "{$domainText}: {$domain} => $IPAdress ;\n{$creationDateText}: {$creationDate} ;\n{$expirationDateText}: {$expirationDate};\n{$registrarText}: {$registrar} ;\n{$ownerText}: {$owner}."
    ];

    sendMessage($data, 'sendMessage');
    $data = [
        'chat_id' => $DotEnv->get('BOT_DEVELOPER'),
        'text' => $raw
    ];

    sendMessage($data, 'sendMessage');
}
die;
