<?php 

function setLanguage($user, $lang = null)
{
  if ($lang !== null) {
    $currentLang = $lang;
  } else {
    if (!$user) {
      $currentLang = 'en';
    } else {
      $currentLang = $user->getLang();
    }
  }

  $langLocale = strtolower($currentLang) . '_' . strtoupper($currentLang);
  putenv("LC_ALL={$langLocale}");
  putenv("LANG=$langLocale");
  putenv("LANGUAGE=$currentLang");

  $encoding = '.UTF-8';
  $setLocaleText = strtolower($currentLang) . '_' . strtoupper($currentLang) . $encoding;
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

  $domain = 'messages';
  textdomain($domain);
  bindtextdomain($domain, 'locales');
  bind_textdomain_codeset($domain, 'utf8');
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
          'text' => "Notify Me 🗣",
          "callback_data" => 'notify/' . $domain
        ],        
      ],
      [
        [
          'text' => "Test message 📥",
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


