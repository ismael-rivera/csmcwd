<?php
// Add JS Scripts
function eci_wp_add_styles() {
	?>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js">
        </script>
        <script type="text/javascript" >
        jQuery(document).ready(function()
        {
        jQuery(".comment_button").click(function(){

        var element = jQuery(this);
        var I = element.attr("id");

        jQuery("#slidepanel"+I).slideToggle(300);
        jQuery(this).toggleClass("active");

        return false;
        });
        });
        </script>
    <?php
}

?>