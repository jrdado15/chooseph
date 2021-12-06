<?php
session_start();

define('FB_GRAPH_VERSION', 'v6.0');
define('FB_GRAPH_DOMAIN', 'https://graph.facebook.com/');
define('FB_APP_STATE', 'eciphp');

include_once __DIR__ .(PHP_OS == 'Windows' ? '' : '/' ) . '/fb_config.php';

include_once __DIR__ . '/facebook_api.php';


?>