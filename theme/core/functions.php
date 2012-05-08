<?php

$login=login_menu();
$ly->data['header']="<p id='header-top'>{$login}<a href='http://www.student.bth.se/~dasp11/phpmvc/'><br/>Daniel Spandel</a><br/><a href='http://www.student.bth.se/~dasp11/phpfb.php'>PHPFORMBUILDERCLASS</a></p><a href='{$this->request->createUrl('index')}'><h1>Lydia</h1></a>O hör sen!";
$ly->data['footer']="Vadå fult? Det är ju du som inte har någon smak!";
