<?php

// PHASE: BOOTSTRAP
//
define('LYDIA_INSTALL_PATH', dirname(__FILE__));
define('LYDIA_SITE_PATH', LYDIA_INSTALL_PATH . '/site');

require_once(LYDIA_INSTALL_PATH.'/src/bootstrap.php');

$ly= CLydia::instance();


//
// PHASE: FRONTCONTROLLER ROUTE
//
$ly->frontControllerRoute();
//
// PHASE: THEME ENGINE RENDER
//
$ly->themeEngineRender();

