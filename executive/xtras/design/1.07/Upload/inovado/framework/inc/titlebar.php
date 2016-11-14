<?php global $data; ?>

<?php if (!is_singular('portfolio')) { ?>

	<!-- Title Bar -->
	<?php if (get_post_meta( get_the_ID(), 'minti_revolutionslider', true ) == '0') { ?>
		
		<?php if ( has_post_thumbnail() && get_post_meta( get_the_ID(), 'minti_titlebar', true ) == 'featuredimage' ) { ?>
	
			<div id="alt-title" class="post-thumbnail" style="background-image: url( <?php $src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full', false, '' ); echo $src[0]; ?> );">
				<div class="grid"></div>
				<div class="container">
					<h1><?php the_title(); ?><?php if($data['text_titledivider'] != "") { echo $data['text_titledivider']; } ?></h1>
					<?php if(get_post_meta( get_the_ID(), 'minti_subtitle', true )){ echo '<h2>'.get_post_meta( get_the_ID(), 'minti_subtitle', true ).'</h2>'; } ?>
				</div>
			</div>
			
			<?php if(get_post_meta( get_the_ID(), 'minti_featuredimage-breadcrumbs', true ) == true) { ?>
				<div id="alt-breadcrumbs">
					<div class="container">
						<?php minti_breadcrumbs(); ?>
					</div>
				</div>
			<?php } ?>
			
			<?php if($data['check_stripedborder']) { ?><div class="hr-border"></div><?php } ?>
		
		<?php } elseif (get_post_meta( get_the_ID(), 'minti_titlebar', true ) == 'notitlebar') { ?>
			
			<?php if(get_post_meta( get_the_ID(), 'minti_featuredimage-breadcrumbs', true ) == true) { ?>
			<div id="no-title">
				<div class="container">
						<div id="breadcrumbs" class="sixteen columns <?php if(get_post_meta( get_the_ID(), 'minti_subtitle', true )){ echo 'breadrcumbpadding'; } /* to align middle */ ?>">
							<?php  minti_breadcrumbs(); ?>
						</div>
				</div>
			</div>
			<?php if($data['check_stripedborder']) { ?><div class="hr-border"></div><?php } ?>
			<?php } else { ?>
			<div id="no-title-divider"></div>
			<?php if($data['check_stripedborder']) { ?><div class="hr-border"></div><?php } ?>
			<?php } ?>
			
		<?php } else { ?>
	
			<div id="title">
				<div class="container">
					<div class="ten columns">
						<h1><?php the_title(); ?><?php if($data['text_titledivider'] != "") { echo $data['text_titledivider']; } ?></h1>
						<?php if(get_post_meta( get_the_ID(), 'minti_subtitle', true )){ echo '<h2>'.get_post_meta( get_the_ID(), 'minti_subtitle', true ).'</h2>'; } ?>
					</div>
					<?php if(get_post_meta( get_the_ID(), 'minti_featuredimage-breadcrumbs', true ) == true) { ?>
						<div id="breadcrumbs" class="six columns <?php if(get_post_meta( get_the_ID(), 'minti_subtitle', true )){ echo 'breadrcumbpadding'; } /* to align middle */ ?>">
							<?php  minti_breadcrumbs(); ?>
						</div>
					<?php } ?>
				</div>
			</div>
			
			<?php if($data['check_stripedborder']) { ?><div class="hr-border"></div><?php } ?>
		
		<?php } ?>
		
	<?php } // end slidertype = noslider ?>
	
	<?php if (get_post_meta( get_the_ID(), 'minti_revolutionslider', true ) != '0') { ?>
	
		<?php if(class_exists('RevSlider')){ putRevSlider(get_post_meta( get_the_ID(), 'minti_revolutionslider', true )); } ?>
	
	<?php } /* end slidertype = revslider */ ?>
	<!-- End: Title Bar -->

<?php } else { ?>

	<?php if (get_post_meta( get_the_ID(), 'minti_titlebar', true ) == 'notitlebar') { ?>
		
		<div id="no-title-divider"></div>
		<?php if($data['check_stripedborder']) { ?><div class="hr-border"></div><?php } ?>
		
	<?php } else { ?>

		<div id="title">
			<div class="container">
				<div class="ten columns">
					<h1><?php the_title(); ?><?php if($data['text_titledivider'] != "") { echo $data['text_titledivider']; } ?></h1>
					<?php if(get_post_meta( get_the_ID(), 'minti_subtitle', true )){ echo '<h2>'.get_post_meta( get_the_ID(), 'minti_subtitle', true ).'</h2>'; } ?>
				</div>
				<div class="projects-nav <?php if( (get_post_meta( get_the_ID(), 'minti_subtitle', true )) == false ){ echo 'projectsnavpadding'; } /* to align middle */ ?>">
					<?php next_post_link('<div class="next">%link</div>', __('Next', 'minti')); ?>  
					<?php previous_post_link('<div class="prev">%link</div>', __('Previous', 'minti')); ?>
				</div>
			</div>
		</div>
		
		<?php if($data['check_stripedborder']) { ?><div class="hr-border"></div><?php } ?>
	
	<?php } ?>

	<!-- End: Projects Title Bar -->

<?php } ?>