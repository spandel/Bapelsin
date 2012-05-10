<?php
//Set error reporting
error_reporting(-1);
ini_set('display_errors',1);

//turn on/off debugging
$bap->config['debug']['display-bapelsin'] = true;
$bap->config['debug']['db-num-queries']=true;
$bap->config['debug']['db-queries']=true;
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
		'modules'	=> array('enabled' => true,'class' => 'CCModules'),
		'page'		=> array('enabled' => true,'class' => 'CCPage'),		
		'themes'	=> array('enabled' => true,'class' => 'CCTheme'),
		'user'		=> array('enabled' => true,'class' => 'CCUser'),
);
/*

$login=login_menu();
$bap->data['header']="Bapelsin.";
$bap->data['slogan']="The smart framework.";
$bap->data['favicon']="bapelsin.png";
$bap->data['logo']="bapelsin.png";
$bap->data['logo_width']=110;
$bap->data['logo_height']=110;
$bap->data['footer']=<<<EOD
<p>Bapelsin : a framework by Daniel Spandel.</p>

<p>
Here's Daniels 
<a href="http://www.student.bth.se/~dasp11">page at BTH</a>, 
<a href="mailto:superflugan@hotmail.com">email</a>, 
<a href="http://twitter.com/spandel">twitter</a>, 
<a href="http://www.facebook.com/daniel.spandel">facebook</a>, 
<a href="http://www.student.bth.se/cv_daniel_spandel.pdf">CV</a>
</p>

EOD;
//$bap->data['footer']="Vadå fult? Det är ju du som inte har någon smak!<br/><br/>$dispTime";
*/
$adj=array(
	'bonkers',
	'smart',
	'fun',
	'ugly',
	'mosaicish',
	'friendly',
	'contemporary',
	'old',
	'dark',
	'light',
	'beautiful',
	'good-looking',
	'wonderful',
	'great',
	'hysterical',
	'weird',
	'strange',
	'funny',
	'yellow',
	'lovely',
	'hated',
	'loved',
	'multi-dimensional',
	'multi-functional',
	'dynamic',
	);

//$bap->data['slogan']="The {$bap->data['slogan-adjektivs'][array_rand($bap->data['slogan-adjektivs'])]} framework.";

$foot=<<<EOD
<p>Bapelsin : a framework by Daniel Spandel.</p>

<p>
Here's Daniels 
<a href="http://www.student.bth.se/~dasp11">page at BTH</a>, 
<a href="mailto:superflugan@hotmail.com">email</a>, 
<a href="http://twitter.com/spandel">twitter</a>, 
<a href="http://www.facebook.com/daniel.spandel">facebook</a>, 
<a href="http://www.student.bth.se/cv_daniel_spandel.pdf">CV</a>
</p>

EOD;
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
	'data'=>array(
		'header'=>'Bapelsin.',
		'slogan'=>"The {$adj[array_rand($adj)]} framework.",
		'favicon'=>'bapelsin.png',
		'logo'=>'bapelsin.png',
		'logo_width'=>110,
		'logo_height'=>110,
		'footer'=>$foot,
		),
	);

$bap->config['routing']=array(
	'home' =>array('enabled'=>true, 'url'=>'index/index'),	
);


/**
* What type of urls should be used?
* 
* default      = 0      => index.php/controller/method/arg1/arg2/arg3
* clean        = 1      => controller/method/arg1/arg2/arg3
* querystring  = 2      => index.php?q=controller/method/arg1/arg2/arg3
*/
$bap->config['url_type'] = 1;

