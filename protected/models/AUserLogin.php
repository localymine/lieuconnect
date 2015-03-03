<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class AUserLogin extends CFormModel {

    public $username;
    public $password;
    public $rememberMe;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // username and password are required
            array('username, password', 'required'),
            // rememberMe needs to be a boolean
            array('rememberMe', 'boolean'),
            // password needs to be authenticated
            array('password', 'authenticate'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'rememberMe' => UserModule::t("Remember me next time"),
            'username' => UserModule::t("username or email"),
            'password' => UserModule::t("password"),
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params) {
        if (!$this->hasErrors()) {  // we only want to authenticate when no input errors
            $identity = new UserIdentity($this->username, $this->password);
            $identity->role = 'user';
            $identity->authenticate();
            switch ($identity->errorCode) {
                case UserIdentity::ERROR_NONE:
                    $duration = $this->rememberMe ? Yii::app()->controller->module->rememberMeTime : 0;
                    Yii::app()->user->login($identity, $duration);
                    break;
                case UserIdentity::ERROR_EMAIL_INVALID:
                    $this->addError("username", Common::t("Email is incorrect.", 'account'));
                    break;
                case UserIdentity::ERROR_USERNAME_INVALID:
                    $this->addError("username", Common::t("Username is incorrect.", 'account'));
                    break;
                case UserIdentity::ERROR_STATUS_NOTACTIV:
                    $this->addError("status", Common::t("You account is not activated.", 'account'));
                    break;
                case UserIdentity::ERROR_STATUS_BAN:
                    $this->addError("status", Common::t("You account is blocked.", 'account'));
                    break;
                case UserIdentity::ERROR_PASSWORD_INVALID:
                    $this->addError("password", Common::t("Password is incorrect.", 'account'));
                    break;
                case UserIdentity::ERROR_NONE_USER:
                    $this->addError("username", Common::t("Email is incorrect.", 'account'));
                    break;
            }
        }
    }

}
