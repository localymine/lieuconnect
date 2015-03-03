<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LoginForm
 *
 * @author khangld
 */
class LoginForm extends CWidget {

    public $lang = 'vi';
    public $type = 'top';
    public $post_id = 0;
    public $title = 'Apply';

    public function init() {
        $this->lang = Yii::app()->language;
    }

    public function run() {
        switch ($this->type) {
            case 'top':
                $this->render('login-form');
                break;
            case 'apply-button':
                $this->render('modal-login-apply-button', array(
                    'title' => $this->title,
                ));
                break;
            case 'modal-login':
                if (Yii::app()->user->isGuest) {
                    $this->render('modal-login-form');
                }
                break;
            case 'modal-recruit':
                if (!Yii::app()->user->isGuest) {
                    $model = new Recruit;
                    $model_user = AUsers::model()->findByPk(Yii::app()->user->id);
                    //
                    $select_country = $model_user->profile->country;
                    $select_state = $model_user->profile->state;
                    $select_city = $model_user->profile->city;
                    //
                    $countries = Countries::model()->findAll();
                    $states = array();
                    $cities = array();
                    if ($select_country != '') {
                        $states = States::model()->localized($this->lang)->findAllByAttributes(array('countryID' => $select_country));
                    }
                    if ($select_state != ''){
                        $cities = Cities::model()->localized($this->lang)->findAllByAttributes(array('stateID' => $select_state));
                    }
                    //
                    $feeling = Feeling::model()->localized($this->lang)->findAll();
                    //
                    $this->render('modal-recruit-form', array(
                        'model' => $model,
                        'model_user' => $model_user,
                        'countries' => $countries,
                        'states' => $states,
                        'cities' => $cities,
                        'select_country' => $select_country,
                        'select_state' => $select_state,
                        'select_city' => $select_city,
                        'feeling' => $feeling,
                        'title' => $this->title,
                    ));
                }
                break;
        }
    }

}
