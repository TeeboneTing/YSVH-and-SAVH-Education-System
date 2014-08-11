<div class="span10 offset1">
<h3>課程詳細資料：</h3>
	<form name="select_course" method="post" action="">
	<table class="table table-striped table-bordered">
<?php
if((int)$_GET['g_id'] > 0):
	// 顯示群組課程
	$group_data = $this->db->get_where('group_course',array('id'=> $_GET['g_id']))->row_array();
	$this->db->select('*');
	$this->db->from('course');
	$this->db->where('GROUP_TOPIC_ID',$_GET['g_id']);
	$this->db->order_by("GROUP_ORDER", "asc");
	$small_topics = $this->db->get()->result_array();
?>
		<tr><td colspan='9'><a href="course_detail?g_id=<?php echo $_GET['g_id']; ?>"> <?php echo $group_data['group_topic']; ?> </a></td></tr>
		<tr><td colspan='9'> 日期： <?php echo $group_data['DATEFROM']."~".$group_data['DATETO'];?></td></tr>
		<tr>
			<td><input type="checkbox"  id="clickAll"> 全選 </td><td>節數</td><td>時間</td>
			<td>時數</td><td>小節主題</td><td>講師</td><td>地點</td><td>假別</td><td>目前報名人數/上限</td>
		</tr>
		<?php foreach($small_topics as $e): ?>
		<tr>
			<td>fdghdfgh
				<?php 
					$date = explode("/", $e['DATETO']); //西元年轉換
					$endday = strtotime(strval((int)$date[0]+1911)."-".$date[1]."-".strval((int)$date[2]+1));
					$man_count = $this->db->get_where('signup',array('course_id'=>$e['id']))->num_rows();
					//判斷課程結束日期大於現在 以及人數上限以內，才可以報名
					if($endday > time() && (!$e['UPLIMIT'] || $man_count < $e['UPLIMIT'])): 
				?>
					<input type="checkbox" id="check" name="id_<?php echo $e['id']?>" value="<?php echo $e['id']?>">
				<?php endif;?>
			</td>
			<td><?php echo $e['GROUP_ORDER'];?></td>
			<td><?php echo $e['TIMEFROM']."~".$e['TIMETO'];?></td>
			<td><?php echo $e['CREDIT']; ?></td>
			<td><a href="course_detail?c_id=<?php echo $e['id']; ?>"><?php echo $e['TOPIC'];?></a></td>
			<?php $speaker = $this->db->get_where('speaker',array('id' => $e['SPEAKER_ID']))->row_array()['NAME']; ?>
			<td><a href="speaker_detail?s_id=<?php echo $e['SPEAKER_ID']; ?>"><?php echo $speaker;?></a></td>
			<td><?php echo $e['PLACE']; ?></td>
			<td><select name="leavetype_<?php echo $e['id']?>" class="input-small"><option value="0">公假</option><option value="1">自假</option></select></td>
			<td>
			<?php 
				if($e['UPLIMIT'])
					{echo $man_count."/".$e['UPLIMIT'];}
				else 
					{echo "不限";}
			?>
			</td>
		</tr>
		<?php endforeach;?>

<?php 
else: //顯示個別課程
	$topic_data = $this->db->get_where('course',array('id'=>$_GET['c_id']))->row_array();
?>
		<tr>
			<td>選取</td><td>主題</td><td>日期</td><td>時間</td>
			<td>時數</td><td>講師</td><td>地點</td><td>假別</td><td>目前報名人數/上限</td>
		</tr>
		
		<tr>
			<td>
				<?php 
					$date = explode("/", $topic_data['DATETO']); //西元年轉換
					$endday = strtotime(strval((int)$date[0]+1911)."-".$date[1]."-".strval((int)$date[2]+1));
					$man_count = $this->db->get_where('signup',array('course_id'=>$topic_data['id']))->num_rows();
					//判斷課程結束日期大於現在時，以及人數沒有超過上限，才可以報名
					if($endday > time() && (!$topic_data['UPLIMIT'] || $man_count < $topic_data['UPLIMIT'])): 
				?>
					<input type="checkbox" id="check" name="id_<?php echo $topic_data['id']; ?>" value="<?php echo $topic_data['id']?>" checked>
				<?php endif;?>
			</td>
			<td><a href="course_detail?c_id=<?php echo $topic_data['id']; ?>"><?php echo $topic_data['TOPIC']; ?></a></td>
			<td><?php echo $topic_data['DATEFROM']."~".$topic_data['DATETO']; ?></td>
			<td><?php echo $topic_data['TIMEFROM']."~".$topic_data['TIMETO']; ?></td>
			<td><?php echo $topic_data['CREDIT']; ?></td>
			<?php $speaker = $this->db->get_where('speaker',array('id' => $topic_data['SPEAKER_ID']))->row_array()['NAME']; ?>
			<td><a href="speaker_detail?s_id=<?php echo $topic_data['SPEAKER_ID']; ?>"><?php echo $speaker; ?></a></td>
			<td><?php echo $topic_data['PLACE']; ?></td>
			<td><select name="leavetype_<?php echo $topic_data['id']; ?>" class="input-small"><option value="0">公假</option><option value="1">自假</option></select></td>
			<td>
			<?php 
				if($topic_data['UPLIMIT'])
					{echo $man_count."/".$topic_data['UPLIMIT'];}
				else 
					{echo "不限";}
			?>
			</td>
		</tr>		
<?php endif; ?>
	
	</table>
	<div class="span4 offset3">
		<input type="submit" class="btn btn-primary" value="我要報名">
		<a href="signup" class="btn">回課程列表</a>
	</div>
	</form>
</div>
