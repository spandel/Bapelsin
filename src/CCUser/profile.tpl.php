<h1>Profile</h1>

<?php if(isset($is_authenticated) && is_array($user['groups'])){?>
	Du är inloggad.
<h2><?=$user['name']?>'s profil</h2>

<p>
<?=$profile_form?>
</p>
You are a member of following groups:
<ul>
<?php
//echo"<pre>".print_r($user,true)."</pre>";

foreach($user['groups'] as $val)
{
	echo "<li>".$val['name']."</li>";
}
?>
</ul>
<?php } else{?>
	Du är inte inloggad :S
<?php }?>
