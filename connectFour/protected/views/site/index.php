<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Welcome to <i>Lab 3</i></h1>

<p>Login as either an admin or a driver.</p>
<?php if(Yii::app()->user->isAdmin()):?>
Connect your Foursquare account
<a href="https://foursquare.com/oauth2/authenticate?client_id=ELPX20JKJSJO2VL1YVBKL0GDVNVTVVQNGBHBZH221GNQ2JMV&response_type=token&redirect_uri=http://beebe.asuscomm.com/CS462driver" >Here </a>
<?php endif;?>