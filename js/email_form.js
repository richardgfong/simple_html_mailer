$(function() {
  $('.error').hide();
  $('.text-input').css({backgroundColor:"#FFFFFF"});
  $('.text-input').focus(function(){
    $(this).css({backgroundColor:"#FFDDAA"});
  });
  $('.text-input').blur(function(){
    $(this).css({backgroundColor:"#FFFFFF"});
  });

  $(".button").click(function() 
  {
    // validate and process form
    // first hide any error messages
    $('.error').hide();

    // instantiate the tms_msg object
    var tms_msg = new Object();
		
    tms_msg.from_name = $("input#email_from_name").val();
    //alert(from_name);
    if (tms_msg.from_name == "") 
    {
      $("label#email_from_name_error").show();
      $("input#email_from_name").focus();
      return false;
    }
    tms_msg.subject = $("input#email_subject").val();
    //alert(from_name);
    if (tms_msg.subject == "") 
    {
      $("label#email_subject_error").show();
      $("input#email_subject").focus();
      return false;
    }
    tms_msg.recipients = $("textarea#email_recipients").val().split(",");
    //alert(from_name);
    if (tms_msg.recipients == "") 
    {
      $("label#email_recipients_error").show();
      $("textarea#email_recipients").focus();
      return false;
    }

    // convert the email addresses to recipient objects
    tms_msg.recipients = tms_msg.recipients.map(addressToRecipient); 

    tms_msg.body = $("textarea#email_body").val();
    //alert(tms_msg.body);
    if (tms_msg.body == "") 
    {
      $("label#email_body_error").show();
      $("textarea#email_body").focus();
      return false;
    }
		
    // get the JSON ready to pass to the php script
    var json_data = {json: JSON.stringify(tms_msg)};

    // if you want to debug the JSON string, uncomment the next two lines
    //var json_data = JSON.stringify(tms_msg);
    //alert (json_data);return false;
		
    $.ajax({
      type: "POST",
      url: "tms_proxy.php",
      data: json_data,
      success: function(response_data) {
        $('#the_form').html("<div id='message'></div>");
        $('#message').html("<div>" + response_data + "</div>")
        .hide()
        .fadeIn(1500, function() {
          $('#message').prepend("<img id='checkmark' src='images/check.png' />");
        });
      }
     });
    return false;
  });
});

function addressToRecipient(address) 
{
  var recip = new Object();
  recip.email = address;
  return recip; 
}
