<?php
class CHTMLPurifier
{
	public static $instance=null;
	
	public static function purify($text)
	{
		if(!self::$instance)
		{
			require_once(__DIR__.'/HTMLPurifier.standalone.php');
			$config=HTMLPurifier_Config::createDefault();
			$config->set('Cache.DefinitionImpl',null);
			self::$instance=new HTMLPurifier($config);
		}
		return self::$instance->purify($text);
	}
	
}
