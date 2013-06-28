<?php
include('httpful.phar');

class TMSClient 
{
  public $base_uri;
  public $port = 443;
  private $auth_token;

  private $debug = false;

  public function __construct($base_uri_in)
  {
    $this -> base_uri = $base_uri_in;
  }

  public function set_auth_token($auth_token_in)
  {
    $this -> auth_token = $auth_token_in;
  }

  public function get_request($uri_in)
  {
    $r = \Httpful\Request::get($uri_in);
    $r -> addHeader('X-AUTH-TOKEN', $this -> auth_token);
    $r -> expectsJSON();

    return $r;
  }

  public function post_request($uri_in, $body_in)
  {
    $r = \Httpful\Request::post($uri_in);
    $r -> addHeader('X-AUTH-TOKEN', $this -> auth_token);
    $r -> expectsJSON();
    $r -> sendsJSON();
    $r -> body($body_in);

    return $r;
  }

  public function get_services()
  {
    $request = $this -> get_request($this -> base_uri);
    $response = $request -> send();

    if (!$response->hasErrors()) 
    {
      if($this -> debug) { print_r($response -> body); }
      $services = $response -> body -> _links;
    } 
    else 
    {
      echo "Uh oh. TMS gave us the old {$response->code} status.\n";
      $services = null;
    } 

    return $services;

  }

  public function send_email($json_data)
  {
    $services = $this -> get_services();

    if(!$services -> email_messages)
    {
      print "no email sending services available\n";
      return null;
    }

    $request = $this -> post_request($this -> base_uri . $services -> email_messages, $json_data);
    if($this -> debug) { print_r($request); }

    $response = $request -> send();

    if (!$response->hasErrors()) 
    {
      if($this -> debug) { print_r($response -> body); }
      $msg_out = $response -> body;
    } 
    else 
    {
      echo "Uh oh. TMS gave us the old {$response->code} status.\n";
      $msg_out = null;
    }

    return $msg_out;

  }

  public function set_debug($bool_in)
  {
    $this -> debug = $bool_in;
  }

}
?>
