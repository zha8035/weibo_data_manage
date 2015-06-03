<?php echo "Hello: ".$username ?>
<ul>
<?php foreach($list as $item): ?>
<li>
<a href="<?php echo $item['href'] ?>" ref="external" data-ajax="false">
	<?php echo $item['text'] ?>
</a>
</li>
<?php endforeach;?>
</ul>