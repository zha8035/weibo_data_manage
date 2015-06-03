
    <script src="http://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.js"></script>
        <?php if (isset($js)): ?>
			<?php if (is_array($js)): ?>
				<?php foreach ($js as $j ):?>
					<?php if (strpos($j, "http") === 0):?>
						<script src="<?php echo $j?>"> </script>
					<?php else:?>
        				<script src="/application/libraries/<?php echo $j?>"> </script>
        			<?php endif;?>
				<?php endforeach; ?>
			<?php else: ?>
        		<script src="/application/libraries/<?php echo $js?>"> </script>
			<?php endif; ?>
		<?php endif; ?>
			
        <?php if (isset($css)) : ?>
		<?php if (is_array($css)): ?>
			<?php foreach ($css as $cs ):?>
        			<link rel="stylesheet" href="/application/libraries/<?php echo $cs ?>" type="text/css"  />
			<?php endforeach; ?>
		<?php else: ?> 
        		<link rel="stylesheet" href="/application/libraries/<?php echo $css ?>" type="text/css"  />
		<?php endif; ?>
        <?php endif; ?>
</body>
</html>
