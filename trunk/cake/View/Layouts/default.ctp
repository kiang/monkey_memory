<?php
// Get User ID
$user = $GLOBALS['facebook']->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $GLOBALS['facebook']->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $GLOBALS['facebook']->getLogoutUrl();
} else {
  $loginUrl = $GLOBALS['facebook']->getLoginUrl(array(
      'scope' => array('read_stream',)
  ));
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Monkey Memory</title>
    <meta charset="utf-8">
    <!-- metadata -->
    <?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('bootstrap.min');
                echo $this->Html->css('main');
                echo $this->Html->script('jquery-1.8.1.min');
                echo $this->Html->script('bootstrap.min');
                echo $this->Html->script('modernizr-transitions');
                echo $this->Html->script('jquery.masonry.min');
                echo $this->Html->script('main');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
    </head>
  <body>
      <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#"><img src="<?php echo $this->Html->url('/'); ?>img/logo.png"></a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li><a href="#">Total Recall</a></li>
              <li class="active"><a href="#about">Super Friends</a></li>
            </ul>
            <form action="" class="navbar-search pull-left">
              <input type="text" placeholder="Recall Memory" class="search-query" id="test">
            </form>
            <ul class="nav pull-right">
              <?php if ($user): ?>
              <li>
                <a href="#" class="avatar"><img src="https://graph.facebook.com/<?php echo $user; ?>/picture"></a>
              </li>
              <li>
                <a href="<?php echo $logoutUrl; ?>">Logout</a>
              </li>
              <?php else: ?>
              <li>
                <a href="<?php echo $loginUrl; ?>"><img src="<?php echo $this->Html->url('/'); ?>img/fb_login_icon.gif"></a>
              </li>
              <?php endif ?>
            </ul>
          </div>
        </div>
      </div>
    </div><div class="box friend">
      <div class="content clearfix">
          <div id="container">
              <?php echo $this->fetch('content'); ?>
          </div>
      </div>
    </div>
  </body>
</html>