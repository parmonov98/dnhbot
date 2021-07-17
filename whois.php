<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'whois.main.php';

$whois = new Whois();
$query = 'example.com';
$result = $whois->Lookup($query,false);
echo '<pre>';
print_r($result);
echo '</pre>';