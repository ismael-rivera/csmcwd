// JavaScript Document

$("input:file").uniform();

//function hide () { $("#author").val(""); $("#email").val("");}



    $.fn.clearForm = function() {
      return this.each(function() {
        var type = this.type, tag = this.tagName.toLowerCase();
        if (tag == 'form')
          return $(':input',this).clearForm();
        if (type == 'text' || type == 'password' || tag == 'textarea')
          this.value = '';
        else if (type == 'checkbox' || type == 'radio')
          this.checked = false;
        else if (tag == 'select')
          this.selectedIndex = -1;
      });
    };
$('#author').clearForm()
$('#email').clearForm()


		 


//------------THIS CODE IS FOR THE ACCORDION TABS.-------------------

$(document).ready(function() {
		var hash = window.location.hash;
		
		$( "#accordion" ).accordion({
		autoHeight: false,		
		icons: { "header": "ui-icon-arrow-1-up", "headerSelected": "ui-icon-arrow-1-down" },
		animated: 'slide',
		navigation: true,
		clearStyle: true
		});
		
		$("#accordion").accordion({active: hash });
	});

//------------THIS CODE BELOW IS FOR THE SLIDER TABS.-------------------

$(function() {
	//grabs the php array I made in functions.php and places all as strings (the array has to be converted into a string on the path to js) inside a javascript variable
	 
	var slide_tabs = slide_tabs_str;
	
	//create var and fill with empty array to be filled with data from php array
	
	var slides = new Array();
	
	//create a var to index the array
	
	var x;
	//pass string data from php array into this var and split it up into indexes
	
	var slides = slide_tabs.split(", ")
	
	//calls up jquery.cycle parameters
		
    $("#slider")/*.after("<ul id='nav'>")*/.cycle({ 
    fx:     'scrollHorz', 
    speed:  250, 
    timeout: 4000, 
	next:   '#next', 
    prev:   '#prev',
	pager:   '#nav',
	pause:	 true,
	pauseOnPagerHover: true,
	pagerAnchorBuilder: function (x, tabs) {
            //used to be - return '<li><div class="if-active"><a href="#">' + '<span></span>' + slides[x] + '</a></div></li>';
			//but now...it's
			return "<li><div class='if-active'>" + slides[x] + "</div></li>";
        }
 	});
});	

// --------------------------CONTACT FORM FUNCTIONALITY---------------------------

$(document).ready(function(){
	
		//CONACT FORM SELECT BOX SLIDER LINK FUNCTIONALITY
		
		var hash = window.location.hash;
		
		 if (hash == "#projectmanagement"){
			
			 $("#project_management").slideDown("fast"); //Slide Down Effect
			 $("#services").val("project management");
 
        } else {
 
            $("#project_management").slideUp("fast");    //Slide Up Effect
			
        }
		
		//-------------
	
		if (hash == "#retailfitout"){
			
			 $("#retail_fitout").slideDown("fast"); //Slide Down Effect
			 $("#services").val("retail fitout");
 
        } else {
 
            $("#retail_fitout").slideUp("fast");    //Slide Up Effect
			
        }
		
		//-------------
		
		if (hash == "#storemaintenance"){
			
			 $("#store_maintenance").slideDown("fast"); //Slide Down Effect
			 $("#services").val("store maintenance");
 
        } else {
 
            $("#store_maintenance").slideUp("fast");    //Slide Up Effect
			
        }
		
		//-------------
		
		if (hash == "#officefitout"){
			
			 $("#office_fitout").slideDown("fast"); //Slide Down Effect
			 $("#services").val("office fitout");
 
        } else {
 
            $("#office_fitout").slideUp("fast");    //Slide Up Effect
			
        }
		
		//-------------
		
		if (hash == "#retailde-fit"){
			
			 $("#retail_defit").slideDown("fast"); //Slide Down Effect
			 $("#services").val("retail defit");
 
        } else {
 
            $("#retail_defit").slideUp("fast");    //Slide Up Effect
			
        }
		
		//-------------
		
		if (hash == "#sitehoarding"){
			
			 $("#site_hoarding").slideDown("fast"); //Slide Down Effect
			 $("#services").val("site hoarding");
 
        } else {
 
            $("#site_hoarding").slideUp("fast");    //Slide Up Effect
			
        }
		
		//-------------
		
		if (hash == "#fixtureremovaldelivery"){
			
			 $("#fixture_removal_and_delivery").slideDown("fast"); //Slide Down Effect
			 $("#services").val("fixture removal and delivery");
 
        } else {
 
            $("#fixture_removal_and_delivery").slideUp("fast");    //Slide Up Effect
			
        }
		
		//-------------
		
		if (hash == "#rubbishremoval"){
			
			 $("#rubbish_removal").slideDown("fast"); //Slide Down Effect
			 $("#services").val("rubbish removal");
 
        } else {
 
            $("#rubbish_removal").slideUp("fast");    //Slide Up Effect
			
        }
		
		//-------------
		
		
			
		//CONTACT FORM SELECT BOX MANUAL FUNCTIONALITY	
		
  		$("#services").change(function(){
		
			//alert('javascript working');
 
        if ($(this).val() == "general enquiry" ) {
 
            $("#general_enquiry").slideDown("fast"); //Slide Down Effect
 
        } else {
 
            $("#general_enquiry").slideUp("fast");    //Slide Up Effect
 
        }
		
		//----------
		
		if ($(this).val() == "project management" ) {
 
            $("#project_management").slideDown("fast"); //Slide Down Effect
 
        } else {
 
            $("#project_management").slideUp("fast");    //Slide Up Effect
 
        }
		
		//----------
		
		if ($(this).val() == "retail fitout" ) {
 
            $("#retail_fitout").slideDown("fast"); //Slide Down Effect
 
        } else {
 
            $("#retail_fitout").slideUp("fast");    //Slide Up Effect
 
        }
		
		//----------
		
		if ($(this).val() == "store maintenance" ) {
 
            $("#store_maintenance").slideDown("fast"); //Slide Down Effect
 
        } else {
 
            $("#store_maintenance").slideUp("fast");    //Slide Up Effect
 
        }
		
		//----------
		
		if ($(this).val() == "office fitout" ) {
 
            $("#office_fitout").slideDown("fast"); //Slide Down Effect
 
        } else {
 
            $("#office_fitout").slideUp("fast");    //Slide Up Effect
 
        }
		
		//----------
		
		if ($(this).val() == "retail defit" ) {
 
            $("#retail_defit").slideDown("fast"); //Slide Down Effect
 
        } else {
 
            $("#retail_defit").slideUp("fast");    //Slide Up Effect
 
        }
		
		//----------
		
		if ($(this).val() == "site hoarding" ) {
 
            $("#site_hoarding").slideDown("fast"); //Slide Down Effect
 
        } else {
 
            $("#site_hoarding").slideUp("fast");    //Slide Up Effect
 
        }
		
		//----------
		
		if ($(this).val() == "fixture removal and delivery" ) {
 
            $("#fixture_removal_and_delivery").slideDown("fast"); //Slide Down Effect
 
        } else {
 
            $("#fixture_removal_and_delivery").slideUp("fast");    //Slide Up Effect
 
        }
		
		//----------
		
		if ($(this).val() == "rubbish removal" ) {
 
            $("#rubbish_removal").slideDown("fast"); //Slide Down Effect
 
        } else {
 
            $("#rubbish_removal").slideUp("fast");    //Slide Up Effect
 
        }
		
		//----------
	
    });
	
});