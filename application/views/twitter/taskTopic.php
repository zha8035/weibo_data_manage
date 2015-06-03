<?php echo "Please Select One Topic to Finish Task( if it is empty, means no work todo, cheer )" ?>
<ul>
<?php foreach($topics as $topic): ?>
<li>
<a href="<?php echo $url.'?topic='.$topic ?>" ref="external" data-ajax="false">
	<?php echo $topic ?>
</a>
</li>
<?php endforeach;?>
</ul>