<?php
/**
 * Template Name: Contact
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
<!--[if IE]>
<div id="content" class="grid_wrapper pages">
<![endif]-->
<!--[if !IE]>-->
<div id="content" class="grid_wrapper shadow1 pages">
<!--<![endif]--> 
<div class="page_header"><h2><?php the_title(); ?></h2></div>
<table>
                  <tr>
                    <td class="grid_L">
            <div class="grid_L_contact"> 
            <h3>Call us on 1300 26 46 86 or fill out the form below.</h3>       
            <div id="step-one-box" class="outerbox">
                <div class="contact_innerbox">
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
                <?php 
				$form = array();
				$form[0] = '[contact-form-7 id="106" title="General Enquiry"]';	 
				$form[1] = '[contact-form-7 id="141" title="Project Management"]';
				$form[2] = '[contact-form-7 id="142" title="Retail Fitout"]';
				$form[3] = '[contact-form-7 id="143" title="Store Maintenance"]';
				$form[4] = '[contact-form-7 id="144" title="Office Fitout"]';
				$form[5] = '[contact-form-7 id="145" title="Retail Defit"]';
				$form[6] = '[contact-form-7 id="146" title="Site Hoarding"]';
				$form[7] = '[contact-form-7 id="147" title="Fixture Removal and Delivery"]';
				$form[8] = '[contact-form-7 id="148" title="Rubbish Removal"]';
					 
					 ?>                              
                </div>
            </div><!-- end step-one-box -->
<!-- ###################################### FORM 1 ######################################## -->            
            <div id="general_enquiry" class="step-two-box outerbox hide">
            	<div class="contact_innerbox">
                    <h4>Step 2</h4>
                    <div class="mandatory">
                        <div>
                        <span class="required"></span>mandatory
                        </div>
                    </div>
                    <?php echo do_shortcode( $form[0] ) ?>
                </div>   
            </div><!-- end step-two-box -->
<!-- ###################################### FORM 1 ######################################## -->
            <div id="project_management" class="step-two-box outerbox hide">
            	<div class="contact_innerbox">
                    <h4>Step 2</h4>
                    <div class="mandatory">
                        <div>
                        <span class="required"></span>mandatory
                        </div>
                    </div>
                    <?php echo do_shortcode( $form[1] ) ?>
                </div>   
            </div><!-- end step-two-box -->
<!-- ###################################### FORM 1 ######################################## --> 
            <div id="retail_fitout" class="step-two-box outerbox hide">
            	<div class="contact_innerbox">
                    <h4>Step 2</h4>
                    <div class="mandatory">
                        <div>
                        <span class="required"></span>mandatory
                        </div>
                    </div>
                    <?php echo do_shortcode( $form[2] ) ?>
                </div>   
            </div><!-- end step-two-box -->
<!-- ###################################### FORM 1 ######################################## --> 
            <div id="store_maintenance" class="step-two-box outerbox hide">
            	<div class="contact_innerbox">
                    <h4>Step 2</h4>
                    <div class="mandatory">
                        <div>
                        <span class="required"></span>mandatory
                        </div>
                    </div>
                    <?php echo do_shortcode( $form[3] ) ?>
                </div>   
            </div><!-- end step-two-box -->
<!-- ###################################### FORM 1 ######################################## --> 
            <div id="office_fitout" class="step-two-box outerbox hide">
            	<div class="contact_innerbox">
                    <h4>Step 2</h4>
                    <div class="mandatory">
                        <div>
                        <span class="required"></span>mandatory
                        </div>
                    </div>
                    <?php echo do_shortcode( $form[4] ) ?>
                </div>   
            </div><!-- end step-two-box -->
<!-- ###################################### FORM 1 ######################################## --> 
            <div id="retail_defit" class="step-two-box outerbox hide">
            	<div class="contact_innerbox">
                    <h4>Step 2</h4>
                    <div class="mandatory">
                        <div>
                        <span class="required"></span>mandatory
                        </div>
                    </div>
                    <?php echo do_shortcode( $form[5] ) ?>
                </div>   
            </div><!-- end step-two-box -->
<!-- ###################################### FORM 1 ######################################## --> 
            <div id="site_hoarding" class="step-two-box outerbox hide">
            	<div class="contact_innerbox">
                    <h4>Step 2</h4>
                    <div class="mandatory">
                        <div>
                        <span class="required"></span>mandatory
                        </div>
                    </div>
                    <?php echo do_shortcode( $form[6] ) ?>
                </div>   
            </div><!-- end step-two-box -->
<!-- ###################################### FORM 1 ######################################## --> 
            <div id="fixture_removal_and_delivery" class="step-two-box outerbox hide">
            	<div class="contact_innerbox">
                    <h4>Step 2</h4>
                    <div class="mandatory">
                        <div>
                        <span class="required"></span>mandatory
                        </div>
                    </div>
                    <?php echo do_shortcode( $form[7] ) ?>
                </div>   
            </div><!-- end step-two-box -->
<!-- ###################################### FORM 1 ######################################## -->
            <div id="rubbish_removal" class="step-two-box outerbox hide">
            	<div class="contact_innerbox">
                    <h4>Step 2</h4>
                    <div class="mandatory">
                        <div>
                        <span class="required"></span>mandatory
                        </div>
                    </div>
                    <?php echo do_shortcode( $form[8] ) ?>
                </div>   
            </div><!-- end step-two-box -->
<!-- ###################################### FORM 1 ######################################## -->   
            
            </div><!-- End grid_L_contact -->
          
      
<div id="company_details">
    <table>
      <tr>
        <td colspan="2"><h3>Company Details</h3></td>
      </tr>
      <tr>
        <td>Phone Number</td>
        <td>1 300 26 46 86</td>
      </tr>
      <tr>
        <td>Email Address</td>
        <td><a href="mailto:info@thatretailcompany.com.au">info@thatretailcompany.com.au</a></td>
      </tr>
      <tr>
        <td>Postal Address</td>
        <td>PO Box 533, Revesby NSW 2212</td>
      </tr>
    </table>
</div>
            
            </td> <!-- End grid_L --> 
            <td class="grid_R"><?php get_trc_sidebar('widget','contact');?> </td>
            </tr>
            <?php get_fade_graphic(); ?>                   
</table>
   
</div><!--end content-->                     
<?php get_footer(); ?> 
  