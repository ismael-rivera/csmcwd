<?php
/**
 * Template Name: Two Column Page
 *
 * A custom page template without sidebar.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage Ishygrids
 * @since Ishygrids 1.0
 */

get_header(); ?>
<div id="content" class="grid_wrapper shadow1 pages">
<div class="page_header"><h2><?php the_title(); ?></h2></div>
<table>
                  <tr>
                    <td class="grid_L">
            <div class="grid_L_contact"> 
            <h3>Call us on 1300 26 46 86 or fill out the form below.</h3>       
            <div id="step-one-box" class="outerbox">
                <div class="innerbox">
                <h4>Step 1</h4>
                <form>
                <label for="services">Please select the services that you are interested in:</br></label>
                    <select name="services" id="services" autocomplete="off" size="1">
                    	<option value="please select" selected="selected">Please Select</option>
                        <option value="general enquiry">General Enquiry</option>
                        <option value="project management">Project Management</option>
                        <option value="retail fitout">Retail Fitout</option>
                        <option value="store maintenance">Store Maintenance</option>
                        <option value="office fitout">Office Fitout</option>
                        <option value="retail defit">Retail Defit</option>
                        <option value="site hoarding">Site Hoarding</option>
                        <option value="fixture removal and delivery">Fixture Removal &amp; Delivery</option>
                        <option value="rubbish removal">Rubbish Removal</option>
                     </select>
                </form>                         
                </div>
            </div><!-- end step-one-box -->  
            
            </div><!-- End grid_L_contact -->
          
            
            </td> <!-- End grid_L --> 
            <td class="grid_R"><?php get_trc_sidebar('static','services');?></td>
            </tr>
            <?php get_fade_graphic(); ?>                   
</table>
   
</div><!--end content-->                     
<?php get_footer(); ?> 
  