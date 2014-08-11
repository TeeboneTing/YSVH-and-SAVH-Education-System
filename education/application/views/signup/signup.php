<div class="span6" >
	<a href="put_email" class="label label-info"><i class="icon-envelope icon-white"></i>修改Email</a> 
	<a href="my_course" class="label label-info"><i class="icon-ok icon-white"></i>查詢已報名課程</a>
	<a href="my_old_course" class="label label-info"><i class="icon-time icon-white"></i>查詢我已參加過的課程</a>
	<a href="old_course" class="label label-info"><i class="icon-search icon-white"></i>查詢舊課程</a>
</div>
<div class="span12">
	<h3>請選擇欲報名的課程：</h3>
	<table class="table table-striped">
	<tr>
	    <td><b>課程類型</b></td> <td><b>課程主題</b></td> <td><b>講師</b></td> <td><b>日期</b></td> 
		<td><b>時數</b></td> <td><b>地點</b></td> <td><b>報名</b></td> 
		<?php if($this->education_model->is_admin($_SESSION['id'])): ?>
		<td><b>修改(管理者)</b></td><th>
		<?php endif; ?>
	</tr>
	<?php
		// 只顯示未來的課程
		$today = idate("Y",time())-1911;
		$today = strval($today)."/".date("m/d",time());
		$this->db->order_by("DATEFROM", "desc");
		$all_course_data = $this->db->get_where('course',array('DATETO >='=> $today))->result_array();
		$group_id = array();
		
		foreach ($all_course_data as $e):
			if($e['TOPIC_TYPE'] > 0):
				if(!isset($group_id[$e['GROUP_TOPIC_ID']])): //群組裡面沒有資料，顯示群組課
					$group_data = $this->db->get_where('group_course',array('id'=> $e['GROUP_TOPIC_ID']))->row_array();
					$group_id[$e['GROUP_TOPIC_ID']] = 1;
	?>		
					<tr>
						<td>群組</td>
						<td><a href="course_detail?g_id=<?php echo $e['GROUP_TOPIC_ID']; ?>"><?php echo $group_data['group_topic']; ?></a></td>
						<td></td>
						<td><?php echo $group_data['DATEFROM']."~".$group_data['DATETO']; ?></td>
						<td><?php echo $group_data['CREDIT']; ?></td>
						<td><?php echo $e['PLACE']?></td>
						<td><a class="btn btn-info" href="select_course?g_id=<?php echo $e['GROUP_TOPIC_ID']; ?>">報名</a></td>
						<?php if($this->education_model->is_admin($_SESSION['id'])): ?>
						<td><a class="btn btn-danger" href="modify_course?g_id=<?php echo $e['GROUP_TOPIC_ID']; ?>">修改</a></td>
						<?php endif; ?>
					</tr>
				<?php endif; // End of showing group topic?>
			<?php else: // 顯示個別課程?>
				<tr>
					<td>個別</td>
					<td><a href="course_detail?c_id=<?php echo $e['id']; ?>"><?php echo $e['TOPIC']; ?></a></td>
					<?php $speaker = $this->db->get_where('speaker',array('id' => $e['SPEAKER_ID']))->row_array()['NAME'];?>
					<td><a href="speaker_detail?s_id=<?php echo $e['SPEAKER_ID']; ?>"><?php echo $speaker; ?></a></td>
					<td><?php echo $e['DATEFROM']."~".$e['DATETO']; ?></td>
					<td><?php echo $e['CREDIT']; ?></td>
					<td><?php echo $e['PLACE'];?></td>
					<td><a class="btn btn-info" href="select_course?g_id=0&c_id=<?php echo $e['id']; ?>">報名</a></td>
					<td>
					<?php if($e['MANAGERID'] === $_SESSION['id']): ?>
					<a class="btn btn-danger" href="modify_course?c_id=<?php echo $e['id']; ?>">修改</a>
					<?php else:
						$q = $this->education_model->search_by_ID_or_name($e['MANAGERID']);
						echo $q[0]['EPNAME']."(".$e['MANAGERID'].")";
					?>
					<?php endif; ?>
					</td>
				</tr>
			<?php endif; // End of showing 個別課程?>
		<?php endforeach; ?>

	</table>
	
</div>