<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title><?php echo $title ?></title>
   <script src="http://code.jquery.com/jquery.js"></script>
   <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
   <link href="<?php echo base_url();?>assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
   <link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet" media="screen">
   <link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery-ui.css">
   <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
   <script src="<?php echo base_url();?>assets/js/style.js"></script>
</head>
<body>
  <div class="container-fluid">
    <h1 align="center">台北榮總蘇澳暨員山分院 教育訓練系統</h1>
    <div class="row-fluid">
	  <div class="span4 offset1">
 	    <p>您好，<?php echo $_SESSION['username']."！(來自".$_SERVER['REMOTE_ADDR'].")"; ?><br>
  	    <?php if(isset($mainpage) && $mainpage) echo "請選擇您要使用的功能："; ?> </p>
  	  </div>
  	  <?php 
  	  	$mainpage_span = '"span2 offset4"';
  	  	$otherpage_span = '"span4 offset2"';
  	  ?>
  	  <div class= <?php if(!isset($mainpage)){echo $otherpage_span;}else {echo $mainpage_span;} ?> >
  	     <div align="right">
  	  	 <?php if(!isset($mainpage)) echo "<a href='".base_url()."mainpage' class='btn'>回首頁</a>"; ?>
  	     <a href="<?php echo base_url();?>logout" class="btn btn-danger">登出</a>
  	     </div>
  	  </div>
    </div>