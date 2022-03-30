<?php

function createBase64($string) {
    $urlCode = base64_encode($string);
    return str_replace(array('+','/','='),array('-','_',''),$urlCode);
}

function decodeBase64($base64ID) {
    $data = str_replace(array('-','_'),array('+','/'),$base64ID);
    $mod4 = strlen($data) % 4;
    if ($mod4) {
            $data .= substr('====', $mod4);
    }
    return base64_decode($data);
}

function alphanumeric_random_string($string_length=8){
	$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	return substr(str_shuffle($permitted_chars), 0, $string_length);
}

function all_errors($errors){
    $arr = [];
    foreach ($errors as $key => $value) {
        $arr[$key] = $value;
    }
    return $arr;
}

function contact_no($contact_no){
    if(!preg_match('/^(\+|00)[0-9]{1,3}[0-9]{4,14}(?:x.+)?$/', $contact_no))
    {
        return FALSE;
    }
    else{
        return TRUE;
    }
}

function klarnaSession($amount = 0,$products = [])
{
  if(empty($amount)){
    return false;
  }
  $CI = & get_instance();
  $post = [
      "locale" => "en-GB",
      "purchase_country" => "GB",
      "purchase_currency" => "GBP",
      "order_amount" => $amount,
      "order_tax_amount" => 0,
      "order_lines" => $products
  ];
  // debug($post , false);
  // echo json_encode($post);
  $api = KLARNA_URL."payments/v1/sessions";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $api);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST' );
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
  curl_setopt($ch, CURLOPT_HTTPHEADER , array(
      "Content-Type:application/json",
      "Authorization: Basic ".base64_encode(KLARNA_CREDENTIALS)
  ));
  $response = curl_exec( $ch );
  curl_close( $ch );
  //debug($response , true);
  $response = json_decode($response , true);
  if(!empty($response['session_id'])){
    return $response['client_token'];
  }
  return false;
}

function getCoupon()
{
  $CI = & get_instance();
  $CI->load->model('users_model' , 'users');
  $CI->load->model('coupons_model' , 'coupons');
  $CI->load->model('coupon_users_model' , 'coupon_users');
  $date = date('Y-m-d');
  if($CI->session->userdata('user_id')){
    $user_id = $CI->session->userdata('user_id');
    $where = "user_id = '".$user_id."'";
    $user = $CI->users->get_where('*', $where, true, '', 1, '');
    $user = $user[0];

    $today = date("Y-m-d");
    $expire = $user['trial_end_date'];
    $today_time = strtotime($today);
    $expire_time = strtotime($expire);
    
    $private_coupon = false;
    if($user['subscription_end_date']!='0000-00-00'){
      $private_coupon = true;
    }
    elseif($expire_time >= $today_time){
      $private_coupon = true;
    }
    
    if($private_coupon==true){
      $where = "user_id = '".$user_id."' AND is_active = 1";
      $res = $CI->coupon_users->get_where('*', $where, true, '', 1, '');
      
     // debug($res , true);
      
      if(!empty($res)){
        $coupon_id = $res[0]['coupon_id'];
        $where = "id = '".$coupon_id."' AND is_active = 1 AND privacy = 2 AND end_date >= '".$date."'";
        $result = $CI->coupons->get_where('*', $where, true, 'id DESC', 1, '');
      }
    }
  }
  else{
    $where = "is_active = 1 AND privacy = 1 AND end_date >= '".$date."'";
    $result = $CI->coupons->get_where('*', $where, true, 'id DESC', 1, '');
  }
  
  if(!empty($result)){
    return $result[0];
  }
  return false;
}

function checkSubscription()
{
  $CI = & get_instance();
  $CI->load->model('users_model' , 'users');
  if($CI->session->userdata('user_id')){
    $user_id = $CI->session->userdata('user_id');
    $where = "user_id = '".$user_id."'";
    $user = $CI->users->get_where('*', $where, true, '', 1, '');
    $user = $user[0];

    $today_time = time();
    if($user['subscription_end_date']!='0000-00-00'){
      $expire_time = strtotime($user['subscription_end_date']);
      if($expire_time >= $today_time){
        return true;
      }
    }
    else{
      $expire_time = strtotime($user['trial_end_date']);
      if($expire_time >= $today_time){
        return true;
      }
    }
  }
  return false;
}

function couponDetail($coupon_id = '' , $product_id = '')
{
  $CI = & get_instance();
  $CI->load->model('coupons_model' , 'coupons');
  $CI->load->model('coupon_products_model' , 'coupon_products');
  $date = date('Y-m-d');
  $where = "id = '".$coupon_id."' AND is_active = 1 AND end_date >= '".$date."'";
  $result = $CI->coupons->get_where('*', $where, true, 'id DESC', 1, '');
  $where = "coupon_id = '".$coupon_id."' AND product_id = '".$product_id."'";
  $result2 = $CI->coupon_products->get_where('*', $where, true, '', '', '');
  if(!empty($result) && !empty($result2)){
    return $result[0]['discount_percentage'];
  }
  return false;
}

function sellOnProduct()
{
  $CI = & get_instance();
  $CI->load->model('products_model' , 'products');
  $where = "upsell_on = '1'";
  $result = $CI->products->get_where('*', $where, true, '', 1, '');
  return $result[0];
}

function deliverCommission($reff_id=0,$order_id='',$order_amount='')
{
  if(empty($reff_id) || empty($order_id) || empty($order_amount)){
    return false;
  }
  $CI = & get_instance();
  $CI->load->model('commissions_model' , 'commissions');
  $user_id = null;
  if($CI->session->userdata('user_id')){
    $user_id = $CI->session->userdata('user_id');
  }
  $commission = (COMMISSION_PERCENTAGE/100)*$order_amount;
  $save = [];
  $save['reff_id'] = $reff_id;
  $save['user_id'] = $user_id;
  $save['order_id'] = $order_id;
  $save['commission_per'] = COMMISSION_PERCENTAGE;
  $save['commission_amount'] = $commission;
  $save['order_amount'] = $order_amount;
  $CI->commissions->save($save);
  $CI->session->unset_userdata('order_amount');

  return true;
}

function send_email_to($email='',$message='',$subject='',$attachment='')
{
    $CI = get_instance();
    $CI->load->library('email');
    $CI->email->initialize(array(
        'protocol'     => 'smtp',
        'smtp_host'    => 'mail.uflow.co.uk',
        'smtp_port'    => '587',
        'smtp_timeout' => '7',
        'smtp_user'    => USER_EMAIL,
        'smtp_pass'    => USER_PASS,
        'newline'   => "\r\n",
        'mailtype'=>'html',
        'charset'=>'utf-8',
        'starttls'=>true,
        'wordwrap'=>true
    ));

    $page = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- So that mobile will display zoomed in -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- enable media queries for windows phone 8 -->
  <meta name="format-detection" content="telephone=no"> <!-- disable auto telephone linking in iOS -->
  <title></title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
<style type="text/css">
body {margin: 0;padding: 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;}
@media screen and (max-width:640px) {
.mob{right: 0!important;}
}   
@media screen and (max-width: 480px) {
  .container {width: auto!important;margin-left:10px;margin-right:10px;}
.mob{position: relative!important;text-align: center;top: 10px !important;right: 0!important;}
.mob img{width: 240px;height:auto;}
}
</style>
</head>
<body style="margin:0; padding:0;"  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#eff6e4" style="font-family: "Open Sans", sans-serif;">
  <tr>
    <td align="center" valign="top" ><br>      
      
      <table border="0" width="600" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px">
        <tr><td align="left" style="font-size:18px;font-weight:bold;padding-bottom:12px;color:#a964a7;padding-left:5px;padding-right:5px">
              <div style="text-align:Center;">Welcome To Uflow!</div>
          </td>
        </tr>
        <tr>
          <td align="left" style="position:relative;padding-left:24px;padding-right:24px;padding-top:24px;padding-bottom:24px;border:3px solid #f7d5ed;background-color:#ffffff;border-radius:14px;-moz-border-radius:14px;-webkit-border-radius:14px;">
           <div style="font-size:25px;font-weight:700; padding-bottom: 35px; color:#a964a7"> hy! </div>
            <div style="font-size:14px;line-height:20px;text-align:left;color:#333333"><br><br>
              <div style="font-size:18px;font-weight:700;color:#5e3368; padding-bottom:10px;"> '.$subject.' </div>
              '.$message.'
              <br><br>
          </td>
        </tr>
        </table></td></tr></table>
    </body></html>';

    $CI->email->clear();
    $CI->email->to($email);
    $CI->email->from(USER_EMAIL);
    $CI->email->subject($subject);
    $CI->email->message($page);
    if(!empty($attachment)){
      $CI->email->attach(base_url().'assets/uploads/'.$attachment);
    }
    $CI->email->send();
    //echo $CI->email->print_debugger(); exit;
    return false;
}

function pre_order_mail($name='',$email='',$message='',$subject='')
{
    $CI = get_instance();
    $CI->load->library('email');
    $CI->email->initialize(array(
        'protocol'     => 'smtp',
        'smtp_host'    => 'mail.uflow.co.uk',
        'smtp_port'    => '587',
        'smtp_timeout' => '7',
        'smtp_user'    => USER_EMAIL,
        'smtp_pass'    => USER_PASS,
        'newline'   => "\r\n",
        'mailtype'=>'html',
        'charset'=>'utf-8',
        'starttls'=>true,
        'wordwrap'=>true
    ));

    $page = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- So that mobile will display zoomed in -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- enable media queries for windows phone 8 -->
  <meta name="format-detection" content="telephone=no"> <!-- disable auto telephone linking in iOS -->
  <title></title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
<style type="text/css">
body {margin: 0;padding: 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;}
@media screen and (max-width:640px) {
.mob{right: 0!important;}
}   
@media screen and (max-width: 480px) {
  .container {width: auto!important;margin-left:10px;margin-right:10px;}
.mob{position: relative!important;text-align: center;top: 10px !important;right: 0!important;}
.mob img{width: 240px;height:auto;}
}
</style>
</head>
<body style="margin:0; padding:0;"  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#eff6e4" style="font-family: "Open Sans", sans-serif;">
  <tr>
    <td align="center" valign="top" ><br>      
      
      <table border="0" width="600" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px">
        <tr><td align="left" style="font-size:18px;font-weight:bold;padding-bottom:12px;color:#a964a7;padding-left:5px;padding-right:5px">
              <div style="text-align:Center;">Welcome To Uflow!</div>
          </td>
        </tr>
        <tr>
          <td align="left" style="position:relative;padding-left:24px;padding-right:24px;padding-top:24px;padding-bottom:24px;border:3px solid #f7d5ed;background-color:#ffffff;border-radius:14px;-moz-border-radius:14px;-webkit-border-radius:14px;">
           <div style="font-size:25px;font-weight:700; color:#a964a7">'.$name.'! </div>
            <div style="font-size:14px;line-height:20px;text-align:left;color:#333333">
              '.$message.'
              <br><br>
          </td>
        </tr>
        </table></td></tr></table>
    </body></html>';

    $CI->email->clear();
    $CI->email->to($email);
    $CI->email->from(USER_EMAIL);
    $CI->email->subject($subject);
    $CI->email->message($page);
    if(!empty($attachment)){
      $CI->email->attach($attachment);
    }
    $CI->email->send();
    //echo $CI->email->print_debugger(); exit;
    return false;
}

function send_sale_email($name='',$email='',$message='',$subject='')
{
    $CI = get_instance();
    $CI->load->library('email');
    $CI->email->initialize(array(
        'protocol'     => 'smtp',
        'smtp_host'    => 'mail.uflow.co.uk',
        'smtp_port'    => '587',
        'smtp_timeout' => '7',
        'smtp_user'    => SALES_EMAIL,
        'smtp_pass'    => SALES_PASS,
        'newline'   => "\r\n",
        'mailtype'=>'html',
        'charset'=>'utf-8',
        'starttls'=>true,
        'wordwrap'=>true
    ));

    $page = '<!DOCTYPE html>
<html>
<head>
    <title>UFLow</title>
   <style>
   .unord_lst{
       list-style: none;
   }
       .brd_lft {
    border-left: 2px solid black;
    height: 470px;
}
.inr_lstng{list-style: none;}
.Log0_eml {
    position: absolute;
    display: inline-flex;
}
ul.eml_listng::before {
    content: "";
    display: block;
    height: 135px;
    width: 1px;
    background: blue;
    left: 16px;
    position: absolute;
}
ul.eml_listng {
    position: relative;

}
.eml_listng li {
    list-style: none;
}
.img_lft-logo {
    margin-right: 30px;
}
.sls_tm {
    color: #EF9A26;
    font-size: 22px;
}
.adr_hdng {
    color: blue;
    font-weight: 600;
}
.sls_tm_pb {
    padding-bottom: 15px;
}
a{
    text-decoration: none;
}
.ww_clr {
    color: #F2AA26;
}

   </style>
</head>
<body>
<div class="main_container" id="main_con_id">
    <div class="brd_lft">
    <ul class="unord_lst">
        
    <p style="line-height:16px">
    <p>Hi '.$name.'</p> 
    Thank you for your order ! <br><br>
 

The estimated delivery time is between 2-5 days on stock items.<br/> 
If you have ordered our bundle deals or some Uflow Keg ranges, we have longer delivery times. We will keep you upto date on when your order will be shipped, and tracking numbers sent to you.

<br><br>
    Hope you have joined the group, if not please join here. https://facebook.com/groups/uflowkeg/ <br><br>
    Thank you for your business! <br><br>
    
    U-Flow Team
    
    </p>

    </ul>
    <div class="Log0_eml">
    

        <div class="eml_adrs">
            <ul class="eml_listng">
                <li class="sls_tm_pb">
                <span class="sls_tm">   Sales Team </span>
                </li>
                <li>
                <span class="adr_hdng"> Address:</span> U-Flow | 291-305 Blackpool Enterprise Lytham<br/>
                 Road, Blackpool, Lancs, FY4 1EW
                </li>
                <li>
                <span class="adr_hdng"> Email: </span> 
                <a href="#">sales@uflow.co.uk</a>
                </li>
                <li>
                    <span class="adr_hdng">Web: </span> 
                    <a href="#"><span class="ww_clr">www.uflow.co.uk </span></a>
                </li>
                <li>
                    <span class="adr_hdng">Phone:</span> 01253 928 010
                </li>
            </ul>
        </div>
    </div>
</div>
</div>
</body>
</html>';

    $CI->email->clear();
    $CI->email->to($email);
    $CI->email->from('noreply@uflow.co.uk');
    $CI->email->subject('Customer Uflow');
    $CI->email->message($page);
    $CI->email->send();
    //echo $CI->email->print_debugger(); exit;
    return false;
}

?>