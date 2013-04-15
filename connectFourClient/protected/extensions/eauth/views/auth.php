<div class="services">
    <ul class="auth-services clear">
        <?php

        foreach ($services as $name => $service) {
            echo '<li class="auth-service ' . $service->id . '" style="float: none;">';

            if ($service->id == 'google_oauth') {
                ?>
                <a class="btn-auth btn-google login-element google_oauth" href="/connectFourclient/index.php/site/login?service=google_oauth"><?PHP print $page; ?> with google</a>
                <div style="margin: 10px;"><span style="color: #B1B1B1;">Connect Privately. We never post without permission.</span></div>
                <?php
            } elseif ($service->id == 'facebook') {
                ?>
                <a href="/connectFourclient/index.php/site/login?service=facebook" class="btn-auth btn-facebook-eauth login-element facebook"><?PHP print $page; ?> with facebook</a>
                <?php
            }

            echo '</li>';
        }
        ?>
    </ul>
</div>