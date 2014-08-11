<div class="span6 offset4">
	<h3>您好，<?php echo $_SESSION['username'];?>！</h3>
	請在此留下您的E-Mail<br>
	以便作為日後通知上課訊息使用。<br><br>
	<?php echo validation_errors(); //顯示表單驗證失敗訊息 ?>
	<?php echo form_open('put_email'); //顯示表單頭 ?>
	<?php $email = $this->db->get_where('ftctl_employ',array('EPID'=>$_SESSION['id']))->row_array()['EMAIL'];?>
		請輸入您的E-Mail：<input type="text" name="email" value="<?php if(!validation_errors()) {echo $email;} else {echo set_value('email');} ?>" /> 
		<input class="btn" type="submit" value="確定" />
	</form>
</div>