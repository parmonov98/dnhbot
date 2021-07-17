<?php
// bootstrap.php
require_once "vendor/autoload.php";

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

// the connection configuration

$paths = array("entities");
$isDevMode = true;

// the connection configuration
$dbParams = array(
  'driver'   => 'pdo_mysql',
  'user'     => 'root',
  'password' => '19981965aBc',
  'dbname'   => 'DNHBot',
  'charset' => 'utf8mb4'

);


$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);
