<div class="span12">
<a href="<?php echo base_url();?>signup" class="label label-info"><i class="icon-home icon-white"></i>回報名系統</a>
<a href="<?php echo base_url();?>my_old_course" class="label label-info"><i class="icon-time icon-white"></i>我已參加過的課程</a><br>
<h3>我已報名的課程：</h3>

<table class="table table-striped">
	<tr>
		<td>課程主題</td><td>講師</td><td>地點</td><td>日期</td><td>時間</td>
		<td>節數</td><td>時數</td><td>假別</td><td>修改</td>
	</tr>
<?php
	//取得課程資料，只顯示已報名之未來課程
	$today = idate('Y') - 1911;
	$today = strval($today)."/".date("m/d");
	$my_course = $this->db->get_where('signup',array(
				'IDCARDNO'=>$_SESSION['id'],
				'DATETO >='=>$today
				))->result_array();
	foreach ($my_course as $e):
		$course_data = $this->db->get_where('course',array('id'=>$e['course_id']))->row_array();
		if($course_data['TOPIC_TYPE'] === "1"){
			$group_topic = $this->db->get_where('group_course',
							array('id'=>$course_data['GROUP_TOPIC_ID'])
							)->row_array()['group_topic'];
		}
?>

	<tr>
	<td>
		<a href="<?php echo base_url();?>course_detail?c_id=<?php echo $e['course_id'];?>">
			<?php if(isset($group_topic)) {echo $group_topic.":";} echo $course_data['TOPIC'];?>
		</a>
	</td>
	<td><?php echo $this->db->get_where('speaker',array('id'=>$course_data['SPEAKER_ID']))->row_array()['NAME'] ?></td>
	<td><?php echo $course_data['PLACE'] ?></td>
	<td><?php echo $course_data['DATEFROM']."~".$course_data['DATETO'] ?></td>
	<td><?php echo $course_data['TIMEFROM']."~".$course_data['TIMETO'] ?></td>
	<td><?php if(isset($group_topic)) {echo "第".$course_data['GROUP_ORDER']."節";}?></td>
	<td><?php echo $course_data['CREDIT']?></td>
	<td><?php if ($e['leave_type'] === "0") {echo "公假";} else {echo "自假";}?> </td>
	<td>
	<a href="<?php echo base_url(); ?>cancel?id=<?php echo $_SESSION['id'];?>&c_id=<?php echo $e['course_id'];?>" class="btn btn-danger">取消報名</a>
	</td>
	</tr>
<?php endforeach;?>
</table>
<a href="<?php echo base_url();?>signup" class="label label-info"><i class="icon-home icon-white"></i>回報名系統</a>
<a href="<?php echo base_url();?>my_old_course" class="label label-info"><i class="icon-time icon-white"></i>我已參加過的課程</a><br>
</div>
