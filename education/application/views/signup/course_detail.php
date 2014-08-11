<div class="span6 offset3">
<h3>課程詳細資料：</h3>
<table class="table table-striped">
<?php 
if(isset($_GET['c_id'])): // 個別課程
	$course_data = $this->db->get_where('course',array('id'=>$_GET['c_id']))->row_array();
	// 檢查其是否為群組課，若為群組課則必須取得群組課課名
	if($course_data['TOPIC_TYPE'] === "1") 
		{$group_topic = $this->db->get_where('group_course',array('id'=>$course_data['GROUP_TOPIC_ID']))->row_array()['group_topic'];}
?>

	<tr><td><b>課程主題：</b></td><td><b><?php if(isset($group_topic)) {echo $group_topic.":";} echo $course_data['TOPIC'];?></b></td></tr>
	<tr><td>課程類型：</td><td><?php if($course_data['TOPIC_TYPE'] === "0") {echo "個別";} else {echo "群組";}?></td></tr>
	<?php if(isset($group_topic)) echo "<tr><td>節數：</td><td>第".$course_data['GROUP_ORDER']."節</td></tr>"; ?>
	<tr><td>講師：</td><td><a href="speaker_detail?s_id=<?php echo $course_data['SPEAKER_ID']; ?>"><?php echo $this->db->get_where('speaker',array('id'=>$course_data['SPEAKER_ID']))->row_array()['NAME'] ?></a></td></tr>
	<tr><td>地點：</td><td><?php echo $course_data['PLACE'] ?></td></tr>
	<tr><td>日期：</td><td><?php echo $course_data['DATEFROM']." 至 ".$course_data['DATETO'] ?></td></tr>
	<tr><td>時間：</td><td><?php echo $course_data['TIMEFROM']." 至 ".$course_data['TIMETO'] ?></td></tr>
	<tr><td>時數：</td><td><?php echo $course_data['CREDIT'];?></td></tr>
	<tr><td>類別：</td><td><?php echo $this->education_model->get_course_type_by_ID($course_data["COURSE_TYPE"])['type_name']; ?></td></tr>
	<tr><td>人數上限：</td><td><?php if(!empty($course_data["UPLIMIT"])) {echo $course_data["UPLIMIT"]."人";} else {echo "無";} ?></td></tr>
	<tr><td>備註</td><td><?php if(!empty($course_data["NOTE"])) {echo $course_data["NOTE"];} else {echo "無";} ?></td></tr>
	<tr><td>簡介：</td><td><?php if(!empty($course_data["INTRODUCTION"])) {echo $course_data["INTRODUCTION"];} else {echo "無";} ?></td></tr> 
	<tr>
		<td>課程管理者：</td>
		<td><?php 
			$q = $this->education_model->search_by_ID_or_name($course_data['MANAGERID']);
			echo $q[0]['EPNAME']."(".$course_data['MANAGERID'].")";
		?></td>
	</tr>
<?php 
elseif(isset($_GET['g_id'])): //群組課程

	$group_data = $this->db->get_where('group_course',array('id'=> $_GET['g_id']))->row_array();
	$this->db->select('*');
	$this->db->from('course');
	$this->db->where('GROUP_TOPIC_ID',$_GET['g_id']);
	$this->db->order_by("GROUP_ORDER", "asc");
	$small_topics = $this->db->get()->result_array();
?>
	
	<tr><td>群組主題：</td><td> <?php echo $group_data['group_topic']; ?> </td></tr>
	<tr><td>日期：</td><td><?php echo $group_data['DATEFROM']." 至 ".$group_data['DATETO'] ?></td></tr>
	<tr><td colspan='2'></td></tr>
	<?php foreach($small_topics as $e):?>
		<tr><td>節數：</td><td>第 <?php echo $e['GROUP_ORDER']; ?> 節</td></tr>
		<tr><td>小節主題：</td><td><?php echo $e['TOPIC']?></td></tr>
		<tr><td>講師：</td><td><a href="speaker_detail?s_id=<?php echo $e['SPEAKER_ID']; ?>"><?php echo $this->db->get_where('speaker',array('id'=>$e['SPEAKER_ID']))->row_array()['NAME'] ?></a></td></tr>
		<tr><td>地點：</td><td><?php echo $e['PLACE'] ?></td></tr>	
		<tr><td>時間：</td><td><?php echo $e['TIMEFROM']." 至 ".$e['TIMETO'] ?></td></tr>
		<tr><td>時數：</td><td><?php echo $e['CREDIT'];?></td></tr>
		<tr><td>類別：</td><td><?php echo $this->education_model->get_course_type_by_ID($e["COURSE_TYPE"])['type_name']; ?></td></tr>
		<tr><td>人數上限：</td><td><?php if(!empty($e["UPLIMIT"])) {echo $e["UPLIMIT"]."人";} else {echo "無";} ?></td></tr>
		<tr><td>備註：</td><td><?php if(!empty($e["NOTE"])) {echo $e["NOTE"];} else {echo "無";} ?></td></tr> 
		<tr><td>簡介：</td><td><?php if(!empty($e["INTRODUCTION"])) {echo $e["INTRODUCTION"];} else {echo "無";} ?></td></tr> 
		<tr>
		<td>課程管理者：</td>
		<td><?php 
			$q = $this->education_model->search_by_ID_or_name($e['MANAGERID']);
			echo $q[0]['EPNAME']."(".$e['MANAGERID'].")";
		?></td>
		</tr>
		<tr><td colspan='2'></td></tr>
	<?php endforeach;?>
	
<?php endif;?>
</table>
<br>
<a href="<?php echo base_url();?>signup">返回報名系統</a> <br>
</div>