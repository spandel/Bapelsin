<h3>Modules</h3>
<p>The following modules exists.</p>
<ul>
<?php
$temp=null;
foreach($modules as $item)
{ 
	if(is_array($item) && !empty($item))
	{
			echo "<li>\n
			<a href='".create_url("modules/view/".$item['name'])."'>{$item['name']}</a>\n
			</li>\n";
	}
}
?>
</ul>
<hr>
<h3>Bapelsin core</h3>
<p>Bapelsin core modules.</p>
<ul>
<?php
$temp=null;
foreach($modules as $item)
{ 
	if(is_array($item) && !empty($item) && $item['isBapelsinCore'])
	{
			echo "<li>\n
			{$item['name']}\n
			</li>\n";
	}
}
?>
</ul>
<hr>
<h3>Bapelsin CMF</h3>
<p>Bapelsin CMF modules.</p>
<ul>
<?php
$temp=null;
foreach($modules as $item)
{ 
	if(is_array($item) && !empty($item) && $item['isBapelsinCMF'])
	{
			echo "<li>\n
			{$item['name']}\n
			</li>\n";
	}
}
?>
</ul>
<hr>
<h3>Models</h3>
<p>The following models exists. All classes that starts with 'CM' are considered to be models.</p>
<ul>
<?php
$temp=null;
foreach($modules as $item)
{ 
	if(is_array($item) && !empty($item) && $item['isModel'])
	{
			echo "<li>\n
			{$item['name']}\n
			</li>\n";
	}
}
?>
</ul>
<hr>
<h3>Controllers</h3>
<p>The following controllers exists.</p>
<ul>
<?php
$temp=null;
foreach($modules as $item)
{ 
	if(is_array($item) && !empty($item) && $item['isController'])
	{
			echo "<li>\n
			{$item['name']}\n
			</li>\n";
	}
}
?>
</ul>
<hr>
<h3>Contains SQL</h3>
<p>The following modules contains SQL. (It implements the interface <code>IHasSQL</code></p>
<ul>
<?php
$temp=null;
foreach($modules as $item)
{ 
	if(is_array($item) && !empty($item) &&$item['hasSQL'])
	{
			echo "<li>\n
			{$item['name']}\n
			</li>\n";
	}
}
?>
</ul>
<hr>
<h3>More modules</h3>
<p>Modules that does not implement any specific Lydia interface.</p>
<ul>
<?php
$temp=null;
foreach($modules as $item)
{ 
	if(is_array($item) && !empty($item) && !($item['isController'] || $item['isBapelsinCore'] || $item['isBapelsinCMF']))
	{
			echo "<li>\n
			{$item['name']}\n
			</li>\n";
	}
}
?>
</ul>
