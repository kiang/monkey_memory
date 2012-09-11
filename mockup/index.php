<?php
/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require 'api/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '153310081475699',
  'secret' => 'cf24b60cf2d6c37c72da2bab07bb3ec2',
));

// Get User ID
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $loginUrl = $facebook->getLoginUrl();
}

// This call will always work since we are fetching public data.
$naitik = $facebook->api('/naitik');

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Monkey Memory</title>
    <meta charset="utf-8">
    <!-- metadata -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
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
          <a class="brand" href="#"><img src="img/logo.png"></a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="#">Total Recall</a></li>
              <li><a href="#about">Super Friends</a></li>
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
                <a href="<?php echo $loginUrl; ?>"><img src="img/fb_login_icon.gif"></a>
              </li>
              <?php endif ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="box">
      <div class="content clearfix">
          <div id="container">
              <div class="item">1</div>
              <div class="item">1</div>
              <div class="item">1</div>
              <div class="item">1</div>
          </div>
      </div>
    </div>

    <script src="js/jquery-1.8.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    
    <script src="js/modernizr-transitions.js"></script>
    <script src="js/jquery.masonry.min.js"></script>
    <script src="js/main.js"></script>
<script>
  $(function(){
    
    var $container = $('#container');
    
    $container.masonry({
      itemSelector: '.item',
      columnWidth: 240,
      isAnimated: !Modernizr.csstransitions
    });
  });
</script>
  </body>
</html>
