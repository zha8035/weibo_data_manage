<?php foreach($works as $index => $work):?>
<div data-role="page" id="<?php echo "page_".$index ?>">
	<div data-role="header">
		<a id="topic" class="ui-btn-left"><?php echo $topic?></a>
		<input type="range" name="precent"  id="precent"  value="0" min="0" max="<?php echo $total?>" data-mini="true" data-highlight="true" data-theme="c" data-track-theme="b">
		<a id="username" class="ui-btn-right"><?php echo $user?></a>
	</div>
	<div role="main" class="ui-content">
		<ul data-role="listview">
		<?php foreach($work as $w): ?>
		<li>
			<?php echo $w['message']?> 
			<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
				<label ><input type="radio" name="<?php echo $w['id']?>" value="yes"> YES</label>
				<label ><input type="radio" name="<?php echo $w['id']?>" value="unknow"> Unknow</label>
				<label ><input type="radio" name="<?php echo $w['id']?>" value="no"> NO</label>
			</fieldset>
		</li>
		<?php endforeach; ?>
		</ul>
	</div>
	<div data-role="footer">
		<?php for ($i = 0; $i < count($works); $i++):?>
			<a href="<?php echo "#page_$i"?>"><?php echo $i ?></a>
		<?php endfor; ?>
		<button class="ui-btn submit" id="submit" name="submit">提交</button>
	</div>
</div>
<?php endforeach; ?>
