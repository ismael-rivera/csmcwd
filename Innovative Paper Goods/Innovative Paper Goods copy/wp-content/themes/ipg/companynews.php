<?php
/*
Template Name: Company News and Events
*/
?>
<?php get_header(); ?>
<div id="content_wrapper" class="grid">	
<table id="content_news" border="0" cellspacing="0" cellpadding="0">
<?php
$query = 'posts_per_page=10';
$queryObject = new WP_Query($query);
// The Loop...
if ($queryObject->have_posts()) {
	while ($queryObject->have_posts()) {
		echo '<tr><td>';
		$queryObject->the_post();
		echo '</td></tr>';
		echo '<tr><td>';
		the_title();
		echo '</td></tr>';
		echo '<tr><td>';
		the_content();
		echo '</td></tr>';
		echo /*Spacer :*/ "<tr><td class='cspacer'>&nbsp;</td></tr>";
	}
}
?>
</table>
<?php get_sidebar('custom'); ?>

</div>
<?php   
get_footer('custom');
?>