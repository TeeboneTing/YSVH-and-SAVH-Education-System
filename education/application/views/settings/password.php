<div class="span6 offset3">
<h3>密碼修改</h3>
<?php if($_SERVER['REQUEST_METHOD'] === "POST") { echo $message; } ?>
<form action="" method="POST" name="change_password">

	<table class="table table-striped table-bordered">
	<tr>
		<td>請輸入舊密碼</td>
		<td>
			<input name="old_pwd" type="password" class="input-medium">
		</td>
	</tr>

	<tr>
		<td>請輸入新密碼</td>
		<td>
			<input name="new_pwd" type="password" class="input-medium">
		</td>
	</tr>

	<tr>
		<td>再輸入一次新密碼</td>
		<td>
			<input name="new_pwd_again" type="password" class="input-medium">
		</td>
	</tr>

	<tr> <td colspan="2" style="text-align:center;">
		<input type="submit" class="btn" value="確定">  
		<input type="reset" class="btn" value="重設">
	</td> </tr>
	</table>

</form>
</div>