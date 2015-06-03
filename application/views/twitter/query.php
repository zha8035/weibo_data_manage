
<form action="query" method="post" enctype="multipart/form-data" ref="external" data-ajax="false">
	<input type="text" name="range" id="range"><br>
	<select name="topic">
	<?php foreach($topics as $topic): ?>
		<option value="<?php echo $topic['topic']?>"> <?php echo $topic['topic'] ?> </option>>
	<?php endforeach; ?>
	<input type="submit" name="submit" value="Submit">
</form>


<div class="demo-container">
<div id="summaryHolder" class="demo-placeholder">
</div>
</div>

<?php if (isset($query)):?>

<p><?php echo "Range: {$from}-{$to}"?></p>
<p id="topic_value"><?php echo $topic_value?> </p>

<table data-role="table" id="summary" data-mode="columtoggle" class="ui-responsive table-stroke">
	<thead>
		<tr>
			<th data-priority="3"> Yes 		</th>
			<th data-priority="4"> No		</th>
			<th data-priority="1"> Unknow 	</th>
			<th data-priority="1"> Not Yet </th>
		</tr> 
	</thead>
	<tbody>
		<tr>
			<th> <?php echo $cyes ?></th>
			<th> <?php echo $cno ?></th>
			<th> <?php echo $cunknow ?></th>
			<th> <?php echo $cnotyet ?></th>
		</tr>	
	</tbody>
</table>
<br><br>


<table data-role="table" id="queryTable" data-mode="columtoggle" class="ui-responsive table-stroke">
	<thead>
		<tr>
			<th data-priority="3"> Id 	</th>
			<th data-priority="4"> User		</th>
			<th data-priority="1"> Score 	</th>
		</tr> 
	</thead>
	<tbody>
		<?php foreach($lists as $list):?>
		<tr>
			<th> <?php echo $list['id'] ?></th>
			<th> <?php echo $list['user'] ?></th>
			<th> <?php echo $list['score']==1?"Yes":($list['score']==0?"No":"Unknow") ?></th>
		</tr>
		<?php endforeach; ?>	
	</tbody>
</table>

<br><br>


<table data-role="table" id="questionQueryTable" data-mode="columtoggle" class="ui-responsive table-stroke">
	<thead>
		<tr>
			<th data-priority="3"> Id 	</th>
			<th data-priority="1"> Message 	</th>
		</tr> 
	</thead>
	<tbody>
		<?php foreach($question_lists as $list):?>
		<tr>
			<th> <?php echo $list['id'] ?></th>
			<th> <?php echo $list['message'] ?></th>
		</tr>
		<?php endforeach; ?>	
	</tbody>
</table>



<?php endif; ?>