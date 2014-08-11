<?php 
	if($_SERVER['REQUEST_METHOD'] === 'GET') { $e_id = $_GET['e_id']; }
	
	$query = $this->db->get_where('elearning',array('id'=>$e_id))->row_array();
	$EMPLOY = $this->db->get_where('FTCTL_EMPLOY',array('EPID'=>$query['EPID']))->row_array();
	$course_type = $this->db->get_where('course_type',
			array('id'=>$query['COURSE_TYPE']))->row_array()['type_name'];
?>

<div class="span8 offset2">
	<a href="<?php echo base_url();?>elearning" class="label label-info">
		<i class="icon-repeat icon-white"></i>返回數位學習首頁
	</a>
	<a href="<?php echo base_url();?>elearning_auth" class="label label-info">
		<i class="icon-th-list icon-white"></i>返回待處裡案件
	</a>
	<h3>數位學習課程認證：</h3>
	<?php echo validation_errors(); //顯示表單驗證失敗訊息 ?>
	<table class="table table-striped table-bordered">
	<tr><td>送件人</td><td><?php echo $EMPLOY['EPNAME']." (".$query['EPID'].") "; ?></td></tr>
	<tr><td>課程主題</td><td><?php echo $query['TOPIC'];?></td></tr>
	<tr><td>日期</td><td><?php echo $query['DATE']; ?></td></tr>
	<tr><td>時間</td><td><?php echo $query['TIMEFROM']."~".$query['TIMETO']; ?></td></tr>
	<tr><td>時數</td><td><?php echo $query['CREDIT']; ?></td></tr>
	<tr><td>類別</td><td><?php echo $course_type; ?></td></tr>
	<tr><td>認證文件</td><td><a href="<?php echo base_url(); ?>elearning_docs?e_id=<?php echo $query['id'];?>" class="btn">按此下載</a></td></tr>
	</table>
	<div style="text-align:center;">
　		<div style="margin:0 auto; width:800px;">
			<?php echo form_open('elearning_auth_course'); //顯示表單頭 ?>
　				評語：<input class="input-xxlarge" type="text" name="comment"> <br>
				<input type="radio" value="1" name="status"> 同意 
				<input type="radio" value="2" name="status"> 不同意 <br><br>
				<input type="submit" value="確認" class="btn">
				<input type="text" value="<?php echo $query['id'];?>" name="e_id" style="display: none">
			</form>
　		</div>
	</div>	
</div>