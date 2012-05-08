<?php
$time=$ly->session->getFlash('time');

$dispTime="";
if($time!=null)
{
	$time=round(microtime(true))-$time;
	$dispTime="Det är ".$time." sekunder sen du uppdaterade senast! Sjuuukt bra information va?!";
}
$login=login_menu();
$ly->data['header']="Bapelsin.";
$ly->data['slogan']="The smart framework.";
$ly->data['favicon']="bapelsin.png";
$ly->data['logo']="bapelsin.png";
$ly->data['logo_width']=110;
$ly->data['logo_height']=110;
$ly->data['footer']=<<<EOD
<p>Lydia : a framework stolen by Daniel Spandel.</p>

<p>
Here's Daniels 
<a href="http://www.student.bth.se/~dasp11">page at BTH</a>, 
<a href="mailto:superflugan@hotmail.com">email</a>, 
<a href="http://twitter.com/spandel">twitter</a>, 
<a href="http://www.facebook.com/daniel.spandel">facebook</a>, 
<a href="http://www.student.bth.se/cv_daniel_spandel.pdf">CV</a>
</p>

EOD;
//$ly->data['footer']="Vadå fult? Det är ju du som inte har någon smak!<br/><br/>$dispTime";

$ly->data['slogan-adjektivs']=array(
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

$ly->data['slogan']="The {$ly->data['slogan-adjektivs'][array_rand($ly->data['slogan-adjektivs'])]} framework.";


