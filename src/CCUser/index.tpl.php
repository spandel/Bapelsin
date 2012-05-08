<h1>Userpage</h1>

<form action="<?=$formAction?>" method='post'>

  <p>Skapa tabell??
    <input type='submit' name='doCreate' value='Create database table' />
  </p>
</form>
<?php if(isset($profile)){?>
	Du är inloggad.

<?php } else{?>
	Du är inte inloggad :S
<?php }?>
