<div class="row-fluid">
	<div class="span3">
		<h4>人事系統 </h4>
		<p><a href="<?php echo base_url();?>personnel">合併人事資料</a></p>
		<p>更新新進人員</p>
		<p><a href="<?php echo base_url();?>personnel/update_employ">人事異動(調院區)</a></p>
	</div>
	<div class="span9">
		<h3>請輸入人員新進日期範圍：
		   <input name="from" type="text" id="datepicker1" class="input-small" value="<?php if(isset($from)) echo $from;?>"> 至 
		   <input name="to" type="text" id="datepicker2" class="input-small" value="<?php if(isset($to)) echo $to;?>">
		   <input id="select-inday" class="btn" type="submit" value="確定">
		</h3>
		<p> 目前員山院區最新人員新進日期：103/5/16 蘇澳：103/4/21 </p>
		<div id="loadingIMG" style="display:none"><img src="<?php echo base_url();?>assets/img/ajax-loader.gif" /> 資料處理中，請稍後...</div>
		<div id="databox">
		<?php 
			if(isset($inserted)){
				echo "已匯入";
				foreach ($inserted as $e)
					{echo $e." ";}
				echo "共".(string)count($inserted)."個新進人員。";
			}
		?>
		</div>
	</div>
	<div class="span10"></div>
</div>