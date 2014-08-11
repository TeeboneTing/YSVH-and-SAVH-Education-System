<?php 
	$query = $this->db->get_where('elearning',array(
			'EPID'=> $_SESSION['id'], 'SEEN'=> 0 ))->result_array(); 
	$status = array('尚未認證','認證完成','退回');
?>


<div class="span8 offset2">
	<a href="<?php echo base_url();?>elearning" class="label label-info">
		<i class="icon-repeat icon-white"></i>返回數位學習首頁
	</a>
	<h3>數位學習認證進度：</h3>
	<table class="table table-striped">
	<tr>
		<td><b>課程主題</b></td><td><b>日期</b></td><td><b>時間</b></td>
		<td><b>時數</b></td><td><b>類別</b></td><td><b>認證狀態</b></td>
	</tr>
	<?php foreach ($query as $e):?>
	<?php if ($e['AUTH_STATUS'] > 0) { echo "<font color='red'>";}?>
	<tr>
		<td><?php echo $e['TOPIC']; ?></td>
		<td><?php echo $e['DATE']; ?></td>
		<td><?php echo $e['TIMEFROM']."~".$e['TIMETO']; ?></td>
		<td><?php echo $e['CREDIT']; ?></td>
		<?php 
			$course_type = $this->db->get_where('course_type',
				array('id'=>$e['COURSE_TYPE']))->row_array()['type_name'];
		?>
		<td><?php echo $course_type; ?></td>
		<?php if ($e['AUTH_STATUS'] != 0): ?>
			<td><a href="<?php echo base_url();?>elearning_auth_accept?e_id=<?php echo $e['id'];?>">
				<?php echo $status[$e['AUTH_STATUS']]; ?></a>
			</td>
		<?php else: ?>
			<td><?php echo $status[$e['AUTH_STATUS']]; ?></td>
		<?php endif;?>
	</tr>
	<?php endforeach;?>
	</table>

</div>