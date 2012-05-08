<?php if(isset($content[0]['created'])): ?>
  <h1>Edit Content</h1>
  <p>You can edit and save this content.</p>
<?php else: ?>
  <h1>Create Content</h1>
  <p>Create new content.</p>
<?php endif; ?>


<?=$form->getHTML(array('class'=>'content-edit'))?>

<p class='smaller-text'><em>
<?php if(isset($content[0]['created'])): ?>
  This content were created by <?=$content[0]['owner']?> at <?=$content[0]['created']?>.
<?php else: ?>
  Content not yet created.
<?php endif; ?>

<?php if(isset($content[0]['updated'])):?>
  Last updated at <?=$content[0]['updated']?>.
<?php endif; ?>
</em></p>

<p><a href='<?=create_url('content')?>'>View all content</a></p>
