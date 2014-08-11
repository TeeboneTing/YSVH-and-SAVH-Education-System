<div class="span4" >
	<a href="<?php echo base_url();?>signup" class="label label-info"><i class="icon-home icon-white"></i>回報名系統</a>
	<?php if ($_SERVER['REQUEST_METHOD'] === 'POST'):?>
		<a href="<?php echo base_url();?>old_course" class="label label-info"><i class="icon-repeat icon-white"></i>所有舊課程列表</a>
	<?php endif;?>
</div>
<div class="span12">
	<h3>舊課程列表：</h3>
	<form class="form-search" name="search" action="" method="POST">
		<div class="input-append" style="display: inline">
			<input type="text" class="span3 search-query" name="query">
			<button type="submit" class="btn">搜尋</button>
		</div>
		<input type="radio" name="search_type" value="keyword"
		<?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['search_type'] === 'keyword'){echo "checked";}
			  elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {echo "checked";} ?> > 關鍵字
		<input type="radio" name="search_type" value="topic"
		<?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['search_type'] === 'topic'){echo "checked";}?>> 主題
		<input type="radio" name="search_type" value="speaker"
		<?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['search_type'] === 'speaker'){echo "checked";}?>> 講師
	
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
			{echo "<p><font color='red'>搜尋 ".$_POST['query']." 共 ".count($old_course_data)." 筆資料。</font></p>";}
		else{
			$filter = "";
			if($_POST['course_type']) { $filter = "類別：".$this->education_model->get_course_type_by_ID($_POST['course_type'])['type_name'];}
			if($_POST['datefrom']) {$filter = $filter." 從".$_POST['datefrom'];}
			if($_POST['dateto']) {$filter = $filter." 到".$_POST['dateto'];}
			echo "<p><font color='red'>過濾 ".$filter." 共 ".count($old_course_data)." 筆資料。</font></p>";
		}
	}// End of elseif ($_SERVER['REQUEST_METHOD'] === 'POST')
	?>
	<table class="table table-striped">
	<tr>
	    <td><b>課程類型</b></td> <td><b>課程主題</b></td> <td><b>講師</b></td> <td><b>日期</b></td> 
		<td><b>時數</b></td> <td><b>地點</b></td> 
		<?php if($this->education_model->is_admin($_SESSION['id'])): ?>
		<td><b>修改(管理者)</b></td>
		<?php endif; ?>
	</tr>
	<?php
	foreach ($old_course_data as $e):
		if($e['TOPIC_TYPE'] > 0){
			$group_topic = $this->db->get_where('group_course',
							array('id'=> $e['GROUP_TOPIC_ID'])
							)->row_array()['group_topic'];
		}
	?>		
		<tr>
			<td><?php if($e['TOPIC_TYPE']==="1") {echo "群組";} else {echo "個別";}?></td>
			<td><a href="<?php echo base_url();?>course_detail?c_id=<?php echo $e['id']; ?>">
					<?php if($e['TOPIC_TYPE']==="1"){echo $group_topic.":";} echo $e['TOPIC']; ?></a></td>
			<?php $speaker = $this->db->get_where('speaker',array('id' => $e['SPEAKER_ID']))->row_array()['NAME'];?>
			<td><a href="<?php echo base_url();?>speaker_detail?s_id=<?php echo $e['SPEAKER_ID']; ?>"><?php echo $speaker; ?></a></td>
			<td><?php echo $e['DATEFROM']."~".$e['DATETO']; ?></td>
			<td><?php echo $e['CREDIT']; ?></td>
			<td><?php echo $e['PLACE']?></td>
			<td>
			<?php if($e['MANAGERID'] === $_SESSION['id']): ?>
			<a class="btn btn-danger" href="<?php echo base_url(); ?>modify_course?c_id=<?php echo $e['id']; ?>">修改</a>
			<?php else:
				$q = $this->education_model->search_by_ID_or_name($e['MANAGERID']);
				echo $q[0]['EPNAME']."(".$e['MANAGERID'].")";
			?>
			<?php endif; ?>
			</td>
		</tr>
	<?php endforeach;?>
	</table>
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