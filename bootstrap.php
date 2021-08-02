<?php
// bootstrap.php
require_once "vendor/autoload.php";

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Parmonov98\Dotenv\Dotenv;
$DotEnv = new Dotenv();

// the connection configuration
$paths = array("entities");
$isDevMode = true;

// the connection configuration
$dbParams = array(
  'driver'   => 'pdo_mysql',
  'user'     => $DotEnv->get('DB_USER'),
  'password' => $DotEnv->get('DB_PASS'),
  'dbname'   => $DotEnv->get('DB_NAME'),
  'charset' => 'utf8mb4'

);


$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);
