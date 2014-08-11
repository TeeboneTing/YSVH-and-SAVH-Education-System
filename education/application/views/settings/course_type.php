<div class="span8 offset2">
<h3>課程類別修改/新增</h3> 
<p align="right">
	<a href="#last" class="label label-info new_course_type">
		<i class="icon-plus-sign icon-white"></i>新增類別
	</a>
</p>
<?php if($_SERVER['REQUEST_METHOD'] === "POST") { echo $message; } ?>
<form action="" method="POST" name="course_type">

	<table class="table table-striped table-bordered">
	<tr>
		<td>類別名稱</td> <td>類別群組</td> <td>員山院區負責人</td> <td>蘇澳院區負責人</td> <td>刪除</td>
	</tr>

	<?php 
		$course_type = $this->db->get('course_type')->result_array();
		foreach ($course_type as $e):
	?>
	<tr>
		<td><input type="text" name="<?php echo $e['id']."-type_name"?>" class="input-medium" value="<?php echo $e['type_name']; ?>"></td>
		<td><input type="text" name="<?php echo $e['id']."-GROUP"?>" class="input-medium" value="<?php echo $e['GROUP']; ?>"></td>
		<td><input type="text" name="<?php echo $e['id']."-YSVH_EPID"?>" class="input-medium" value="<?php echo $e['YSVH_EPID']; ?>"></td>
		<td><input type="text" name="<?php echo $e['id']."-SAVH_EPID"?>" class="input-medium" value="<?php echo $e['SAVH_EPID']; ?>"></td>
		<td>  
			<button class="label label-important remove_course_type" id="<?php echo $e['id'];?>">
				<i class="icon-minus-sign icon-white"></i>
			</button> 
		</td>
	</tr>
	<?php endforeach; ?>

	<tr> <td colspan="5" style="text-align:center;">
		<input type="submit" class="btn" value="確定">  
		<input type="reset" class="btn" value="重設">
	</td> </tr>
	
	</table>
	<div id="last"></div>
</form>

<p align="right">
	<a href="#last" class="label label-info new_course_type">
		<i class="icon-plus-sign icon-white"></i>新增類別
	</a>
</p>

</div>