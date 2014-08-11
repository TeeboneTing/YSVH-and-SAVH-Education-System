<?php 
	// 撈課程資料  according to c_id
	$course_data = $this->db->get_where('course',array('id' => $_GET['c_id']))->row_array();
	$speaker_data = $this->db->get_where('speaker',array('id' => $course_data['SPEAKER_ID']))->row_array();
	if($course_data['TOPIC_TYPE'] === "1")
		{$group_data = $this->db->get_where('group_course',array('id' => $course_data['GROUP_TOPIC_ID']))->row_array();}
	
?>

<div class="span8 offset2">
<br>
<?php echo validation_errors(); //顯示表單驗證失敗訊息 ?>
<?php echo form_open('modify_course'); //顯示表單頭 ?>
<table class="table table-bordered">
<tr>
	<td>**請選擇課程形式為個別或群組：</td>
	<td>
		<select name="topic_type" class="input-small" readonly>
			<option value="0" <?php if($course_data['TOPIC_TYPE'] === "0") {echo "selected";} ?> >個別</option>
			<option value="1" <?php if($course_data['TOPIC_TYPE'] === "1") {echo "selected";} ?> >群組</option>
		</select>
		
	</td>
</tr>

<tr>
	<td>*課程主題：</td>
	<td>
		<input name="group_topic_show" value="<?php if(isset($group_data)) {echo $group_data['group_topic'];} ?>" style="display: none">
		<div id="group_topic_content" style="display: inline"><?php if(isset($group_data)) {echo $group_data['group_topic'];} ?></div>
		<input type="text" name="topic" value="<?php echo $course_data['TOPIC']; ?>" /> 
		<input tyle="text" name="c_id" value="<?php echo $_GET['c_id']; ?>" style="display: none">
		<button class="btn" id="modify_group_topic" data-toggle="modal" data-target="#myModal" title="修改群組主題" style="display:none"
			<?php if(isset($group_data)) {echo "disabled";} ?> >
			<i class="icon-cog"></i>
		</button>
	</td>
</tr>

<tr id="group_order" style="display: none">
	<td>*節數：</td>
	<td>
		第 <input type="text" name="group_order" class="input-mini" value="<?php echo $course_data['GROUP_ORDER']; ?>" />節課
		(請填阿拉伯數字)
	</td>
</tr>

<tr>
	<td>*講師：</td>
	<td>
		<input type="text" id="speaker" name="speaker"
				value="<?php echo $speaker_data['NAME']." ".$speaker_data['IDCARDNO']." ".$speaker_data['CURRENTWORK']; ?>" />
		<img id="loading" style="display: none" src="<?php echo base_url();?>assets/img/ajax-loader2.gif" />
		<div id="new_speaker_container" style="display: inline">
			<button class="btn" id="new_speaker" data-toggle="modal" data-target="#addSpeaker" title="新增講師"><i class="icon-plus"></i></button>
			 資料庫沒有講師資料，請新增講師
		</div>
		<br>(請輸入姓名或身分證字號搜尋，或輸入姓名+身分證字號新增講師)
	</td>
</tr>

<tr>
	<td>*地點：</td>
	<td><input type="text" name="place" value="<?php echo $course_data['PLACE']; ?>" /></td>
</tr>

<tr>
	<td>*日期：</td>
	<td>
		<input name="from" type="text" id="datepicker1" class="input-small" value="<?php echo $course_data['DATEFROM']; ?>"
			<?php if(isset($group_data)) {echo "readonly";} ?> > 至 
		<input name="to" type="text" id="datepicker2" class="input-small" value="<?php echo $course_data['DATETO']; ?>"
			<?php if(isset($group_data)) {echo "readonly";} ?> >
	</td>
</tr>

<tr>
	<td>*時間：</td>
	<td>
		<input name="timefrom" type="text" class="input-small" value="<?php echo $course_data['TIMEFROM']; ?>" > 至
		<input name="timeto" type="text" class="input-small" value="<?php echo $course_data['TIMETO']; ?>" > 
		(請填入 小時:分鐘 例：15:20)
	</td>
</tr>

<tr>
	<td>*時數：</td>
	<td><input name="credit" type="text" class="input-small" value="<?php echo $course_data['CREDIT']; ?>"></td> 
</tr>

<tr>
	<td>*類別：</td>
	<td>
		<select class="input-medium" name="course_type">
		<option value=""></option> <!-- 預設選項值為空值 -->
		<?php //從資料庫撈類別名單 
		$course_type = $this->education_model->get_all_course_type();
		foreach ($course_type as $e): ?>
			<option value='<?php echo $e['id']?>' <?php if($e['id'] === $course_data['COURSE_TYPE']) {echo "selected";} ?>>
				<?php echo $e['type_name']; ?>
			</option>
		<?php endforeach;?>
		</select>
	</td>
</tr>

<tr>
	<td>*課程類型：</td>
	<td>
		<select class="input-small" name="class_type">
			<option value="0" <?php if($course_data['CLASS_TYPE'] === "0") {echo "selected";} ?> >實體</option>
			<option value="1" <?php if($course_data['CLASS_TYPE'] === "1") {echo "selected";} ?> >數位</option>
		</select>
	</td> 
</tr>

<tr>
	<td>人數上限：</td>
	<td><input type="text" class="input-small" name="limit"
				value="<?php if($course_data['UPLIMIT']) {echo $course_data['UPLIMIT'];} ?>"/> </td>
</tr>

<tr>
	<td>備註：</td>
	<td><textarea class="input-xlarge" name="note" rows="2" ><?php if($course_data['NOTE']) {echo $course_data['NOTE'];} ?></textarea></td>
</tr>

<tr>
	<td>簡介：</td>
	<td><textarea class="input-xlarge" name="introduction" rows="5" ><?php if($course_data['INTRODUCTION']) {echo $course_data['INTRODUCTION'];} ?></textarea></td>
</tr>
</table>

<div class="span4 offset2">
	<?php if($course_data['TOPIC_TYPE'] === "1"): ?>
		<a class="btn btn-info" href="<?php echo base_url(); ?>modify_course?g_id=<?php echo $course_data['GROUP_TOPIC_ID'];?>">
			修改群組主題與日期
		</a>
	<?php endif; ?>
	<input type="submit" class="btn btn-primary" value="修改" />
	<a class="btn btn-danger delete_course"  href="<?php echo base_url(); ?>delete_course?c_id=<?php echo $_GET['c_id']; ?>">
		刪除課程
	</a>
</div>

<div class="span6">
<font color="red">*:必填(選)的欄位</font> <br>
**:必須先選擇您的課程是群組(一系列)的課程，還是單一個別課程<br>
</div>
</form> 

</div>

<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">編輯群組課程主題</h3>
  </div>
  <div class="modal-body">
    	<table class="table">
    	<tr>
    		<td>請輸入群組主題：</td> 
    		<td><input type="text" id="group_topic" class="input-large" 
    					value="<?php if(isset($group_data)) echo $group_data['group_topic']; ?>" disabled/> </td>
    	</tr>
    	<tr>
    		<td>日期：</td>
    		<td> 
    			<input type="text" class="input-small" id="datepicker3" value="<?php if(isset($group_data)) echo $group_data['DATEFROM']; ?>" disabled> 至 
    			<input type="text" class="input-small" id="datepicker4" value="<?php if(isset($group_data)) echo $group_data['DATETO']; ?>" disabled>
    		</td>
    	</tr>
    	<tr><td colspan="2">(同一門群組課，請填相同課名、開始及結束日期。)</td></tr>
    	</table>
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" id="group_topic_yes">確定</button>
    <button class="btn" data-dismiss="modal" aria-hidden="true" id="group_topic_no">取消</button>
  </div>
</div>

<div id="addSpeaker" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">新增講師資料</h3>
  </div>
  <div class="modal-body">
    	講師姓名：<input type="text" name="speaker_name" class="input-large blah" /><br>
    	身分證字號： <input type="text" name="speaker_idcardno" class="input-large blah"/> <br>
    	最高學歷： <input type="text" name="speaker_education" class="input-large blah" /> <br>
    	現職工作：<input type="text" name="speaker_currentwork" class="input-large blah" /> <br>
    	工作經歷：<br>
    	<textarea class="input-xlarge blah" name="speaker_workexp" rows="5" ></textarea>
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" id="save_speaker">修改</button>
    <button class="btn" data-dismiss="modal" aria-hidden="true">取消</button>
  </div>
</div>