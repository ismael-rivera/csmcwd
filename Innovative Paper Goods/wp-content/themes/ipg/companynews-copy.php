<?php
/*
Template Name: Company News and Events
*/
?>
<?php get_header(); ?>
<div id="content_wrapper" class="grid">	
 <table id="content_news" border="1" cellspacing="0" cellpadding="0">
<?php
 $query = "SELECT * FROM wp_posts WHERE post_type='post'";
 $result = mysql_query($query);
 while($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
{
    echo /*Title :*/   "<tr><td class='title'>{$row['post_title']}</td></tr>"; 
    echo /*Content :*/ "<tr><td class='content'>{$row['post_content']}</td></tr>";
	echo /*Spacer :*/ "<tr><td class='cspacer'>&nbsp;</td></tr>";
}
 ?>
</table> 
<?php get_sidebar('custom'); ?>

</div>
<?php   
get_footer('custom');
?>