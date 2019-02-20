<form id="redirectForm" action="<?php echo $_GET['FPAY_URL']; ?>" method="post" name="myform">
<input type="hidden" name="ORDER_ID" value="<?php echo $_GET['ORDER_ID']; ?>"/>
<input type="hidden" name="REFERENCE_ID" value="<?php echo $_GET['REFERENCE_ID']; ?>"/>
<input type="hidden" name="TRACK_ID" value="<?php echo $_GET['TRACK_ID']; ?>"/>
</form>
<script language="javascript">
       document.getElementById("redirectForm").submit();
</script>
