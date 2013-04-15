<?php

// this file must be stored in:
// protected/components/WebUser.php

class WebUser extends CWebUser {

    // Store model to not repeat query.
    private $_model;


    // This is a function that checks the field 'role'
    // in the User model to be equal to 1, that means it's admin
    // access it by Yii::app()->user->isAdmin()
    function isAdmin(){

        if(Yii::app()->user->isGuest)
            return false;
        $user = $this->loadUser(Yii::app()->user->id);
        return $user->role == "admin";
    }

    // This is a function that checks the field 'role'
    // in the User model to be equal to 1, that means it's admin
    // access it by Yii::app()->user->isAdmin()
    function isFlowerShop(){
        if(Yii::app()->user->isGuest)
            return false;
        $user = $this->loadUser(Yii::app()->user->id);

        return $user->role == "flowershop";
    }

    // Load user model.
    protected function loadUser($id=null)
    {
        if($this->_model===null)
        {
            if($id!==null)
                $this->_model=User::model()->findByPk($id);
        }
        return $this->_model;
    }
}
?>