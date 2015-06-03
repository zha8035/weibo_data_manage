
<form action="orderTask" method="post" enctype="multipart/form-data" ref="external" data-ajax="false">
	<select name="user">
	<?php foreach($users as $user): ?>
		<option value="<?php echo $user['name']?>"><?php echo $user['name']?></option>
	<?php endforeach; ?>
	</select>
	<select name="topic">
	<?php foreach($topics as $topic): ?>
		<option value="<?php echo $topic['topic']?>"> <?php echo $topic['topic'] ?> </option>>
	<?php endforeach; ?>
	<input type="text" name="range" id="range"><br>
	<input type="submit" name="submit" value="Submit">
</form>

<table data-role="table" id="taskTable" data-mode="columtoggle" class="ui-responsive table-stroke">
	<thead>
		<tr>
			<th data-priority="3"> Index 	</th>
			<th data-priority="4"> User		</th>
			<th data-priority="1"> From 	</th>
			<th data-priority="1"> To 		</th>
			<th data-priority="2"> Date 	</th>
			<th data-priority="2"> isDone 	</th>
		</tr> 
	</thead>
	<tbody>
		<?php foreach($tasks as $key => $task):?>
		<tr>
			<th> <?php echo $key ?></th>
			<td> <?php echo $task["user"]?></td>
			<td> <?php echo $task["from"]?></td>
			<td> <?php echo $task["to"]?></td>
			<td> <?php echo date(DATE_RFC822, $task["date"])?></td>
			<td> <?php echo $task["isDone"]==1?"Yes":"No" ?></td>
		</tr>
		<?php endforeach; ?>	
	</tbody>
</table>
