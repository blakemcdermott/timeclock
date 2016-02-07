<?php
global $date_range_get_params;
//global $data;
?>
<div data-role="popup" id="popupDialog" data-overlay-theme="a" data-theme="a" data-dismissible="false" style="max-width:400px;">
    <div data-role="header" data-theme="a">
    <h1>Delete Record?</h1>
    </div>
    <div role="main" class="ui-content">
        <h3 class="ui-title">Are you sure you want to delete this record?</h3>
    <p>This action cannot be undone.</p>
        <a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-a" data-rel="back">Cancel</a>
        <a href="<?php echo $get_page.'?id='.$data[0]['id'].'&type=' . $type . $date_range_get_params . '&action=delete'; ?>" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-a" data-rel="external" data-transition="fade" >Delete</a>
    </div>
</div>
	</div>	<!-- main-conent -->

</div>	<!-- inside-wrap -->
</div>
</body>
</html>
