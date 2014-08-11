
<?php
if($this->education_model->is_admin($_SESSION['id'])):
?>
<div class="function">
	<?php if($this->education_model->is_admin($_SESSION['id']) === 5):?>
    <a href="<?php echo base_url(); ?>personnel">
       <img src="<?php echo base_url(); ?>assets/img/personnel.png" />
    </a> 
    <?php endif;?>
    <a href="<?php echo base_url(); ?>new_course">
    	<img src="<?php echo base_url(); ?>assets/img/new_class.png" />
    </a>
    <a href="<?php echo base_url(); ?>signin">
    	<img src="<?php echo base_url(); ?>assets/img/signin.png" />
    </a>
</div>

<?php endif;?>

<div class="function">
	<a href="<?php echo base_url();?>elearning">
    	<img src="<?php echo base_url();?>assets/img/insert.png" />
	</a>
    <a href="<?php echo base_url();?>signup">
    	<img src="<?php echo base_url();?>assets/img/signup.png" />
    </a>
    <img src="<?php echo base_url();?>assets/img/statistics.png" />
</div>

<div class="function" >
	<a href="<?php echo base_url();?>extracurricular">
		<img src="<?php echo base_url();?>assets/img/outdoor.png" /> 
	</a>
	<a href="<?php echo base_url();?>settings">
		<img src="<?php echo base_url();?>assets/img/settings.png" /> 
	</a>
</div>
