<?php
$baseUrl = Yii::app()->baseUrl;
?>
<div class="services">
    <ul class="auth-services clear" style ="margin-top: 10px; overflow: hidden;">
        <?php
        foreach ($services as $name => $service) {
            echo '<li class="auth-service ' . $service->id . '" style="width: 100%;">';
            if ($service->id == 'facebook') {
                ?>
                <div class = "iphone-social-login">
                    <center>
                        <a class = "iphone-facebook-login-btn" href = "/user/login?service=facebook&iphone=true"><img style = "width:40px;margin:-5px 15px 0px 0px;" src = "<?php echo $baseUrl; ?>/img/iphone-login-fbicon.png"/><?PHP print $page; ?> with Facebook</a>
                        <p>Connect Privately. We never post without permission</p>
                    </center>
                </div>
                <?php
            }
        }
        ?>
    </ul>
</div>


