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
  $loginUrl = $GLOBALS['facebook']->getLoginUrl();
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
                echo $this->Html->script('main');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
    </head>
  <body><?php echo $this->fetch('content'); ?></body>
</html>