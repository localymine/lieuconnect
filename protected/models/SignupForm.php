<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SignupForm
 *
 * @author khangld
 */
class SignupForm extends AUsers {

    public $verifyPassword;
    public $verifyCode;

    public function rules() {
        $rules = array(
            array('username, password, verifyPassword, email', 'required'),
            array('username', 'length', 'max' => 20, 'min' => 3, 'message' => Common::t("Incorrect username (length between 3 and 20 characters).")),
            array('password', 'length', 'max' => 128, 'min' => 4, 'message' => Common::t("Incorrect password (minimal length 4 symbols).")),
            array('email', 'email'),
            array('username', 'unique', 'message' => Common::t("This user's name already exists.")),
            array('email', 'unique', 'message' => Common::t("This user's email address already exists.")),
            //array('verifyPassword', 'compare', 'compareAttribute'=>'password', 'message' => Common::t("Retype Password is incorrect.")),
            array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u', 'message' => Common::t("Incorrect symbols (A-z0-9).")),
        );

        if (!(isset($_POST['ajax']) && $_POST['ajax'] === 'signup-form')) {
            array_push($rules, array('verifyCode', 'captcha', 'allowEmpty' => !AUsers::doCaptcha('registration')));
        }

        array_push($rules, array('verifyPassword', 'compare', 'compareAttribute' => 'password', 'message' => Common::t("Retype Password is incorrect.")));
        return $rules;
    }

}
