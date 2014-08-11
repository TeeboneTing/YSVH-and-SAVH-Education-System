<div class="span8 offset2">
<a href="<?php echo base_url();?>extracurricular" class="label label-info">
<i class="icon-repeat icon-white"></i>返回院外研習首頁
</a>
<h3>登錄院外研習</h3>
<?php echo $error;?>
<?php echo validation_errors(); //顯示表單驗證失敗訊息 ?>
<?php echo form_open_multipart('extracurricular_log'); //顯示表單頭 ?>
<table class="table table-bordered">
<tr>
	<td>*課程主題：</td>
	<td><input type="text" name="topic" class="input-xxlarge" value="<?php echo set_value("topic"); ?>"></td>
</tr>

<tr>
	<td>*日期：</td>
	<td><input name="date" type="text" id="datepicker1" class="input-medium" 
				value="<?php echo set_value('date'); ?>"></td>
</tr>

<tr>
	<td>*主辦單位</td>
	<td><input name="host" type="text" class="input-xxlarge" value="<?php echo set_value('host'); ?>"></td>
</tr>

<tr>
	<td>*地點：</td>
	<td><input name="place" type="text" class="input-large" value="<?php echo set_value('place'); ?>"></td> 
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
	<td>*假別</td>
	<td>
		<select class="input-medium" name="leave_type">
			<option value="" <?php echo set_select('leave_type', ''); ?> ></option>
			<option value="0" <?php echo set_select('leave_type', '0'); ?> >公假</option>
			<option value="1" <?php echo set_select('leave_type', '1'); ?> >自假</option>
		</select>
	</td>	
</tr>

<tr>
	<td>*自我評估</td>
	<td>
		<label class="radio">
			<input type="radio" name="selfassess" value="1" <?php echo set_radio('selfassess', '1'); ?> >
			自本課程獲益極大，將用之於醫院，預料貢獻會很大。
		</label>
		
		<label class="radio">
			<input type="radio" name="selfassess" value="2" <?php echo set_radio('selfassess', '2'); ?> >
			頗有心得，頗可活用於醫院。
		</label>
		
		<label class="radio">
			<input type="radio" name="selfassess" value="3" <?php echo set_radio('selfassess', '3'); ?> >
			整個課程值得推薦其他員工參加。
		</label>
		
		<label class="radio">
			<input type="radio" name="selfassess" value="4" <?php echo set_radio('selfassess', '4'); ?> >
			本課程部分尚待改進，方可能受益。
		</label>
		
		<label class="radio">
			<input type="radio" name="selfassess" value="5" <?php echo set_radio('selfassess', '5'); ?> >
			本課程根本不值得參加。
		</label>
	
	</td>
</tr>

<tr>
	<td>經由本課程您學到了什麼？<br>(至少陳述五項重點)</td>
	<td><textarea name="learned" class="input-xlarge" rows="5"><?php echo set_value('learned'); ?></textarea></td>
</tr>

<tr>
	<td>心得/具體建議？<br>(自假者不必填寫)</td>
	<td><textarea name="expsuggest" class="input-xlarge" rows="5"><?php echo set_value('expsuggest'); ?></textarea></td>
</tr>

<tr>
	<td>上傳公文檔案<br> (jpg, pdf, zip) </td>
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
