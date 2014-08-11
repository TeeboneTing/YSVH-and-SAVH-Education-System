<?php 
	$manager_info = $this->education_model->search_by_ID_or_name($_SESSION['id'])[0];
	$tmp = array();
	if($manager_info['BRANCHNO'] === "0"){ 
		$tmp = $this->db->get_where('course_type',
			array('YSVH_EPID'=>$_SESSION['id']))->result_array();
	}elseif ($manager_info['BRANCHNO'] === "1"){
		$tmp = $this->db->get_where('course_type',
				array('SASVH_EPID'=>$_SESSION['id']))->result_array();
	}
	//病人安全員山院區負責人：G221610514
	$course_types = array();
	foreach ($tmp as $e){ array_push($course_types, $e['id']); }

	$query = array();
	if(count($course_types)){
		$this->db->select('*');
		$this->db->from('elearning');
		$this->db->where(array('AUTH_STATUS'=>0,'BRANCHNO'=>$manager_info['BRANCHNO'])); //也要看申請人的院區
		$this->db->where_in('COURSE_TYPE',$course_types); 
		$query = $this->db->get()->result_array();
	}
?>

<div class="span8 offset2">
	<a href="<?php echo base_url();?>elearning" class="label label-info">
		<i class="icon-repeat icon-white"></i>返回數位學習首頁
	</a>
	<h3>待處理案件：</h3>
	<p> 您負責的課程類別：
		<?php 
			$content = "";
			foreach ($tmp as $e) { $content = $content.$e['type_name']."，"; }
			echo rtrim($content,"，"); 
		?> 
	</p>
	<table class="table table-striped">
	<tr>
		<td><b>送件人</b></td><td><b>課程主題</b></td><td><b>日期</b></td><td><b>時間</b></td>
		<td><b>時數</b></td><td><b>類別</b></td><td><b>認證文件</b></td><td><b>認證</b></td>
	</tr>
	<?php foreach ($query as $e):?>
	<tr>
		<?php $EMPLOY = $this->db->get_where('FTCTL_EMPLOY',array('EPID'=>$e['EPID']))->row_array();?>
		<td><?php echo $EMPLOY['EPNAME']." (".$e['EPID'].")"; ?></td>
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
		<td><a class="btn" href="<?php echo base_url(); ?>elearning_auth_course?e_id=<?php echo $e['id'];?>">認證</a></td>
	</tr>
	<?php endforeach;?>
	</table>
</div>