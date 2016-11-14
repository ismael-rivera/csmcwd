/*
 * Security Ninja
 * (c) 2011. Web factory Ltd
 */


jQuery(document).ready(function($){
  // alternate table rows
  $("#sn-tests-help tr:odd, #security-ninja tr:odd,").addClass('alternate');

  // init tabs
  $("#tabs").tabs();

  // just to make sure the button is not stuck
  $('#run-tests').removeAttr('disabled');

  // run tests, via ajax
  $('#run-tests').click(function(){
    var data = {action: 'sn_run_tests'};

    $(this).attr('disabled','disabled')
           .val('Running tests, please wait!');
    $.blockUI({ message: 'Security Ninja is analyzing your site.<br />Please wait, it can take a few minutes.' });

    $.post(ajaxurl, data, function(response) {
      if (response != '1') {
        alert('Bad AJAX response. Page will automatically reload.');
        window.location.reload();
      } else {
        window.location.reload();
      }
    });
  }); // run tests

  // show test details/help tab
  $('.sn-details').click(function(){
    $("#tabs").tabs('select', '#sn_help');

    // get the link anchor and scroll to it
    target = this.href.split("#")[1]; console.log(target);
    $.scrollTo('#' + target);

    return false;
  }); // show test details
});