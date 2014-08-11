<?php if ($_SERVER['REQUEST_METHOD'] === 'GET'): ?>
<?php 
	$query = $this->db->get_where('signup',array('course_id'=>$_GET['c_id']))->result_array();
	$emails = array();
	foreach ($query as $e){
		$item = $this->db->get_where('ftctl_employ',array('IDCARDNO'=>$e['IDCARDNO']))->row_array()['EMAIL'];
		array_push($emails, $item);
	}
	$course = $this->db->get_where('course',array('id'=>$_GET['c_id']))->row_array();
?>
<h3 align="center">課程修改發送E-mail通知</h3>
<form name="send_email" action="" method="post">
<div class="span7 offset3">
	<table>
		<tr><td>主旨：</td><td><input class="input-xxlarge" type="text" name="subject" value="課程異動"></td></tr>
		<tr><td>收件人：<br>(逗號做分隔)</td><td><input class="input-xxlarge" type="text" name="to" 
			value="<?php foreach($emails as $e) {echo $e.",";}?>">
		</td></tr>
		<tr><td>內文：</td><td><textarea rows="10" name="content" class="input-xxlarge">
親愛的收件者：

我們的課程資訊有所修改，請至我們的課程網站觀看。
課程名稱：<?php echo $course['TOPIC'];?>

課程詳細資訊：<?php echo base_url()."course_detail?c_id=".$_GET['c_id']; ?>

謝謝。

台北榮總蘇澳暨員山榮民醫院
教育訓練系統
</textarea></td></tr>
	</table>
<div style="text-align:center;">
	<input type="submit" class="btn" value="發送">
</div>
</div>
</form>
<?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
<h3 align="center">課程修改發送E-mail通知</h3>
<div style="text-align:center;">
	<h4> 發送結果：<?php echo $send_ret; ?> </h4>
</div>
<?php endif;?>