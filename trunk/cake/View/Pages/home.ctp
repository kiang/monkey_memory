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
                  location.href = "<?php echo $this->Html->url('/posts/index/'); ?>" + ui.item.id;
              }
          });
    <?php
}
?>
                
});
</script>