<?php

require_once "vendor/autoload.php";

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Traiwi\Core\Services\Config;

$paths = array("vendor/traiwi/traiwi/src/Core/Entities/");
$isDevMode = true;

$clientConfig = new Config("client/config");

// the connection configuration
$dbParams = array(
	'driver'   => 'pdo_mysql',
	'user'     => $clientConfig->get("user"),
	'password' => $clientConfig->get("password"),
	'dbname'   => $clientConfig->get("dbname"),
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);

?>