<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $this->fetch('title'); ?>
	</title>
	<?php echo $this->element('webarch/css') ?>


<body class="">
	<?php 
		echo $this->element('webarch/header') ;
	?>

	<!-- BEGIN CONTAINER -->
	<div class="page-container row-fluid">
	<?php 
		echo $this->element('webarch/sidebar') ;
	?>

	  <!-- BEGIN PAGE CONTAINER-->
	  <div class="page-content">
	    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->

	    <div class="content">
	    	<?php 
	    		echo $this->Session->flash();
	      		echo $this->fetch('content');
	    	?>
	    </div>
	  </div>
	  <?php echo $this->element('sql_dump'); ?>
	</div>
	<!-- END CONTAINER -->
		<?php 
			echo $this->element('webarch/script');
		?>
	
</body>
</html>
