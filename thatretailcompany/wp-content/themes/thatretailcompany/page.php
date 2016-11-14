<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the wordpress construct of pages
 * and that other 'pages' on your wordpress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage thatretailcompany
 * @since thatretailcompany 1.0
 */

get_header(); ?>
<div id="content" class="grid_wrapper shadow1 pages">
    <div class="page_header"><h2><?php the_title(); ?></h2></div>
            <div class="grid_L">
				<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
                <?php the_content(); ?>
                <?php endwhile; ?>
            <div class="demo">

                <div id="accordion">
                    <h3><a href="#">Project Management</a></h3>
                    <div>
                        <p>
                        Mauris mauris ante, blandit et, ultrices a, suscipit eget, quam. Integer
                        ut neque. Vivamus nisi metus, molestie vel, gravida in, condimentum sit
                        amet, nunc. Nam a nibh. Donec suscipit eros. Nam mi. Proin viverra leo ut
                        odio. Curabitur malesuada. Vestibulum a velit eu ante scelerisque vulputate.
                        </p>
                    </div>
                    <h3><a href="#">Section 2</a></h3>
                    <div>
                        <p>
                        Sed non urna. Donec et ante. Phasellus eu ligula. Vestibulum sit amet
                        purus. Vivamus hendrerit, dolor at aliquet laoreet, mauris turpis porttitor
                        velit, faucibus interdum tellus libero ac justo. Vivamus non quam. In
                        suscipit faucibus urna.
                        </p>
                    </div>
                    <h3><a href="#">Section 3</a></h3>
                    <div>
                        <p>
                        Nam enim risus, molestie et, porta ac, aliquam ac, risus. Quisque lobortis.
                        Phasellus pellentesque purus in massa. Aenean in pede. Phasellus ac libero
                        ac tellus pellentesque semper. Sed ac felis. Sed commodo, magna quis
                        lacinia ornare, quam ante aliquam nisi, eu iaculis leo purus venenatis dui.
                        </p>
                        <ul>
                            <li>List item one</li>
                            <li>List item two</li>
                            <li>List item three</li>
                        </ul>
                    </div>
                    <h3><a href="#">Section 4</a></h3>
                    <div>
                        <p>
                        Cras dictum. Pellentesque habitant morbi tristique senectus et netus
                        et malesuada fames ac turpis egestas. Vestibulum ante ipsum primis in
                        faucibus orci luctus et ultrices posuere cubilia Curae; Aenean lacinia
                        mauris vel est.
                        </p>
                        <p>
                        Suspendisse eu nisl. Nullam ut libero. Integer dignissim consequat lectus.
                        Class aptent taciti sociosqu ad litora torquent per conubia nostra, per
                        inceptos himenaeos.
                        </p>
                    </div>
                </div>

			</div><!-- End demo --> 
            </div> <!-- End grid_L --> 
            <div class="grid_R"><?php get_sidebar(); ?></div>                  
    </div>   <!--end content-->                 
  <div id="footer" class="grid">
  <?php get_footer(); ?> 
  </div>