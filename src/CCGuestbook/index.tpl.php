<h1>Collection of wonderful poems</h1>
<p>Hello there dear poet! Why don't you share your talent with us?</p>

<form action="<?=$formAction?>" method='post'>
 
<p>
    <label>Poet: <br/>
    <input type='text' name='poet'/></label><br/>
    <label>Poem: <br/>
    <textarea name='newEntry'></textarea></label>
  </p>
  <p>
    <input type='submit' name='doAdd' value='Add message' />
    <input type='submit' name='doClear' value='Clear all messages' />
    <input type='submit' name='doCreate' value='Create database table' />
  </p>
</form>

<h2>Current messages</h2>

<?php foreach($entries as $val):?>
<div id='comment'>
	<h4><?=$val['poet']?></h4>
	<p id='comment-post'><?=$val['entry']?></p>
	<p id='comment-time'><?=$val['created']?></p>
</div>
<?php endforeach;?>
