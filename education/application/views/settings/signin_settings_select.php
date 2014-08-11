<?php 
	
	if($_SERVER['REQUEST_METHOD'] === "GET") $c_id = $_GET['c_id'];
	$course_data = $this->db->get_where('course',array('id'=>$c_id))->row_array();
	$signin_data = $this->db->get_where('signup',array('course_id'=>$c_id))->result_array();
	$signin_count = 0;
	foreach($signin_data as $e) { if($e['intime']) { $signin_count += 1;} }
	$registered = 0;
	foreach ($signin_data as  $e) {if ($e['registered']) {$registered += 1;}}
	if($course_data['TOPIC_TYPE'] === "1"){
		$group_topic = $this->db->get_where('group_course',
							array('id'=>$course_data['GROUP_TOPIC_ID'])
							)->row_array()['group_topic'];
	}
?>

<div class="span12">
<a href="<?php echo base_url();?>signin" class="label label-info"><i class="icon-home icon-white"></i>回簽到系統</a>
<h4>課名：<?php if(isset($group_topic)) {echo $group_topic.":";} echo $course_data['TOPIC'];?>。日期：<?php echo $course_data['DATEFROM'];?>~<?php echo $course_data['DATETO'];?>。
網路報名人數：<?php echo $registered; ?>人，現場簽到人數：<?php echo $signin_count; ?>人</h4>
<form name="signin" method="POST" action="">
	<?php $day_check = true; ?>
	<h4>請輸入身分證字號： 
		<input name="signin_id" type="text" class="input-medium" <?php if(!$day_check) {echo "disabled";} ?>> 
		<input type="radio" name="time" value="in" <?php if(!isset($_POST['time']) || $_POST['time'] === "in") {echo "checked";} ?> >簽到 
		<input type="radio" name="time" value="out" <?php if($_SERVER['REQUEST_METHOD']==="POST" && $_POST['time'] === "out") {echo "checked";} ?>>簽退
		<input type="text" name="course_id" value="<?php echo $c_id;?>" style="display: none"> 
		<input type="submit" class="btn" value="確定" <?php if(!$day_check) {echo "disabled";} ?>>
	</h4>
</form>
<h3>簽到名單：</h3>
<table class="table table-bordered table-striped">
	<tr>
		<td>姓名</td><td>身分證字號</td><td>院區</td><td>部門</td><td>報名方式</td>
		<td>假別</td><td>簽到</td><td>簽退</td>
	</tr>
	<?php 
	//撈網路報名名單與員工資料
	foreach ($signin_data as $e):
		$employ_data = $this->db->get_where('ftctl_employ',array('IDCARDNO'=>$e['IDCARDNO']))->row_array();
	?>
	<tr>
		<td><?php echo $employ_data['EPNAME']; ?></td>
		<td><?php echo $e['IDCARDNO']; ?></td>
		<td><?php if($employ_data['BRANCHNO'] === "0") {echo "員山";} elseif($employ_data['BRANCHNO'] === "1") {echo "蘇澳";}?></td>
		<td><?php echo $this->education_model->get_dpname($employ_data); ?></td>
		<td><?php if($e['registered']) {echo "網路報名";} else {echo "現場報名";} ?></td>
		<td>
		<select name="<?php echo $e['IDCARDNO']; ?>" class="input-small" <?php if(!$day_check) {echo "disabled";} ?>>
		<option value="0" <?php if(!$e['leave_type']) {echo "selected";} ?>>公假</option>
		<option value="1" <?php if($e['leave_type']) {echo "selected";} ?>>自假</option>
		</select>
		<button class="btn btn-danger leave_type" id="<?php echo $e['IDCARDNO']; ?>" <?php if(!$day_check) {echo "disabled";}?>>修改</button>
		</td>
		<td>
			<input type="text" class="input-small" name="in-<?php echo $e['IDCARDNO'];?>" value="<?php echo $e['intime']; ?>">
			<button class="btn btn-danger modify-time" id="in-<?php echo $e['IDCARDNO'];?>">修改</button> 
		</td>
		<td>
			<input type="text" class="input-small" name="out-<?php echo $e['IDCARDNO'];?>" value="<?php echo $e['outtime']; ?>">
			<button class="btn btn-danger modify-time" id="out-<?php echo $e['IDCARDNO'];?>">修改</button> 
		</td>
	</tr>
	<?php endforeach; ?>
</table>

</div>