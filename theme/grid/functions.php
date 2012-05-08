<?php

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

$bap->data['slogan-adjektivs']=array(
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

$bap->data['slogan']="The {$bap->data['slogan-adjektivs'][array_rand($bap->data['slogan-adjektivs'])]} framework.";


