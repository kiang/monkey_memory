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
                            <a href="<?php echo $loginUrl; ?>">Login</a>
                        </li>
                    <?php endif ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="box">
    <div class="content clearfix">
        <div class="post">
            <div class="item">
                <div class="shadow"></div>
                <div class="data">
                    <img src="http://a6.sphotos.ak.fbcdn.net/hphotos-ak-snc7/418819_10151076389362736_1925098142_n.jpg">
                    111111111111
                    <a href="#">2222222222</a>
                </div>
            </div>
            <div class="item">

            </div>
        </div>
        <div class="photo">
            <div class="item">
                1
            </div>
            <div class="item">
                1
            </div>
        </div>
        <div class="comment">
            <div class="item">
                1
            </div>
            <div class="item">
                1
            </div>
        </div>
        <div class="like">
            <div class="item">
                1
            </div>
            <div class="item">
                1
            </div>
        </div>
    </div>
</div>
<script language="javascript">
    $(function() {
<?php
if ($user) {
    $friends = array();
    try {
        // Proceed knowing you have a logged in user who's authenticated.
        $data = $GLOBALS['facebook']->api('/' . $user . '/friends');
        foreach ($data['data'] AS $friend) {
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
                  location.href = "<?php echo $this->Html->url('/posts/'); ?>" + ui.item.id;
              }
          });
    <?php
}
?>
                
});
</script>