<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.  The actual display of comments is
 * handled by a callback to twentyten_comment which is
 * located in the functions.php file.
 *
 * @package WordPress
 * @subpackage thatretailcompany
 * @since thatretailcompany 1.0
 */
?>


<div class="outerbox">
                <div class="contact_innerbox">
         
                <?php 
				
				$args = array(
					'fields' 				  => apply_filters( 'comment_form_default_fields', custom_fields($fields) ),
					'must_log_in'             => 'ul',
					'logged_in_as'            => null,
					'comment_notes_before'    => null,
					'comment_notes_after'     => '',
					'id_form'              	  => 'testimonialform',
					'id_submit'          	  => 'submit',
					'title_reply'       	  => '<h4>Add a Testimonial</h4>',
					'title_reply_to' 		  => null,
					'cancel_reply_link'  	  => __( 'Cancel reply' ),
					'label_submit'  		  => 'Submit',
					'comment_field'           => '<tr><td class="comment-form-comment label message_label">' . '<label for="comment">' . __( 'Comment' ) . '</label>' . '</td>' . '<td class="field message_field" rowspan="2"><span class="required"></span><textarea id="comment" name="comment" cols="45" rows="12" tabindex="4" aria-required="true"></textarea>' . '</td></tr><tr>
    <td class="label"></td>
  </tr>',
					'submit_wrapper_begin'	  => '<tr><td>&nbsp;</td><td class="form_submit">',
					'submit_wrapper_end' 	  => '</td></tr>'  
					); 
    			
				?>
               <table class="srvcs_form">
                <?php comment_form($args); ?>
                </table>        
                                        
				</div>
</div><!-- end outerbox --> 

<?php if ( have_comments() ) : ?>
			<!-- thatretailcompany NOTE: The following h3 id is left intact so that comments can be referenced on the page -->
			<h3 id="comments-title">Testimonials</h3>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
				<?php previous_comments_link( __( '&larr; Older Comments', 'twentyten' ) ); ?>
				<?php next_comments_link( __( 'Newer Comments &rarr;', 'twentyten' ) ); ?>
<?php endif; // check for comment navigation ?>

			<ol>
				<?php
					/* Loop through and list the comments. Tell wp_list_comments()
					 * to use twentyten_comment() to format the comments.
					 * If you want to overload this in a child theme then you can
					 * define twentyten_comment() and that will be used instead.
					 * See twentyten_comment() in twentyten/functions.php for more.
					 */
					wp_list_comments( array( 'avatar_size' => NULL, 'callback' => 'twentyten_comment' ) );
				?>
			</ol>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
				<?php previous_comments_link( __( '&larr; Older Comments', 'twentyten' ) ); ?>
				<?php next_comments_link( __( 'Newer Comments &rarr;', 'twentyten' ) ); ?>
<?php endif; // check for comment navigation ?>

<?php else : // or, if we don't have comments:

	/* If there are no comments and comments are closed,
	 * let's leave a little note, shall we?
	 */
	if ( ! comments_open() ) :
?>
	<p><?php _e( 'Comments are closed.', 'twentyten' ); ?></p>
<?php endif; // end ! comments_open() ?>

<?php endif; // end have_comments() ?>
