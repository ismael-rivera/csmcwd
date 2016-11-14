<?php
/*
Template Name: How To Order
*/
/**
 * How To Order template file.
 *
 * Instructional Page explaining to new customers how to order and purchase a product.
 * 
 *  
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers 3.0
 */
get_header(); ?>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<div id="content_wrapper" class="grid">	
<div class="intro">
          			<?php if ( is_front_page() ) { ?>
            	<h2><?php the_title(); ?></h2>
					<?php } else { ?>	
				<h1><?php the_title(); ?></h1>
					<?php } ?>	
             
<!--<h2>Place your Order by Email
and Pay by Check, Money Order or by PayPal</h2>
<p>Use this form to submit your order. We will email you with your total cost.
(NY residents will have sales tax added).</p>

<p>Print this form send it with your check or money order to:</p>

<p>InnovativePaperGoods.com</p>

<p>PO Box 377 • Stone Ridge, NY 12484</p>

<p>We will notify you when we receive your check
and inform you of the shipping date.</p>

<p>Thank you for purchasing a webdecal.</p>
<p>If you have questions or prefer to order over the telephone with a credit card you can call us at: 845-338-0501.</p>-->
</div>
<?php echo slogan_tweet();?>		
        <div id="content">
        	<?php the_content(); 
			/* Run the loop to output the posts.
			 * If you want to overload this in a child theme then include a file
			 * called loop-index.php and that will be used instead.
			 */
			 //include ("howtoorder_content.php");
			?>
        </div>
</div>
<!--<div id="info" class="grid"><strong>Some Information</strong><br /><p>Dolore magna aliquam erat, volutpat ut wisi enim ad minim veniam quis! Mirum est notare quam littera gothica quam nunc putamus parum claram anteposuerit litterarum. Aliquam erat volutpat ut wisi enim ad minim veniam quis! Dolor sit amet, consectetuer adipiscing elit sed diam. Nibh euismod tincidunt ut laoreet dolore magna nostrud exerci tation ullamcorper suscipit lobortis nisl ut.</p>

<p>Eorum claritatem Investigationes demonstraverunt lectores legere me lius quod ii. Dignissim qui blandit praesent luptatum zzril delenit augue duis, dolore te feugait.</p>

<p>Gothica quam nunc putamus parum claram anteposuerit litterarum formas humanitatis, per seacula quarta decima et quinta. Littera decima eodem modo typi qui nunc nobis videntur parum clari fiant sollemnes in. Est etiam processus dynamicus qui sequitur mutationem consuetudium, lectorum mirum est. Me lius quod ii legunt saepius claritas notare.</p>


</div>-->

<?php get_footer(); ?>