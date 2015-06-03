<div data-role="pageone" id="pageone">
<div data-role="header" data-theme="c" id="headercate">
<div class="ui-grid-a">
	<div class="ui-block-a" style="width:50%">
		<img src="<?=$imgurl?>" style="height:6em;width:6em" />
	</div>
	<div class="ui-block-b" style="width:50%">
		<b>金卡会员<br/> </b>
		可用积分: <?=$scores?> <br/>
		有效期: <?=$aviable_date?>
	</div>
</div>
</div>

<div data-role="collapsibleset" > 
	<div data-role="collapsible">
		<h2>可用优惠</h2>
		<ul data-role="listview" data-split-icon="carat-r">
		</ul>
		<p>To be continue </p>
	</div>
	<div data-role="collapsible">
		<h2>消费记录</h2>
		<ul data-role="listview" data-split-icon="carat-r">
		</ul>
		<p> To be continue </p>
	</div>
</div>

<center><a href="#" data-role="button" data-inline="true"  data-icon="back" data-iconpos="left">返回</a></center>