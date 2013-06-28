<?php
include('inc/tms_client.php');
include('inc/config.php');

if(isset($_POST['json'])) 
//if(1 == 1)
{
  $json_data = $_POST['json'];
   error_log("Input to tms_proxy: " . $json_data);
  // data for testing
  //$json_data = '{"from_name":"From Name","subject":"Test Subject","recipients":[{"email":"tor@flatebo.org"},{"email":"tor@takechances.net"}],"body":"Body body body"}';

  # get the client ready
  $tms = new TMSClient($tms_base_uri);
  $tms -> set_auth_token($tms_api_key);
  #$tms -> set_debug(true);

  #send it off
  $msg = $tms -> send_email($json_data);

  if(isset($msg -> _links))
  {
    //error_log("SUCCESS: sent message " . $msg -> _links -> self);
    echo("SUCCESS: sent message " . $msg -> _links -> self . "<br/>" . $msg -> body);
  }
  else
  {
    error_log("ERROR: Did not get a message back from TMS");
  }
}
else
{
  error_log("ERROR: Input to tms_proxy: " . var_dump($_POST));
  echo("ERROR: What are you trying to do to me?");
}
?>
