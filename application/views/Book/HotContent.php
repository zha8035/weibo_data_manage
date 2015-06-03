<div role="main" class="ui-content" >
	<div data-role="collapsibleset" > 
		<?php foreach ($books as $kind => $bookinfolist):?>
			<div data-role="collapsible">
				<h2><?php echo $kind?><span class="ui-li-count"><?php echo count($bookinfolist)?>种</span></h2>
				<ul data-role="listview" data-split-icon="carat-r">
				<?php foreach ($bookinfolist as $bookinfo):?>
					<li> 
						<a href="#<?php echo $bookinfo['name_en']?>" data-rel="popup">
							<img src="<?php echo $bookinfo['img_url']?>">
							<h2><?php echo $bookinfo['name']?></h2>
							<p><?php echo $bookinfo['describe']?></p>
							<span class="ui-li-count">剩余<?php echo $bookinfo['remaining']?>本</span>
						</a>

						<a href="search/<?php echo $bookinfo['name_en']?>">search <?php echo $bookinfo['name']?></a>
						<div id="<?php echo $bookinfo['name_en']?>" data-role="popup" class="ui-content">
							 <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
							<p><?php echo $bookinfo['name']?></p>
						</div>
					</li>
				<?php endforeach; ?>
				</ul>
			</div>
		<?php endforeach; ?>
	</div>
</div>
