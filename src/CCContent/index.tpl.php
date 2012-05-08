<h1>Content controller</h1>
<p>Index för content</p>

<ul>
<?php foreach($contents as $val) :?>
<li><?=$val['id']?>, <?=$val['title']?> by <?=$val['owner']?> <a href="<?=create_url("content/edit/{$val['id']}")?>">edit</a> <a href="<?=create_url("page/view/{$val['id']}")?>">view</a></li>
<?php endforeach; ?>
</ul>
<h2>actions</h2>
<a href="<?=create_url("content/init")?>">Init</a><br/>
<a href="<?=create_url("content/create")?>">New content</a><br/>
<a href="<?=create_url("blog")?>">View blog</a><br/>
