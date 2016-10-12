<?php

  include $_SERVER['DOCUMENT_ROOT'] . '/config.php'; 
  include BASEPATH . 'admin/admin_modules.php';

  $default_lang = $default_lang_admin;

  require_once("admin_functions.php");
  echo "
  <html>
  <head>
  <title>". Dictionary::GetAdminWord(836) ."</title>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <link rel='stylesheet' title='text style' href='$lovely_css' type='text/css' />
  </head><body style=\"background:#000;heaght:100%\">";

  $message = "". Dictionary::GetAdminWord(837) ." $base_url/admin";
  $result = DB::Query("select * from `?_admin`");
  while ($get_rows=DB::GetArray($result))
  {
    $num = $num +1;
    $message .= "\n\n". Dictionary::GetAdminWord(838) ." #$num: $get_rows[adminuser]\n". Dictionary::GetAdminWord(839) ." #$num: $get_rows[adminpass]";
  }

  $subject = Dictionary::GetAdminWord(840);

  include("../email.php");

  mail(_CONFIG_EMAIL,$subject,$message,"From: ". _CONFIG_EMAIL);


  echo "
  <br />
  <br />
  <div style=\"text-align:center;color:#fff;\">". Dictionary::GetAdminWord(841) ."</div>
  <br />
  <br />


  <div style=\"text-align:center\"><a href=\"index.php\" style=\"color:#fc8\">". Dictionary::GetAdminWord(106) ."</a></div>

  </body>
  </html>
  ";

?>