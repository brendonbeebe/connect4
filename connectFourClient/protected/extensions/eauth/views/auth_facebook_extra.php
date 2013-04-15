<div class="animated fadeInUpBig services">
    <ul class="auth-services clear">
        <?php
        foreach ($services as $name => $service) {

            echo '<li class="auth-service ' . $service->id . '" style="float: none;">';

            if ($service->id == 'facebook_extra') {
                ?>
                <!--<a href="/user/login?service=facebook_extra&facebook_extra_single=true" class="btn-auth btn-facebook-eauth login-element facebook">facebook_extra_s</a>-->
                <!--<a href="/user/login?service=facebook_extra&facebook_extra_matchmaker=true" class="btn-auth btn-facebook-eauth login-element facebook">facebook_extra_m</a>-->
                <div>
                    <div class="main-left">
                        <!--<a class="main-button" href="<?PHP // print $this->createUrl('suggestions/SingleSuggestions');    ?>"><img src="/img/single_button.png">-->
                        <a href="/user/login?service=facebook_extra&facebook_extra_single=true" class="main-button" id="main_button"><img src="/img/single_button.png">
                            <br>
                            <br>
                            I am Single
                            </br>
                            </br>
                        </a>
                        <div class="hint">Suggestions for Yourself</div>
                    </div>
                    <div class="main-right">
                        <!--<a class="main-button" href="<?PHP // print $this->createUrl('suggestions/MatchmakerSuggestions');  ?>"><img src="/img/matchmaker_button.png">-->
                        <a href="/user/login?service=facebook_extra&facebook_extra_matchmaker=true" class="main-button" id="matchmaker_button"><img src="/img/matchmaker_button.png">
                            <br>
                            <br>
                            I am a MatchMaker
                            </br>
                            </br>
                        </a>
                        <div class="hint">Suggestions for someone else you'd like to Match</div>
                    </div>
                </div>
                <?php
            }

            echo '</li>';
        }
        ?>
    </ul>
</div>