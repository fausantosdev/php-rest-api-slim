<?php

$internalFolder = 'php-rest-api-slim';
$protocol = 'http';

define('CONF_APP_ROOT',( substr($_SERVER['DOCUMENT_ROOT'],-1) == '/' ) ? "{$_SERVER['DOCUMENT_ROOT']}{$internalFolder}" : "{$_SERVER['DOCUMENT_ROOT']}/{$internalFolder}");

$dotenv = Dotenv\Dotenv::createImmutable(CONF_APP_ROOT);
$dotenv->load();

// App configs
define('CONF_APP_KEY',$_ENV['APPKEY']);
define('CONF_APP_NAME','php-rest-api');
define('CONF_APP_URL',$protocol . '://' .  $_SERVER['HTTP_HOST'] . ($internalFolder ? '/' . $internalFolder  : ''));
define('CONF_APP_SHARED',CONF_APP_URL . '/shared');// Recursos compartilhados
define('CONF_APP_UPLOAD_DIR',CONF_APP_ROOT . '/storage/uploads');
define('CONF_APP_VIEWS',CONF_APP_ROOT . '/shared/views');
define('CONF_APP_SESSION_PATH',CONF_APP_ROOT . '/storage/sessions/');

include_once 'database.php';
include_once 'email.php';
include_once 'passwords.php';
include_once 'upload.php';

include_once 'messages.php';
