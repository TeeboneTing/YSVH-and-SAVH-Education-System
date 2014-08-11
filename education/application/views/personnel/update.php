<div class="row-fluid">
	<div class="span3">
		<h4>人事系統 </h4>
		<p><a href="<?php echo base_url();?>personnel">合併人事資料</a></p>
		<p><a href="<?php echo base_url();?>personnel/import_new_employ">更新新進人員</a></p>
		<p>人事異動(調院區)</p>
	</div>
	<div class="span9">
		<form name="search" class="form-search" method="post" action="">
		<h3>
			請輸入人員姓名或身分證字號：
  			<input class="span3 search-query" name="search-text" type="text">
  			<button type="submit" class="btn">搜尋</button>
		</h3>
		</form>
		
		<?php // 顯示搜尋結果
		if(isset($search_result)): ?>
			<table class="table table-striped">
			<tr>
	      		<td><b>姓名</b></td> <td><b>身分證字號</b></td> 
				<td><b>部門</b></td> <td><b>到職日期</b></td> 
				<td><b>院區</b></td> <td><b>管理權限</b></td>
			</tr>
		<?php 	
			foreach ($search_result as $e):
				$DPNAME = $this->education_model->get_dpname($e);
				$dpnames = $this->education_model->get_all_dpname($e['BRANCHNO']);
		?>		
				<tr>
				<td><?php echo $e['EPNAME']; ?></td>
				<td id='idcardno'><?php echo $e['IDCARDNO']; ?></td>
				<td>
					<select id='dpnames'>
				
					<?php foreach ($dpnames as $i): ?>
						<?php if($DPNAME === $i['DPNAME']): ?>
							<option value='<?php echo $i['DPNO'];?>' selected><?php echo $i['DPNAME'];?></option>
						<?php else: ?>
							<option value='<?php echo $i['DPNO'];?>'> <?php echo $i['DPNAME'];?> </option>
						<?php endif;?>
					<?php endforeach;?>
				
					</select>
				</td>
				
				<td> <?php echo $e['INDAY'];?> </td>
				<td>
					<select id='branch' class="input-small">
						<option value='0' <?php if($e['BRANCHNO'] === "0"){ echo "selected";} ?>>員山</option>
						<option value='1' <?php if($e['BRANCHNO'] === "1"){ echo "selected";} ?>>蘇澳</option>
					</select>
				</td>
				<td>
					<?php $admin = $this->education_model->is_admin($e['EPID']);?>
					<select id='admin' >
						<option value='0' <?php if($admin === 0) {echo "selected";} ?> >一般使用者</option>
						<option value='1' <?php if($admin === 1) {echo "selected";} ?> >課程管理者</option>
						<option value='5' <?php if($admin === 5) {echo "selected";} ?> >系統管理者</option>
					</select>
				</td>
				</tr>
			<?php endforeach; ?>
			</table>
			
			<?php if(count($search_result)): ?>
				<div class="span4 offset4"><button class="btn" id="save_change">儲存變更</button></div>
			<?php endif; ?>
		<?php endif;?>
		
	</div>
	<div class="span10"></div>
</div>