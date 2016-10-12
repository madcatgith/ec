<html>
<head>
  <title>{Dictionary::GetAdminWord(345)}&#171;{$base_url}&#187;!</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

  <script type="text/javascript" src="/media/js/translit.js"></script>
  <script type="text/javascript" src="/admin/js/jquery-1.3.2.min.js"></script>
  <script type="text/javascript" src="/admin/js/easyTooltip.js"></script>
  <script type="text/javascript" src="/admin/js/jquery-ui-1.7.2.custom.min.js"></script>
  <script type="text/javascript" src="/admin/js/jquery.wysiwyg.js"></script>
  <script type="text/javascript" src="/admin/js/hoverIntent.js"></script>
  <script type="text/javascript" src="/admin/js/superfish.js"></script>
  <script type="text/javascript" src="/admin/js/custom.js"></script>
  <script type="text/javascript" src="/media/js/ui.dialog.js"></script>
  <script type="text/javascript" src="/media/js/swfobject.js"></script>
  <script type="text/javascript" src="/admin/js/colorpicker.js"></script>
  <script type="text/javascript" src="/admin/js/Flash.js"></script>
  <script type="text/javascript" src="/admin/js/flashManual.js"></script>
  <script type="text/javascript" src="/admin/js/def.js"></script>
  <script type="text/javascript" src="/admin/js/jstree/jquery.tree.min.js"></script>
  <script type="text/javascript" src="/admin/js/jstree/plugins/jquery.tree.checkbox.js"></script>

  <link rel="stylesheet" type="text/css" title="text style" href="/admin/tags.css" />
  <link rel="stylesheet" type="text/css" title="text style" href="/admin/colorpicker.css" />
  <link rel="stylesheet" type="text/css" title="text style" href="/admin/css/ui-lightness/jquery-ui-1.7.2.custom.css" />

  <link type="text/css" href="/admin/css/layout.css" rel="stylesheet">
</head>
<body>
  <?php if($no_top == ''): ?>
    <div id="header">

        <!-- Top -->
        <div id="top">
          <!-- Logo -->
          <div class="logo">
            <a href="/admin/" title="Administration Home" class="tooltip"><img src="/admin/assets/logo.png" alt="Wide Admin"></a>
          </div>
          <!-- End of Logo -->

          <!-- Meta information -->
          <div class="meta">
            <p>Welcome, Johnatan Doe! <a href="#" title="1 new private message from Elaine!" class="tooltip">1 new message!</a></p>
            <ul>
              <li><a href="#" title="End administrator session" class="tooltip"><span class="ui-icon ui-icon-power"></span>Logout</a></li>
              <li><a href="#" title="Change current settings" class="tooltip"><span class="ui-icon ui-icon-wrench"></span>Settings</a></li>
              <li><a href="#" title="Go to your account" class="tooltip"><span class="ui-icon ui-icon-person"></span>My account</a></li>
            </ul>
          </div>
          <!-- End of Meta information -->
        </div>
        <!-- End of Top-->

        <!-- The navigation bar -->
        <?php if($no_menu == ''): ?>
          <?=$menu; ?>
        <?php endif; ?>
        <!-- End of navigation bar" -->

        <!-- Search bar -->
        <div id="search">
          <form action="/search/" method="POST">
            <p>
              <input type="submit" value="" class="but">
              <input type="text" name="q" value="Search the admin panel" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;">
            </p>
          </form>
        </div>
        <!-- End of Search bar -->

      </div>
  <?php endif; ?>

</body>
</html>