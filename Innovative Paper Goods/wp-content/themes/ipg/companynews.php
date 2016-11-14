<?php
/*
Template Name: Company News and Events
*/
?>
<?php get_header(); ?>
<div id="content_wrapper" class="grid">	
<div id="content" class='content_news'>
<table class="ipgloop" border="0" cellspacing="0" cellpadding="0">
<?php
$query = 'posts_per_page=10';
$queryObject = new WP_Query($query);
// The Loop...
if ($queryObject->have_posts()) {
	while ($queryObject->have_posts()) {
		echo '<tr><td>';
		$queryObject->the_post(); ?>
		</td></tr>
        <tr><td>
        <?php /*$size = array(280,90);
		the_post_thumbnail($size, $attr);*/ ?>
        <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
        </td></tr>
        <tr><td>
		<?php the_content(); ?>
		</td></tr>
		<?php echo /*Spacer :*/ "<tr><td class='cspacer'>&nbsp;</td></tr>";
	}
}
?>
</table>
</div>
<?php get_sidebar('custom'); ?>

</div>
<?php   
get_footer();
?>