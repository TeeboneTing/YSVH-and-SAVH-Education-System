<div class="span12">
<a href="<?php echo base_url();?>signup" class="label label-info"><i class="icon-home icon-white"></i>回報名系統</a>
<a href="<?php echo base_url();?>my_course" class="label label-info"><i class="icon-ok icon-white"></i>我已報名的課程</a>
<?php if ($_SERVER['REQUEST_METHOD'] === 'POST'):?>
	<a href="<?php echo base_url();?>my_old_course" class="label label-info"><i class="icon-repeat icon-white"></i>所有參加過課程列表</a>
<?php endif;?>
	<h3>我已參加過的課程：</h3>
	<form class="form-search" name="search" action="" method="POST">
		<div class="input-append" style="display: inline">
			<input type="text" class="span3 search-query" name="query">
			<button type="submit" class="btn">搜尋</button>
		</div>
		<input type="radio" name="search_type" value="keyword" checked> 關鍵字
		<input type="radio" name="search_type" value="topic"> 主題
		<input type="radio" name="search_type" value="speaker"> 講師
		
		<button class="btn" data-toggle="modal" data-target="#myModal" title="進階搜尋">
			進階過濾
		</button>
		<input type="text" name="filter_search" value="0" style="display: none">
	</form>
<?php 
	if($_SERVER['REQUEST_METHOD'] === 'GET') 
		{echo $this->pagination->create_links();} 
	elseif ($_SERVER['REQUEST_METHOD'] === 'POST'){
		if(!$_POST['filter_search'])
			{echo "<p><font color='red'>搜尋 ".$_POST['query']." 共 ".count($my_course)." 筆資料。</font></p>";}
		else{
			$filter = "";
			if($_POST['course_type']) { $filter = "類別：".$this->education_model->get_course_type_by_ID($_POST['course_type'])['type_name'];}
			if($_POST['datefrom']) {$filter = $filter." 從".$_POST['datefrom'];}
			if($_POST['dateto']) {$filter = $filter." 到".$_POST['dateto'];}
			if($_POST['leave_type'] === "0") {$filter = $filter." 假別：公假";}
			elseif($_POST['leave_type'] === "1") {$filter = $filter." 假別：自假";}
			echo "<p><font color='red'>過濾 ".$filter." 共 ".count($my_course)." 筆資料。</font></p>";
		}
	}// End of elseif ($_SERVER['REQUEST_METHOD'] === 'POST')
?>

<table class="table table-striped">
	<tr>
		<td>課程主題</td><td>講師</td><td>地點</td><td>日期</td><td>時間</td>
		<td>節數</td><td>時數</td><td>假別</td><td>備註</td>
	</tr>
	<?php
	//取得課程資料，只顯示過去課程
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
		<?php if (empty($e['intime']) || empty($e['outtime'])): ?>
		<font color="red">未簽到/簽退</font>
		<?php  endif;?>
	</td>
	</tr>
	<?php endforeach;?>
</table>
<?php if($_SERVER['REQUEST_METHOD'] === 'GET') {echo $this->pagination->create_links();} ?>
<a href="<?php echo base_url();?>signup" class="label label-info"><i class="icon-home icon-white"></i>回報名系統</a>
<a href="<?php echo base_url();?>my_course" class="label label-info"><i class="icon-ok icon-white"></i>我已報名的課程</a><br>
</div>

<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">進階過濾</h3>
  </div>
  <form name="filter" method="post" action="">
  <div class="modal-body">
    	<table class="table table-striped">
    	<tr>
    		<td>過濾類別：</td> 
    		<td>
    		<select name="course_type">
    		<option value=""></option>
    			<?php //從資料庫撈類別名單 
				$course_type = $this->education_model->get_all_course_type();
				foreach ($course_type as $e): ?>
					<option value='<?php echo $e['id']?>'> <?php echo $e['type_name']; ?> </option>
				<?php endforeach;?>
    		</select>
    		</td>
    	</tr>
    	<tr>
    		<td>日期範圍：</td>
    		<td> 
    			<input type="text" class="input-small" id="datepicker1" name="datefrom"> 至 
    			<input type="text" class="input-small" id="datepicker2" name="dateto">
    		</td>
    	</tr>
    	<tr>
    		<td>過濾假別：</td>
    		<td>
    			<select	name="leave_type">
    				<option value=""></option>
    				<option value="0">公假</option>
    				<option value="1">自假</option>
    			</select>
    		</td>
    	</tr>
    	</table>
  </div>
  <div class="modal-footer">
  	<input type="text" name="search_type" value="keyword" style="display: none">
  	<input type="text" name="filter_search" value="1" style="display: none">
  	<input type="submit" class="btn btn-primary" value="確定">
    <button class="btn" data-dismiss="modal" aria-hidden="true">取消</button>
  </div>
  </form>
</div>