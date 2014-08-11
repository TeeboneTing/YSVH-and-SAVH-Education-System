<div class="span8 offset2">
<?php if($type): ?>
<h3>課程修改成功！</h3>
<h4>課程詳細資料：</h4>
<?php else: ?>
<h3>課程新增成功！</h3>
<h4>新增課程詳細資料：</h4>
<?php endif; ?>
<table class="table table-striped table-bordered">
	<tr><td>課程主題：</td><td><?php if($_POST['topic_type'] === "1") {echo $_POST['group_topic_show'].":";} echo $_POST['topic']; ?> </td></tr>
	<?php if($_POST['topic_type'] === "1") echo "<tr><td>節數：</td><td>第 ".$_POST['group_order']." 節</td></tr>"; ?>
	<tr><td>講師：</td><td><?php echo $_POST["speaker"]; ?></td></tr>
	<tr><td>地點：</td><td><?php echo $_POST["place"]; ?></td></tr>
	<tr><td>日期：</td><td><?php echo $_POST["from"]." 至 ".$_POST["to"]; ?></td></tr>
	<tr><td>時間：</td><td><?php echo $_POST["timefrom"]." 至 ".$_POST["timeto"]; ?></td></tr>
	<tr><td>時數：</td><td><?php echo $_POST["credit"]; ?></td></tr>
	<tr><td>類別：</td><td><?php echo $this->education_model->get_course_type_by_ID($_POST["course_type"])['type_name']; ?></td></tr>
	<tr><td>課程類型：</td><td><?php if($_POST["class_type"] === "0") echo "實體"; elseif($_POST["class_type"] === "1") {echo "數位";} ?></td></tr>
	<tr><td>人數上限：</td><td><?php if(!empty($_POST["limit"])) {echo $_POST["limit"]."人";} else {echo "無";} ?></td></tr>
	<tr><td>備註：</td><td><?php if(!empty($_POST["note"])) {echo $_POST["note"];} else {echo "無";} ?></td></tr> 
	<tr><td>簡介：</td><td><?php if(!empty($_POST["introduction"])) {echo $_POST["introduction"];} else {echo "無";} ?></td></tr> 
</table> <br>
<?php if($type): ?>
<a href="<?php echo base_url();?>send_email?c_id=<?php echo $_POST['c_id'];?>" class="label label-info" class="label label-info">
	<i class="icon-envelope icon-white"></i>電子郵件通知報名者課程更改
</a>
<a href="<?php echo base_url();?>signup" class="label label-info">
	<i class="icon-repeat icon-white"></i>返回報名系統
</a>
<?php else: ?>
<a href="<?php echo base_url();?>new_course" class="label label-info">
	<i class="icon-repeat icon-white"></i>繼續新增其他課程
</a>
<?php endif; ?>
</div>
