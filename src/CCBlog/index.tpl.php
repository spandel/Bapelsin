<h1>Blog controller</h1>
<p>Index för blog</p>


<?php foreach($contents as $val) :?>
<h2><?=$val['title']?></h2>
<p id='post-posted'><em>Posted on <?=$val['created']?> by <?=$val['owner']?></em></p>
<p id='post-content'>
<?=filter_data($val['data'],$val['filter'])?>
</p>
<p id='manage-post'><a href="<?=create_url("content/edit/".$val['id'])?>">edit</a></p>
<?php endforeach; ?>


