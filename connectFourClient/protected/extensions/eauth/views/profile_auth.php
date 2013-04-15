<?php
$detect = new Mobile_Detect();
if ($detect->isMobile()) {
    ?>
    <table>
        <?php
        foreach ($services as $name => $service) {
            if ($service->id == 'google_oauth') {
                ?>
                <tr>
                <td id="left-icon">
                    <i class="icon-google-plus-sign icon-large"></i>
                </td>
                <?php
                if (!in_array('Google', $connected_social_services)) {
                    ?>
                    <td id="right-side">
                        <a class="btn edit-pro-btn" href="/user/login?service=google_oauth&for_unlink_service=true">Connect Google</a>
                    </td>
                    <?php if ($social_error == 'Google') { ?>
                        <div class="social_link_unlink_message"><span>This social service has already used.</span></div>
                        <?php
                    }
                } else {
                    ?>
                    <td id="right-side">
                        Connected
                        <a href="/user/unlinkSocialService?service_name=Google" data-target="unlink" class="delete-social-service unlink-service btn edit-pro-btn">Unlink</a>
                    </td>   
                </tr>
                <?php
            }
        } elseif ($service->id == 'facebook') {
            ?>
            <tr>
            <td id="left-icon">
                <i class="icon-facebook-sign icon-large"></i>
            </td>
            <?php
            if (!in_array('Facebook', $connected_social_services)) {
                ?>
                <td id="right-side">
                    <a href="/user/login?service=facebook&for_unlink_service=true" class="btn-auth btn-facebook-eauth login-element facebook" style="margin-left: 0px;">Connect Facebook</a> 
                </td>
                <?php if ($social_error == 'Facebook') { ?>
                    <div class="social_link_unlink_message"><span>This social service has already used.</span></div>
                    <?php
                }
            } else {
                ?>
                <td id="right-side">
                    Connected
                    <a href="/user/unlinkSocialService?service_name=Facebook" data-target="unlink" class="delete-social-service unlink-service btn edit-pro-btn">Unlink</a>
                </td>   
                </tr>
                <?php
            }
        }
    }
    ?>
    </table>
<?php } else { ?>
    <div class="services">
        <ul class="auth-services clear" style ="margin-top: 10px;">
            <?php
            foreach ($services as $name => $service) {
                echo '<li class="auth-service ' . $service->id . '" style="width: 100%;">';
                if ($service->id == 'google_oauth') {
                    ?>
                    <hr>
                    <div class="social-icon-label">Google</div>
                    <?php
                    if (!in_array('Google', $connected_social_services)) {
                        ?>
                        <a class="btn-auth btn-google login-element google_oauth" href="/user/login?service=google_oauth&for_unlink_service=true">Connect now</a>
                        <?php if ($social_error == 'Google') { ?>
                            <div class="social_link_unlink_message"><span>This social service has already used.</span></div>
                            <?php
                        }
                    } else {
                        ?>
                        <span class="social-service-connected">Connected</span><a href="/user/unlinkSocialService?service_name=Google" data-target="unlink" class="delete-social-service unlink-service">Unlink</a>
                        <?php
                    }
                } elseif ($service->id == 'facebook') {
                    ?>
                    <hr>
                    <div class="social-icon-label">Facebook</div>
                    <?php
                if(!in_array('Facebook', $connected_social_services)){
                        ?>
                        <a href="/user/login?service=facebook&for_unlink_service=true" class="btn-auth btn-facebook-eauth login-element facebook" style="margin-left: 0px;">Connect now</a> 
                    <?php if($social_error == 'Facebook') { ?>
                            <div class="social_link_unlink_message"><span>This social service has already used.</span></div>
                    <?php } 
                }else {?>
                        <span class="social-service-connected">Connected</span><a href="/user/unlinkSocialService?service_name=Facebook" data-target="unlink" class="delete-social-service unlink-service">Unlink</a>
                        <?php
                    }
                }
            }
            ?>
        </ul>

    </div>

<?php } ?>