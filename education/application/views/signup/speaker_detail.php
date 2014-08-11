<div class="span6 offset3">
<h3>講師詳細資料：</h3>
<?php $speaker_data = $this->db->get_where('speaker',array('id'=>$_GET['s_id']))->row_array(); ?>
<table class="table table-striped">
	<tr><td>講師姓名：</td><td><?php echo $speaker_data['NAME']; ?></td></tr>	
	<tr><td>身分證字號：</td><td><?php echo $speaker_data['IDCARDNO']; ?></td></tr>
	<tr><td>現職工作：</td><td><?php echo $speaker_data['CURRENTWORK']; ?></td></tr>
	<tr><td>工作經歷：</td><td><?php echo $speaker_data['WORKEXP']; ?></td></tr>
	<tr><td>最高學歷：</td><td><?php echo $speaker_data['EDUCATION']; ?></td></tr>
</table>
<br>
<a href="<?php echo base_url();?>signup">返回報名系統</a> <br>
</div>
