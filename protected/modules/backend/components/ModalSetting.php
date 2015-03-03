<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModalSetting
 *
 * @author khangld
 */
class ModalSetting extends CWidget{
    
    public function init() {
        
    }
    
    public function run() {
        
        $user_id = Yii::app()->user->id;
        $model = User::model()->findByPk($user_id);
        $profile = $model->profile;
        $profileFields=$profile->getFields();
        $model_password = new UserChangePassword;
        
        $this->render('modal-setting', array(
            'model' => $model,
            'model_password' => $model_password,
            'profile' => $profile,
            'profileFields' => $profileFields,
        ));
    }
}
