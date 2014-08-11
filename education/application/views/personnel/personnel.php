<div class="row-fluid">
	<div class="span3">
		<h4>人事系統 </h4>
		<p>合併人事資料</p>
		<p><a href="<?php echo base_url();?>personnel/import_new_employ">更新新進人員</a></p>
		<p><a href="<?php echo base_url();?>personnel/update_employ">人事異動(調院區)</a></p>
	</div>
	<div class="span9">
	  <h3> 以下列出擁有共同帳號的名單，共 <?php echo $total_rows ?> 筆資料。</h3>
	  <a href="<?php echo base_url();?>download_csv" class="btn">下載csv檔</a> (格式：姓名,身分證字號,員山部門,蘇澳部門,到職日期)
	  <?php echo $this->pagination->create_links(); ?>
	  <form name="join" method="post" action="">
	  <table class="table table-striped">
	    <tr>
	      <td><b>姓名</b></td> <td><b>身分證字號</b></td> <td><b>部門</b></td> <td><b>到職日期</b></td> <td><b>院區</b></td>
	    </tr>
	    <?php 
	      foreach ($query as $e){
			//撈部門名稱
			$para1['DPNO'] = $this->education_model->get_ysvh_employ_by_ID($e['EPID'])['DPNO']; $para1['BRANCHNO'] = '0';
	      	$YSVH_DPNAME = $this->education_model->get_dpname($para1);
	      	$para2['DPNO'] = $this->education_model->get_savh_employ_by_ID($e['EPID'])['DPNO']; $para2['BRANCHNO'] = '1';
	      	$SAVH_DPNAME = $this->education_model->get_dpname($para2);
			echo "<tr>";
			echo "<td>".$e['EPNAME']."</td>";
			echo "<td>".$e['IDCARDNO']."</td>";
			echo "<td>員山：".$YSVH_DPNAME."<br>蘇澳：".$SAVH_DPNAME."</td>";
			echo "<td>".$e['INDAY']."</td>";
			echo "<td> <select name='".$e['EPID']."' class='input-medium'><option value='' selected>(請選擇院區)</option> <option value='0'>員山</option> <option value='1'>蘇澳</option></select></td>";
			echo "</tr>";
		  }
	    ?>
	  </table>
	  <?php echo $this->pagination->create_links(); ?>
	  <div class="span4 offset4"><input type="submit" class="btn btn-danger" value="合併"> <input type="reset" class="btn" value="重設" ></div>
	  </form>
	</div>
	<div class="span10"></div>
</div>