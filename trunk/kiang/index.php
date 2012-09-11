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
require 'config.php';

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
    $token = $facebook->getAccessToken();
}

// Login or logout url will be needed depending on current user state.
if ($user) {
    $logoutUrl = $facebook->getLogoutUrl();
} else {
    $loginUrl = $facebook->getLoginUrl(array(
        'scope' => array('friends_likes', 'friends_location', 'friends_photos', 'friends_status')
    ));
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Monkey Memory</title>
        <meta charset="utf-8">
        <!-- metadata -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <!--<link rel="stylesheet" href="css/bootstrap-responsive.min.css">-->
        <style>
            body{padding: 41px 0 0 0;overflow: hidden;}
            .navbar .avatar{padding: 6px 0 0 !important;}
            .navbar .avatar img{width: 30px;height: 30px;}
            //.box{height:1000px;background:url(img/bg.jpg);-moz-background-size:100% 100%;background-size:100%;background-repeat:no-repeat;}
        </style>
                <script src="js/jquery-1.8.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
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
                    <a class="brand" href="#">Monkey Memory</a>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li class="active"><a href="#">Total Recall</a></li>
                            <li><a href="#about">Super Friends</a></li>
                        </ul>
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
                                    <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
                                </li>
<?php endif ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="box">
            <form class="navbar-search pull-left">
                <input type="text" class="search-query" placeholder="Search">
            </form>
        </div>
        <script language="javascript">
            $(function() {
<?php
if ($user) {
    $friends = array();
    try {
        // Proceed knowing you have a logged in user who's authenticated.
        $data = $facebook->api('/' . $user . '/friends');
        foreach($data['data'] AS $friend) {
            $friends[] = array(
                'value' => $friend['name'],
                'label' => $friend['name'],
                'id' => $friend['id'],
            );
        }
    } catch (FacebookApiException $e) {
        error_log($e);
    }
    echo 'var friends = ' . json_encode($friends) . ';';
    ?>
        $('.search-query').autocomplete({
            minLength: 0,
            source: friends,
            select: function(event, ui) {
                
            }
        });
    <?php
}
?>
                
            });
        </script>
        <?php
        //pr($friends);
        pr($friends);
        //pr($facebook->api('/me/501216987/photos?access_token=' . $facebook->getAccessToken()));
        ?>
    </body>
</html>
