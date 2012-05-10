<h1>Module Manager</h1>
<h2>About</h2>
<p>
Module Manager displays information on modules and enable managing of all Lydia modules. 
Lydia is made up of modules. 
Each module has its own subdirectory in the <code>src</code>-directory.</p>
<h2>Enabled Controllers</h2>
<p>The following controllers exists. You enable and disable controllers in <code>site/config.php.</code> Also listed here are all their public methods.</p>
<ul>
<?php
$temp=null;
foreach($controllers as $item)
{ 
	if(is_array($item) && !empty($item))
	{
		echo "<ul id=''>\n";
		foreach($item as $i)
		{
			echo "<li>\n
			<a href='".create_url($temp."/".$i)."'>$i</a>\n
			</li>\n";
		}
		echo"</ul>\n";
	}
	else if(!is_array($item))
	{
		echo"<li><a href='".create_url($item)."'>".$item."</a></li>";
		$temp=$item;
	} 
}?>
</ul>
