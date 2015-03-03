<?php

class ProfileController extends MyStudentController {

    public $lang = 'vi';

    public function init() {
        $this->lang = Yii::app()->language;

        parent::init();
    }

    public function actionIndex() {

        $user = AUsers::model()->findByPk(Yii::app()->user->id);
        $profile = $user->profile;

        $model_password = new AUserChangePassword;

        $countries = Countries::model()->findAll();
        $states = States::model()->localized($this->lang)->findAll();
        $cities = Cities::model()->localized($this->lang)->findAll();

        $this->render('index', array(
            'model_password' => $model_password,
            'user' => $user,
            'profile' => $profile,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
        ));
    }

    public function actionUpdate_about() {
        if (Yii::app()->request->isAjaxRequest) {

            $user = AUsers::model()->findByPk(Yii::app()->user->id);
            $profile = $user->profile;

            $data = json_decode($_POST['data']);

            if ($data->email != $user->email) {
                $result = AUsers::model()->existsEmail($data->email)->find();
                if ($result != NULL) {
                    // false -> exist Email
                    $arr_resp = array(
                        'CODE' => 'ERR',
                        'MESS' => Common::t('Email Exist', 'account')
                    );

                    $message = json_encode($arr_resp);
                    echo $message;
                    Yii::app()->end();
                }
            }

            $user->email = $data->email;
            $profile->firstname = $data->firstname;
            $profile->lastname = $data->lastname;
            $profile->phone = $data->phone;
            $profile->birth_date = $data->birth_date;
            $profile->gender = $data->gender;

            if ($user->save()) {
                $profile->save();
                $arr_resp = array(
                    'CODE' => 'OK',
                );

                $message = json_encode($arr_resp);
                echo $message;
                Yii::app()->end();
            } else {

                $arr_resp = array(
                    'CODE' => 'ERR',
                    'MESS' => Common::t('An error occurred', 'post')
                );

                $message = json_encode($arr_resp);
                echo $message;
                Yii::app()->end();
            }
        }
    }

    public function actionUpdate_contact_info() {
        if (Yii::app()->request->isAjaxRequest) {

            $user = AUsers::model()->findByPk(Yii::app()->user->id);
            $profile = $user->profile;

            $data = json_decode($_POST['data']);

            $profile->address = $data->address;
            $profile->country = $data->country;
            $profile->state = $data->state;
            $profile->city = $data->city;
            $profile->zipcode = $data->zipcode;
            $profile->expectation = $data->expectation;
            $profile->grade_year = $data->grade_year;
            $profile->gpa = $data->gpa;

            if ($user->save()) {
                $profile->save();
                $arr_resp = array(
                    'CODE' => 'OK',
                );

                $message = json_encode($arr_resp);
                echo $message;
                Yii::app()->end();
            } else {

                $arr_resp = array(
                    'CODE' => 'ERR',
                    'MESS' => Common::t('An error occurred', 'post')
                );

                $message = json_encode($arr_resp);
                echo $message;
                Yii::app()->end();
            }
        }
    }

    public function actionChange_password() {
        if (Yii::app()->request->isAjaxRequest) {

            $user = AUsers::model()->notsafe()->findByPk(Yii::app()->user->id);

            $data = json_decode($_POST['data']);

            if (AUsers::encrypting($data->oldPassword) != $user->password) {
                $arr_resp = array(
                    'CODE' => 'ERR',
                    'MESS' => Common::t("Old Password is incorrect.", 'account')
                );

                $message = json_encode($arr_resp);
                echo $message;
                Yii::app()->end();
            } else {
                if ($data->password != $data->verifyPassword) {
                    $arr_resp = array(
                        'CODE' => 'ERR',
                        'MESS' => Common::t("New Password and Confirm Password is not the same.", 'account')
                    );

                    $message = json_encode($arr_resp);
                    echo $message;
                    Yii::app()->end();
                } else {

                    $user->password = AUsers::encrypting($data->password);
                    $user->save();
                    //
                    $arr_resp = array(
                        'CODE' => 'OK',
                        'MESS' => Common::t("New Password has applied.", 'account')
                    );

                    $message = json_encode($arr_resp);
                    echo $message;
                    Yii::app()->end();
                }
            }
        }
    }

    public function actionSetting() {
        if (Yii::app()->request->isAjaxRequest) {

            $type = trim(strtolower($_POST['type']));

            switch ($type) {
                case 'resume':
                    AProfiles::model()->public_resume();
                    break;
                case 'profile':
                    AProfiles::model()->public_profile();
                    break;
            }
            //
            $arr_resp = array(
                'CODE' => 'OK',
            );

            $message = json_encode($arr_resp);
            echo $message;
        }
    }

}
