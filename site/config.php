<?php
//Set error reporting
error_reporting(-1);
ini_set('display_errors',1);

//turn on/off debugging
$bap->config['debug']['display-bapelsin'] = false;
$bap->config['debug']['db-num-queries']=true;
$bap->config['debug']['db-queries']=false;
$bap->config['debug']['timer']=true;

//set database(s)
$bap->config['database'][0]['dsn']='sqlite:'.BAPELSIN_SITE_PATH.'/data/.ht.sqlite';

//sets a name for the session
$bap->config['session_name']=preg_replace('/[:\.\/-_]/','',$_SERVER["SERVER_NAME"]);
$bap->config['session_key']='lydia';

//set your timezone
$bap->config['timezone']='Europe/Stockholm';

//set character encoding
$bap->config['character_encoding']='UTF-8';

//set hashing algorithm. choose from: plain, md5salt, md5, sha1salt, sha1.
$bap->config['hashing_algorithm']="sha1salt";

//set if creating new users is allowed
$bap->config['create_new_users'] = true;

//set language
$bap->config['language']='en';

//set base url. if null, default base_url will be used.
$bap->config['base_url']=null;

//set controllers. 
$bap->config['controllers'] = 
array(
		'acp'		=> array('enabled' => true,'class' => 'CCAdminControlPanel'),
		'blog'		=> array('enabled' => true,'class' => 'CCBlog'),
		'content'	=> array('enabled' => true,'class' => 'CCContent'),
		'developer'	=> array('enabled' => true,'class' => 'CCDeveloper'),
		'guestbook'	=> array('enabled' => true,'class' => 'CCGuestbook'),
		'index'		=> array('enabled' => true,'class' => 'CCIndex'),				
		'page'		=> array('enabled' => true,'class' => 'CCPage'),		
		'themes'	=> array('enabled' => true,'class' => 'CCTheme'),
		'user'		=> array('enabled' => true,'class' => 'CCUser'),
);

//set what theme to use.
$bap->config['theme'] = array(
	'name'=> 'grid', 
	'stylesheet'=>'style.php',
	'template_file'=>'default.tpl.php',
	'regions'=>array('flash','featured-first','featured-middle','featured-last',
		'primary','sidebar','triptych-first','triptych-middle','triptych-last',
		'footer-column-one','footer-column-two','footer-column-three','footer-column-four',
		'footer',
		),
	);



/**
* What type of urls should be used?
* 
* default      = 0      => index.php/controller/method/arg1/arg2/arg3
* clean        = 1      => controller/method/arg1/arg2/arg3
* querystring  = 2      => index.php?q=controller/method/arg1/arg2/arg3
*/
$bap->config['url_type'] = 1;

