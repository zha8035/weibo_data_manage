<form action="uploadData" ref="external" data-ajax="false" method="post" enctype="multipart/form-data">
<input type="file" name="data" id="data"><br>
<input type="submit" name="submit" value="Submit">
</form>

<table data-role="table" id="taskTable" data-mode="columtoggle" class="ui-responsive table-stroke">
	<thead>
		<tr>
			<th data-priority="3"> Index 	</th>
			<th data-priority="4"> Name		</th>
			<th data-priority="4"> From		</th>
			<th data-priority="4"> To		</th>
			<th data-priority="1"> Date 	</th>
		</tr> 
	</thead>
	<tbody>
		<?php foreach($file as $key => $task):?>
		<tr>
			<th> <?php echo $key ?></th>
			<td> <?php echo $task["name"]?></td>
			<td> <?php echo $task["from"]?></td>
			<td> <?php echo $task["to"]?></td>
			<td> <?php echo date(DATE_RFC822, $task["date"])?></td>
		</tr>
		<?php endforeach; ?>	
	</tbody>
</table>