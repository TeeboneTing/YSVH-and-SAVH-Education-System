<?php 
	$auth_count = $this->db->get_where('elearning',array(
			'EPID'=> $_SESSION['id'], 'SEEN'=> 0 ,'AUTH_STATUS >' => 0))->num_rows();
?>

<div class="span8 offset2">
	<div class="row-fluid">
		<a href="<?php echo base_url();?>elearning_trace" class="label label-info">
			<i class="icon-th-list icon-white"></i>認證進度追蹤 
			<?php if ($auth_count) { echo "(".$auth_count.")"; }?>
		</a> 
		<a href="<?php echo base_url();?>elearning_log" class="label label-info">
			<i class="icon-pencil icon-white"></i>登錄課程
		</a>
		<a href="<?php echo base_url();?>elearning_auth" class="label label-info">
			<i class="icon-certificate icon-white"></i>認證課程
		</a>
	</div>
	
	<h3>已認證數位學習時數：</h3>
	<?php echo $this->pagination->create_links(); ?>
	<table class="table table-striped">
	<tr>
		</td><td><b>課程主題</b></td><td><b>日期</b></td><td><b>時間</b></td>
		<td><b>時數</b></td><td><b>類別</b></td><td><b>認證文件</b></td>
	</tr>
	<?php foreach ($query as $e):?>
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
		<td><a href="<?php echo base_url();?>elearning_docs?e_id=<?php echo $e['id'];?>">下載文件</a></td>
	</tr>
	<?php endforeach;?>
	</table>
</div>