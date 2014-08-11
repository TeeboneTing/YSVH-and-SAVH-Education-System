<?php 
	$auth_count = $this->db->get_where('extracurricular',array(
			'EPID'=> $_SESSION['id'], 'SEEN'=> 0 ,'AUTH_STATUS >' => 0))->num_rows();
?>

<div class="span8 offset2">
	<div class="row-fluid">
		<a href="<?php echo base_url();?>extracurricular_trace" class="label label-info">
			<i class="icon-th-list icon-white"></i>認證進度追蹤 
			<?php if ($auth_count) { echo "(".$auth_count.")"; }?>
		</a> 
		<a href="<?php echo base_url();?>extracurricular_log" class="label label-info">
			<i class="icon-pencil icon-white"></i>登錄院外研習心得
		</a>
		<a href="<?php echo base_url();?>extracurricular_auth" class="label label-info">
			<i class="icon-certificate icon-white"></i>認證課程
		</a>
	</div>
	
	<h3>已認證院外研習課程：</h3>
	<?php echo $this->pagination->create_links(); ?>
	<table class="table table-striped">
	<tr>
		</td><td><b>課程主題</b></td><td><b>日期</b></td><td><b>地點</b></td>
		<td><b>類別</b></td><td><b>詳細資訊</b></td>
	</tr>
	<?php foreach ($query as $e):?>
	<tr>
		<td><?php echo $e['TOPIC']; ?></td>
		<td><?php echo $e['DATE']; ?></td>
		<td><?php echo $e['PLACE']; ?></td>
		<?php 
			$course_type = $this->db->get_where('course_type',
				array('id'=>$e['course_type']))->row_array()['type_name'];
		?>
		<td><?php echo $course_type; ?></td>
		<td><a href="<?php echo base_url(); ?>extracurricular_detail?e_id=<?php echo $e['id'];?>" class="btn">詳細資訊</a></td>
	</tr>
	<?php endforeach;?>
	</table>
</div>