<?php 
	if($_SERVER['REQUEST_METHOD'] === 'GET') { $e_id = $_GET['e_id']; }
	
	$query = $this->db->get_where('extracurricular',array('id'=>$e_id))->row_array();
	$EMPLOY = $this->db->get_where('FTCTL_EMPLOY',array('EPID'=>$query['EPID']))->row_array();
	$course_type = $this->db->get_where('course_type',
			array('id'=>$query['course_type']))->row_array()['type_name'];
	
	$leave = array('0'=>'公假','1'=>'自假');
	$self_assess = array(
			'1' => '自本課程獲益極大，將用之於醫院，預料貢獻會很大。',
			'2' => '頗有心得，頗可活用於醫院。',
			'3' => '整個課程值得推薦其他員工參加。',
			'4' => '本課程部分尚待改進，方可能受益。',
			'5' => '本課程根本不值得參加。'
	);
?>

<div class="span8 offset2">
	<a href="<?php echo base_url();?>extracurricular" class="label label-info">
		<i class="icon-repeat icon-white"></i>返回院外研習首頁
	</a>
	<a href="<?php echo base_url();?>extracurricular_auth" class="label label-info">
		<i class="icon-th-list icon-white"></i>返回待處裡案件
	</a>
	<h3>數位學習課程認證：</h3>
	<?php echo validation_errors(); //顯示表單驗證失敗訊息 ?>
	<table class="table table-striped table-bordered">
	<tr><td>送件人</td><td><?php echo $EMPLOY['EPNAME']." (".$query['EPID'].") "; ?></td></tr>
	<tr><td>課程主題</td><td><?php echo $query['TOPIC'];?></td></tr>
	<tr><td>日期</td><td><?php echo $query['DATE']; ?></td></tr>
	<tr><td>主辦單位</td><td><?php echo $query['HOST']; ?></td></tr>
	<tr><td>地點</td><td><?php echo $query['PLACE']; ?></td></tr>
	<tr><td>類別</td><td><?php echo $course_type; ?></td></tr>
	<tr><td>假別</td><td><?php echo $leave[$query['leave_type']]; ?></td></tr>
	<tr><td>自我評估</td><td><?php echo $self_assess[$query['selfassess']]; ?></td></tr>
	<tr><td>經由本課程您學到了什麼？<br>(至少陳述五項重點)</td><td><?php echo $query['learned']; ?></td></tr>
	<tr><td>心得/具體建議？<br>(至少撰寫150字以上)</td><td><?php echo $query['expsuggest']; ?></td></tr>
	<tr><td>認證文件</td><td><a href="<?php echo base_url(); ?>extracurricular_docs?e_id=<?php echo $query['id'];?>" class="btn">按此下載</a></td></tr>
	</table>
	<div style="text-align:center;">
　		<div style="margin:0 auto; width:800px;">
			<?php echo form_open('extracurricular_auth_course'); //顯示表單頭 ?>
　				評語：<input class="input-xxlarge" type="text" name="comment"> <br>
				<input type="radio" value="1" name="status"> 同意 
				<input type="radio" value="2" name="status"> 不同意 <br><br>
				<input type="submit" value="確認" class="btn">
				<input type="text" value="<?php echo $query['id'];?>" name="e_id" style="display: none">
			</form>
　		</div>
	</div>	
</div>