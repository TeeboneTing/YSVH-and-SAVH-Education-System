<div class="span6 offset3">
<h3>請選擇要設定的功能</h3>
<table class="table table-striped table-bordered">
<thead><tr> <th><b>功能</b></th> <th><b>設定</b></th> </tr></thead>
<tbody>
	<tr> <td>修改登入密碼</td> <td><a href="<?php echo base_url(); ?>password" class="btn">設定</a></td> </tr>

	<?php if ($this->education_model->is_admin($_SESSION['id']) ===5):?>
	<tr> 
		<td>修改使用者權限(限系統管理者)</td>	
		<td><a href="<?php echo base_url(); ?>personnel/update_employ" class="btn">設定</a></td>
	</tr>
	<?php endif;?>
	
	<?php if ($this->education_model->is_admin($_SESSION['id']) ===5):?>
	<tr> 
		<td>修改課程類別(限系統管理者)</td> 
		<td> <a href="<?php echo base_url(); ?>course_type_settings" class="btn">設定</a> </td>
	</tr>
	<?php endif;?>
	
	<?php if ($this->education_model->is_admin($_SESSION['id']) ===5):?>
	<tr> 
		<td>修改簽到退時間(限系統管理者)</td> 
		<td> <a href="<?php echo base_url(); ?>signin_settings" class="btn">設定</a> </td>
	</tr>
	<?php endif;?>

</tbody>
</table>
</div>