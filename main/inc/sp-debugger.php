<?php 
if($bootstrap->debug){ ?>

<script type="text/javascript">
  var debugMsg = "<?php echo $bootstrap->debugMsg; ?>";
  if(debugMsg){
  	alert('<?php echo $bootstrap->debugMsg; ?>');
  }
</script>
<?php } ?>