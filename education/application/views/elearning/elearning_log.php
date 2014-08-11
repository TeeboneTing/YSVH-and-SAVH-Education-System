<div class="span8 offset2">
<a href="<?php echo base_url();?>elearning" class="label label-info">
	<i class="icon-repeat icon-white"></i>返回數位學習首頁
</a>
<h3>登錄數位學習時數</h3>
<?php echo $error;?>
<?php echo validation_errors(); //顯示表單驗證失敗訊息 ?>
<?php echo form_open_multipart('elearning_log'); //顯示表單頭 ?>
<table class="table table-bordered">
<tr>
	<td>*課程主題：</td>
	<td><input type="text" name="topic" class="input-xxlarge" value="<?php echo set_value("topic"); ?>"></td>
</tr>

<tr>
	<td>*日期：</td>
	<td><input name="date" type="text" id="datepicker1" class="input-medium" value="<?php echo set_value('date'); ?>"></td>
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
	<td>上傳認證文件<br> (jpg, pdf, zip) </td>
	<td><input type="file" name="userfile" size="40" /></td>
</tr>
</table>

<div class="span2 offset3">
	<input type="submit" class="btn btn-primary" value="登錄" />
	<input type="reset" class="btn" value="重設" />
</div>

<div class="span6">
<font color="red">*:必填(選)的欄位</font>
</div>
</form> 

</div>
