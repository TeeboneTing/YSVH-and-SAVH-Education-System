<div class="span8 offset2">
<h3>請輸入課程名稱搜尋：</h3>
<form action="" method="post">
	<input type="text" class="input-large" name="course_name"> 
	<input type="submit" class="btn" value="搜尋">
</form>

<?php if($_SERVER['REQUEST_METHOD'] === "POST"): ?>
	<table class="table table-striped table-bordered">
	<tr>
		<td>課程名稱</td> <td>日期</td> <td>時間</td> 
		<td>時數</td> <td>類別</td> <td>講師</td> <td>地點</td>
	</tr>
	<?php foreach($course as $e):?>
	<tr>
		<td> <a href="<?php echo base_url();?>signin_settings_select?c_id=<?php echo $e['id'];?>">
		<?php 
			if($e['TOPIC_TYPE']){ echo $e['GROUP_TOPIC'].":".$e['TOPIC']; } 
			else{ echo $e['TOPIC']; } 
		?>
		</a> </td>
		
		<td><?php echo $e['DATEFROM']."~".$e['DATETO']; ?></td>
		<td><?php echo $e['TIMEFROM']."~".$e['TIMETO']; ?></td>
		<td><?php echo $e['CREDIT']; ?></td>
		<td><?php echo $e['COURSE_TYPE']; ?></td>
		<td><?php echo $e['SPEAKER']; ?></td>
		<td><?php echo $e['PLACE']; ?></td>
	</tr>
	<?php endforeach;?>
	</table>
<?php endif; ?>

</div>