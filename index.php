<?php

// PHASE: BOOTSTRAP
//
define('BAPELSIN_INSTALL_PATH', dirname(__FILE__));
define('BAPELSIN_SITE_PATH', BAPELSIN_INSTALL_PATH . '/site');

require_once(BAPELSIN_INSTALL_PATH.'/src/bootstrap.php');

$bap= CBapelsin::instance();


//
// PHASE: FRONTCONTROLLER ROUTE
//
$bap->frontControllerRoute();
//
// PHASE: THEME ENGINE RENDER
//
$bap->themeEngineRender();

