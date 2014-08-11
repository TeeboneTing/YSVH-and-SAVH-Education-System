<div class="span12">
	<h3>請選擇課程：</h3>
	<table class="table table-striped">
	<tr>
	    <td><b>課程類型</b></td> <td><b>課程主題</b></td> <td><b>講師</b></td> <td><b>日期</b></td> 
		<td><b>節數</b></td> <td><b>時數</b></td> <td><b>地點</b></td> <td><b>點名(請洽開課管理者)</b></td>
	</tr>
	<?php
		//只顯示未來的課程
		$today = idate("Y",time())-1911;
		$today = strval($today)."/".date("m/d",time());
		$this->db->order_by("DATEFROM", "desc");
		$all_course_data = $this->db->get_where('course',array('DATETO >='=> $today))->result_array();
		
		foreach ($all_course_data as $e):
			if($e['TOPIC_TYPE'] > 0){
				$group_topic = $this->db->get_where('group_course',
								array('id'=> $e['GROUP_TOPIC_ID'])
								)->row_array()['group_topic'];
			}
	?>		
				<tr>
					<td><?php if($e['TOPIC_TYPE'] > 0) {echo "群組";} else {echo "個別";}?></td>
					<td><a href="<?php echo base_url();?>course_detail?c_id=<?php echo $e['id']; ?>">
							<?php if($e['TOPIC_TYPE'] > 0){echo $group_topic.":";} echo $e['TOPIC']; ?></a></td>
					<?php $speaker = $this->db->get_where('speaker',array('id' => $e['SPEAKER_ID']))->row_array()['NAME'];?>
					<td><a href="<?php echo base_url();?>speaker_detail?s_id=<?php echo $e['SPEAKER_ID']; ?>"><?php echo $speaker; ?></a></td>
					<td><?php echo $e['DATEFROM']."~".$e['DATETO']; ?></td>
					<td><?php if($e['TOPIC_TYPE'] > 0) {echo $e['GROUP_ORDER'];}?></td>
					<td><?php echo $e['CREDIT']; ?></td>
					<td><?php echo $e['PLACE']?></td>
					<td>
						<?php if($e['MANAGERID'] === $_SESSION['id'] || $this->education_model->is_admin($_SESSION['id']) === 5):?>
							<a class="btn" href="<?php echo base_url();?>signin_course?c_id=<?php echo $e['id']?>">點名</a>
						<?php else: 
							$manager = $this->education_model->search_by_ID_or_name($e['MANAGERID'])[0];
							echo $manager['EPNAME']."(".$e['MANAGERID'].")";
						endif; ?>
					</td>
				</tr>
		<?php endforeach;?>