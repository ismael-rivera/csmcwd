<style type="text/css">
.event{
	width: 320px;
	height: 345px;
	background-color: #FFF;
	border: 1px solid #dbdbdb;
}
</style>
<?php
?>
<!--If no widgets are active the table below will dissappear. If you wish to avoid this, then remove or comment out the above php code that checks for widgets. Otherwise you should leave this as is.-->
<table width="1024" border="0" cellspacing="20" cellpadding="0">
  <tr>
    <td class="event"><?php /*echo do_shortcode("[contact-form 2 'Order Form - Customer Info']")*/ 
	insert_cform();
	?></td>
  </tr>
</table>
