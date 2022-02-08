<?php


use Gettext\Loader\PoLoader;

function __($string)
{
    $locale = $GLOBALS['locale'];
    $loader = new PoLoader();
    //From a file
    $localPath = 'locales/' . $locale . '/LC_MESSAGES/messages.po';
    $translations = $loader->loadFile($localPath);

//  dd($translations);
    //retrieve
    $translation = $translations->find(null, $string);

    if ($translation) {
        return $translation->getTranslation();
    } else {
        return $string;
    }

}

function setLanguage($lang = null)
{
    if ($lang !== null) {
        $newLang = $lang;
    } else {
        $newLang = 'en_US';
    }
    $GLOBALS['locale'] = $newLang;
    putenv("LANG=" . $newLang);
    setlocale(LC_ALL, $newLang);
    $domain = 'translations';
    textdomain($domain);
    bindtextdomain($domain, 'locales');
    return setlocale(LC_ALL, 0);
}

function getLanguage()
{
    return setlocale(LC_ALL, 0);
}

function getLanguageInlineKeyboard()
{

    return [
        'inline_keyboard' => [
            [
                [
                    'text' => "🇺🇸 - English",
                    "callback_data" => '/en'
                ],
                [
                    'text' => "🇷🇺 - Russian",
                    "callback_data" => '/ru'
                ],
                [
                    'text' => "🇺🇿 - Uzbek",
                    "callback_data" => '/uz'
                ],
            ]
        ]
    ];
}

function getNotifyMeButtons($domain)
{

    return [
        'inline_keyboard' => [
            [
                [
                    'text' => _("Notify Me"),
                    "callback_data" => 'notify/' . $domain
                ],
            ],
            [
                [
                    'text' => _("Test message"),
                    "callback_data" => 'send/' . $domain
                ],
            ]
        ]
    ];
}

function startsWith($string, $startString)
{
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}

function answerCallbackQuery($text = '', bool $show_alert = true, $url = null)
{
    global $request, $user;

    $data = [
        'callback_query_id' => $request['callback_query']['id'],
    ];
    if ($user != null) {
        $data['chat_id'] = $user->getTelegramId();
    } else {
        $data['chat_id'] = $request['callback_query']['from']['id'];
    }

    if ($text !== '') {
        $data['text'] = $text;
    }
    if ($show_alert === true) {
        $data['show_alert'] = true;
    }
    if ($url !== null) {
        $data['url'] = $url;
    }

    return sendMessage($data, 'answerCallbackQuery');
}
function deleteMessage($chat_id, $message_id){
    $data = [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
    ];
    return sendMessage($data, 'deleteMessage');
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

    // file_put_contents("return_sent.txt", $output);
    return $output;
}
