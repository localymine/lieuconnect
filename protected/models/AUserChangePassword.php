<?php

/**
 * UserChangePassword class.
 * UserChangePassword is the data structure for keeping
 * user change password form data. It is used by the 'changepassword' action of 'UserController'.
 */
class AUserChangePassword extends CFormModel {

    public $oldPassword;
    public $password;
    public $verifyPassword;

    public function rules() {
        return Yii::app()->controller->id == 'recovery' ? array(
            array('password, verifyPassword', 'required'),
            array('password, verifyPassword', 'length', 'max' => 128, 'min' => 4, 'message' => Common::t("Incorrect password (minimal length 4 symbols).", 'account')),
            array('verifyPassword', 'compare', 'compareAttribute' => 'password', 'message' => Common::t("Retype Password is incorrect.", 'account')),
                ) : array(
            array('oldPassword, password, verifyPassword', 'required'),
            array('oldPassword, password, verifyPassword', 'length', 'max' => 128, 'min' => 4, 'message' => Common::t("Incorrect password (minimal length 4 symbols).", 'account')),
            array('verifyPassword', 'compare', 'compareAttribute' => 'password', 'message' => Common::t("Retype Password is incorrect.", 'account')),
            array('oldPassword', 'verifyOldPassword'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'oldPassword' => Common::t("Old Password", 'account'),
            'password' => Common::t("Password", 'account'),
            'verifyPassword' => Common::t("Retype Password", 'account'),
        );
    }

    /**
     * Verify Old Password
     */
    public function verifyOldPassword($attribute, $params) {
        if (User::model()->notsafe()->findByPk(Yii::app()->user->id)->password != Yii::app()->getModule('user')->encrypting($this->$attribute))
            $this->addError($attribute, Common::t("Old Password is incorrect.", 'account'));
    }

}
