<?php 
	$query = $this->db->get_where('course_type',array('id'=>$_POST['course_type']));
	$branch = $this->education_model->search_by_ID_or_name($_SESSION['id'])[0]['BRANCHNO'];
	if($branch === "0") {$EPID = $query->row_array()['YSVH_EPID'];}
	elseif ($branch === "1") {$EPID = $query->row_array()['SAVH_EPID'];}
	$EPNAME = $this->db->get_where('ftctl_employ',array('EPID'=>$EPID))->row_array()['EPNAME']; 
	
?>

<div class="span8 offset2">
<?php if (isset($repeat)):?>
	<h3><font color="red">您先前已經登錄過此課程，無須重複登錄！</font></h3>
<?php else: ?>
	<h3>課程登錄成功！</h3>
<?php endif; ?>
<h4>詳細資料：</h4>
<table class="table table-striped table-bordered">
	<tr><td>課程主題：</td><td><?php echo $_POST['topic']; ?> </td></tr>
	<tr><td>日期：</td><td><?php echo $_POST["date"]; ?></td></tr>
	<tr><td>時間：</td><td><?php echo $_POST["timefrom"]." 至 ".$_POST["timeto"]; ?></td></tr>
	<tr><td>時數：</td><td><?php echo $_POST["credit"]; ?></td></tr>
	<tr><td>類別：</td><td><?php echo $this->education_model->get_course_type_by_ID($_POST["course_type"])['type_name']; ?></td></tr>
	<tr><td>上傳檔案名稱：</td><td><?php echo $filedata['orig_name']; ?></td></tr>
	<tr><td>認證負責人：</td><td><?php echo $EPNAME;?></td></tr>
</table> <br>

<a href="<?php echo base_url();?>elearning" class="label label-info">
	<i class="icon-repeat icon-white"></i>返回登錄系統
</a>

</div>
