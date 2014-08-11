<?php 
	// 根據 $_POST裡面的course id，取得所有報名的課程並顯示出來
	$course_ids = array();
	foreach ($_POST as $k => $v){
		$item = explode("_", $k);
		if($item[0] === "id")	{array_push($course_ids, (int)$item[1]);}
	}
?>


<div class="span4 offset4">
<?php if(count($course_ids)): ?>
	<h3>課程報名成功！</h3>
	<h4>課程詳細資料：</h4>
	<?php 
	foreach ($course_ids as $c_id):
		$course_data = $this->db->get_where('course',array('id'=>$c_id))->row_array();
		// 檢查其是否為群組課，若為群組課則必須取得群組課課名
		if($course_data['TOPIC_TYPE'] === "1") 
			{$group_topic = $this->db->get_where('group_course',array('id'=>$course_data['GROUP_TOPIC_ID']))->row_array()['group_topic'];}
	?>
	<table class="table table-striped">
		<tr><td><b>課程主題：</b></td><td><b><?php if(isset($group_topic)) {echo $group_topic.":";} echo $course_data['TOPIC'];?></b></td></tr>
		<?php  if(isset($group_topic)) echo "<tr><td>節數：</td><td>第 ".$course_data['GROUP_ORDER']." 節</td></tr>" ?>
		<tr><td>講師：</td><td><a href="speaker_detail?s_id=<?php echo $course_data['SPEAKER_ID']; ?>>"><?php echo $this->db->get_where('speaker',array('id'=>$course_data['SPEAKER_ID']))->row_array()['NAME'] ?></a></td></tr>
		<tr><td>地點：</td><td><?php echo $course_data['PLACE'] ?></td></tr>
		<tr><td>日期：</td><td><?php echo $course_data['DATEFROM']." 至 ".$course_data['DATETO'] ?></td></tr>
		<tr><td>時間：</td><td><?php echo $course_data['TIMEFROM']." 至 ".$course_data['TIMETO'] ?></td></tr>
		<tr><td>時數：</td><td><?php echo $course_data['CREDIT'];?></td></tr>
		<tr><td>假別：</td><td><?php if ($_POST['leavetype_'.$c_id] === "0") {echo "公假";} else {echo "自假";}?> </td></tr>
	</table>
	<br>
	<?php endforeach;?>
<?php else:?>
	<h3>您沒有報名任何課程！</h3><br>
<?php endif;?>

<a href="<?php echo base_url();?>signup">返回報名系統</a> <br>
</div>
