<table>
	<thead>
		<tr>
			<th> Username 	</th>
			<th> recordId 	</th>
		</tr> 
	</thead>
	<tbody>
		<?php foreach($data as $d):?>
		<tr>
			<th> <?php echo $d['username'] ?></th>
			<td> <?php echo $d['record_id']?></td>
		</tr>
		<?php endforeach; ?>	
	</tbody>
</table>