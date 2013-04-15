<?PHP
$baseUrl = Yii::app()->baseUrl;
?>
<div class="services gmail-disconnected hide" style ="width: 44%; margin: -330px auto 229px;">
    <ul class="auth-services clear" style="margin: 0px 0px 0px 120px;">
        <?php
        foreach ($services as $name => $service) {
            echo '<li class="auth-service ' . $service->id . ' social-setting">';
            if ($service->id == 'google_oauth') {
                ?>
                <div class ="social_activate_image google_signin_step" ><a href="/user/login?service=google_oauth&google_activate_service=true"><img src="<?php echo $baseUrl; ?>/img/grey_google.png" class="social_activate_image_setting" /></a></div>
                <div class="social_activate_content_setting" >you have not connected this network.<br/>you only have to do this once.</div>
                <!--<div href="#" class="btn btn-primary btn-large social_activate_btn google_signin_step" id ="login-google1">activate</div>-->
                <a class="btn btn-primary btn-large social_activate_btn google_signin_step" href="/user/login?service=google_oauth&google_activate_service=true">activate</a>

                <?php
            }
        }
        ?>
    </ul>
</div>