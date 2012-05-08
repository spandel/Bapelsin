
<?php if($contents==null):?>
<h1>Page index controller</h1>
<p>Index för page.</p>
<?php else:?>

<h1><?=$contents['title']?></h1>
<p><?=$contents->getFilteredData()?></p>
<p><a href="<?=create_url("content/edit/".$contents['id'])?>">edit</a> <a href="<?=create_url("content")?>">view all</a> </p>
<?php endif;?>
