<?php // 取得群組課程資料
	$group_data = $this->db->get_where('group_course',array('id'=>$_GET['g_id']))->row_array();
	$course_datas = $this->db->get_where('course',array('GROUP_TOPIC_ID'=>$group_data['id']))->result_array(); 

?>

<div class="span10 offset1">
	<h3>群組課程資訊：</h3>
	<table class="table table-striped table-bordered">
	<tr>
		<td colspan='8'>
			<input type="text" name="g_id" value="<?php echo $_GET['g_id']; ?>" style="display:none" />
			<button class="btn" data-toggle="modal" data-target="#myModal" title="修改群組主題">修改</button>
			群組主題：<div id="group_container" style="display:inline;"><?php echo $group_data['group_topic']; ?></div> 
		</td>
	</tr>
	<tr>
		<td colspan='8'>
			<button class="btn" data-toggle="modal" data-target="#myModal" title="修改群組日期">修改</button>
			日期：<div id="group_date_container" style="display:inline;"><?php echo $group_data['DATEFROM']."~".$group_data['DATETO']; ?></div> 
		</td>
	</tr>
	<tr>
		<td><input type="checkbox"  id="clickAll"> 全選</td><td>節數</td><td>時間</td><td>小節主題</td>
		<td>講師</td><td>地點</td><td>時數</td><td>修改</td>
	</tr>
	<?php 
	$all_cid ="";	
	foreach ($course_datas as $e): 
		$all_cid = $all_cid.$e['id'].","; 
	?>
		<tr>
			<td><input type="checkbox" id="check" name="id_<?php echo $e['id']?>" value="<?php echo $e['id']?>"></td>
			<td><?php echo $e['GROUP_ORDER']; ?></td>
			<td><?php echo $e['TIMEFROM']."~".$e['TIMETO']; ?></td>
			<td><?php echo $e['TOPIC']; ?></td>
			<?php $speaker = $this->db->get_where('speaker',array('id' => $e['SPEAKER_ID']))->row_array()['NAME']; ?>
			<td><a href="speaker_detail?s_id=<?php echo $e['SPEAKER_ID']; ?>"><?php echo $speaker;?></a></td>
			<td><?php echo $e['PLACE']; ?></td>
			<td><?php echo $e['CREDIT']; ?></td>
			<td><a href="<?php base_url();?>modify_course?c_id=<?php echo $e['id']; ?>" class="btn btn-danger">修改小節</a></td>
		</tr>
	<?php endforeach; ?>
	</table>
	<div class="span4 offset3">
		<a class="btn btn-warning delete_course" href="" id="delete_selected"><i class="icon-trash icon-white"></i>刪除選取小節</a> 
		<a class="btn btn-danger delete_course" href="<?php echo base_url(); ?>delete_course?c_id=<?php echo rtrim($all_cid,","); ?>">
			<i class="icon-trash icon-white"></i>刪除整個群組
		</a>
	</div>
</div>

<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">修改群組課程主題</h3>
  </div>
  <div class="modal-body">
    	<table class="table">
    	<tr>
    		<td>請輸入群組主題：</td> 
    		<td><input type="text" id="group_topic" class="input-large" 
    					value="<?php echo $group_data['group_topic']; ?>" /> </td>
    	</tr>
    	<tr>
    		<td>日期：</td>
    		<td> 
    			<input type="text" class="input-small" id="datepicker3" value="<?php echo $group_data['DATEFROM']; ?>" > 至 
    			<input type="text" class="input-small" id="datepicker4" value="<?php echo $group_data['DATETO']; ?>" >
    		</td>
    	</tr>
    	</table>
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" id="modify_group_course">修改</button>
    <button class="btn" data-dismiss="modal" aria-hidden="true" >取消</button>
  </div>
</div>