<div class="span8 offset2">
<br>
<?php echo validation_errors(); //顯示表單驗證失敗訊息 ?>
<?php echo form_open('new_course'); //顯示表單頭 ?>
<table class="table table-bordered">
<tr>
	<td>**請選擇課程形式為個別或群組：</td>
	<td>
		<select name="topic_type" class="input-small">
			<option value="0" <?php echo set_select('topic_type', '0'); ?> >個別</option>
			<option value="1" <?php echo set_select('topic_type', '1'); ?> >群組</option>
		</select>
	</td>
</tr>

<tr>
	<td>*課程主題：</td>
	<td>
		<input name="group_topic_show" value="<?php if(isset($group_topic)) echo $group_topic; ?>" style="display: none">
		<div id="group_topic_content" style="display: inline"><?php if(isset($group_topic)) echo $group_topic; ?></div>
		<input type="text" name="topic" value="<?php echo set_value('topic'); ?>" /> 
		<button class="btn" id="modify_group_topic" data-toggle="modal" data-target="#myModal" title="修改群組主題" style="display:none"><i class="icon-cog"></i></button>
	</td>
</tr>

<tr id="group_order" style="display: none">
	<td>*節數：</td>
	<td>
		第 <input type="text" name="group_order" class="input-mini" value="<?php echo set_value('group_order'); ?>" />節課
		(請填阿拉伯數字)
	</td>
</tr>

<tr>
	<td>*講師：</td>
	<td>
		<input type="text" id="speaker" name="speaker" placeholder="" value="<?php echo set_value('speaker'); ?>" />
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
	<td><input type="text" name="place" value="<?php echo set_value('place'); ?>" /></td>
</tr>

<tr>
	<td>*日期：</td>
	<td>
		<input name="from" type="text" id="datepicker1" class="input-small" value="<?php echo set_value('from'); ?>" > 至 
		<input name="to" type="text" id="datepicker2" class="input-small" value="<?php echo set_value('to'); ?>">
	</td>
</tr>

<tr>
	<td>*時間：</td>
	<td>
		<input name="timefrom" type="text" class="input-small" value="<?php echo set_value('timefrom'); ?>" > 至
		<input name="timeto" type="text" class="input-small" value="<?php echo set_value('timeto'); ?>" > 
		(請填入 小時:分鐘 例：15:20)
	</td>
</tr>

<tr>
	<td>*時數：</td>
	<td><input name="credit" type="text" class="input-small" value="<?php echo set_value('credit'); ?>"></td> 
</tr>

<tr>
	<td>*類別：</td>
	<td>
		<select class="input-medium" name="course_type">
		<option value=""></option> <!-- 預設選項值為空值 -->
		<?php //從資料庫撈類別名單 
		$course_type = $this->education_model->get_all_course_type();
		foreach ($course_type as $e)
			{echo "<option value='".$e['id']."' ".set_select('course_type', $e['id'])." >".$e['type_name']."</option>";}
		?>
		</select>
	</td>
</tr>

<tr>
	<td>*課程類型：</td>
	<td>
		<select class="input-small" name="class_type">
			<option value="0" <?php echo set_select('class_type', '0'); ?> >實體</option>
			<option value="1" <?php echo set_select('class_type', '1'); ?> >數位</option>
		</select>
	</td> 
</tr>

<tr>
	<td>人數上限：</td>
	<td><input type="text" class="input-small" name="limit" value="<?php echo set_value('limit'); ?>"/> </td>
</tr>

<tr>
	<td>備註：</td>
	<td><textarea class="input-xlarge" name="note" rows="2" ></textarea></td>
</tr>

<tr>
	<td>簡介：</td>
	<td><textarea class="input-xlarge" name="introduction" rows="5" ></textarea></td>
</tr>
</table>

<div class="span2 offset3">
	<input type="submit" class="btn btn-primary" value="新增" />
	<input type="reset" class="btn" value="重設" />
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
    		<td><input type="text" id="group_topic" class="input-large" value="<?php if(isset($group_topic)) echo $group_topic; ?>" /> </td>
    	</tr>
    	<tr>
    		<td>日期：</td>
    		<td> 
    			<input type="text" class="input-small" id="datepicker3" value="<?php echo set_value('from'); ?>"> 至 
    			<input type="text" class="input-small" id="datepicker4" value="<?php echo set_value('to'); ?>">
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
  <table class="table">
    	<tr><td>講師姓名：</td><td><input type="text" name="speaker_name" class="input-large blah" /></td></tr>
    	<tr><td>身分證字號：</td><td><input type="text" name="speaker_idcardno" class="input-large blah"/></td></tr>
    	<tr><td>最高學歷：</td><td><input type="text" name="speaker_education" class="input-large blah" /></td></tr>
    	<tr><td>現職工作：</td><td><input type="text" name="speaker_currentwork" class="input-large blah" /></td></tr>
    	<tr><td>工作經歷：</td><td><textarea class="input-xlarge blah" name="speaker_workexp" rows="5" ></textarea></td></tr>
  </table>
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" id="save_speaker">新增</button>
    <button class="btn" data-dismiss="modal" aria-hidden="true">取消</button>
  </div>
</div>