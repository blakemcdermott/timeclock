<?php
foreach ( $_GET as $key=>$value ) {
	$$key = $value;	
}
//$day = $_GET['day'];
//$row_num = $_GET['row_num'];
//$date = $_GET['date'];
?>
<div class="ui-grid-a">
	<div class="ui-block-a">
            <fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
            <input type="hidden" name="<?php echo $day; ?>-date-<?php echo $row_num; ?>" id="<?php echo $day; ?>-date-<?php echo $row_num; ?>" value="<?php echo $date; ?>"><input type="radio" name="<?php echo $day; ?>-timetype-<?php echo $row_num; ?>" id="<?php echo $day; ?>-radio-choice-c-<?php echo $row_num; ?>" value="job" checked="checked" ><label for="<?php echo $day; ?>-radio-choice-c-<?php echo $row_num; ?>">JOB</label>
            <input type="radio" name="<?php echo $day; ?>-timetype-<?php echo $row_num; ?>" id="<?php echo $day; ?>-radio-choice-d-<?php echo $row_num; ?>" value="admin" ><label for="<?php echo $day; ?>-radio-choice-d-<?php echo $row_num; ?>">ADMIN</label>
            <input type="radio" name="<?php echo $day; ?>-timetype-<?php echo $row_num; ?>" id="<?php echo $day; ?>-radio-choice-e-<?php echo $row_num; ?>" value="pto" ><label for="<?php echo $day; ?>-radio-choice-e-<?php echo $row_num; ?>">PTO</label></fieldset>
        </div>
        <div class="ui-block-b">
        <!--<div class="ui-bar ui-bar-a" style="height:60px">Block B</div>-->
            <fieldset class="ui-grid-a">
            <div class="ui-block-a">
            	<input type="text" type="number" name="<?php echo $day; ?>-jobid-<?php echo $row_num; ?>" id="<?php echo $day; ?>-jobid-<?php echo $row_num; ?>" value="" placeholder="Job ID">
            </div>
            <div class="ui-block-b">
            	<input type="text" type="number" name="<?php echo $day; ?>-hours-<?php echo $row_num; ?>" id="<?php echo $day; ?>-hours-<?php echo $row_num; ?>" value="" placeholder="Hours">
            </div>
            </fieldset>
        </div>
</div><!-- /grid-a -->