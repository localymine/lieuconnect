<?php

class AccountController extends FrontController {

    public $lang = 'vi';

    public function init() {
        $this->lang = Yii::app()->language;
    }

    public function actions() {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
                'foreColor' => 0x0000B2,
                'transparent' => true,
                'minLength' => 6, // min length of generated word  
                'maxLength' => 7, // max length of generated word  
                'width' => 100, // width of the CAPTCHA image  
                'height' => 38, // height of the CAPTCHA image  
                'offset' => -2,
                'padding' => 4,
            ),
        );
    }

    public function actionProfile() {
        
    }

    public function actionLogout() {
        Yii::app()->user->logout();
        clearstatcache();
        session_start();
        session_destroy();
        $this->redirect(Yii::app()->createUrl('story'));
    }

    public function actionSignup() {

        $model = new SignupForm;
        $model_profile = new AProfiles;
        $countries = Countries::model()->findAll();
        $states = array();
        $cities = array();
        $select_state = '';
        $select_city = '';

        $select_country = isset($_POST['AProfiles']['country']) ? $_POST['AProfiles']['country'] : '';
        if ($select_country != '') {
            $states = States::model()->localized($this->lang)->findAllByAttributes(array('countryID' => $select_country));
            $select_state = isset($_POST['AProfiles']['state']) ? $_POST['AProfiles']['state'] : '';
        }
        if ($select_state != '' && isset($_POST['AProfiles']['city'])) {
            $cities = Cities::model()->localized($this->lang)->findAllByAttributes(array('stateID' => $select_state));
            $select_city = $_POST['AProfiles']['city'];
        }

        if (Yii::app()->user->id) {
            $this->redirect(Yii::app()->createUrl('profile'));
        } else {
            if (isset($_POST['SignupForm'])) {
                $model->attributes = $_POST['SignupForm'];
                $model_profile->attributes = ((isset($_POST['AProfiles']) ? $_POST['AProfiles'] : array()));
                if ($model->validate() && $model_profile->validate()) {
                    $model->activkey = AUsers::encrypting(microtime() . $model->password);
                    $model->password = AUsers::encrypting($model->password);
                    $model->verifyPassword = AUsers::encrypting($model->verifyPassword);
                    $model->superuser = 0;
                    $model->status = ((AUsers::model()->activeAfterRegister) ? AUsers::STATUS_ACTIVE : AUsers::STATUS_NOACTIVE);
                    if ($model->save()) {
                        $model_profile->user_id = $model->id;
                        $model_profile->save();
                        //
                        $hodler['email'] = $model->email;
                        $hodler['activkey'] = $model->activkey;
                        AUsers::sendActiveMail($model->email, $hodler);
                        //
                        Yii::app()->user->setState('regist_id', $model->id);
                        $seed = md5($model->id);
                        $this->redirect(Yii::app()->createUrl('account/thankyou', array('authDate' => date('Ymd'), 'key' => $seed)));
                        Yii::app()->end();
                    }
                }
            }
        }

        $this->render('_form', array(
            'model' => $model,
            'model_profile' => $model_profile,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
            'select_country' => $select_country,
            'select_state' => $select_state,
            'select_city' => $select_city,
            'verifyCode' => '',
        ));
    }
    
    public function actionThankyou(){
        $regist_id = Yii::app()->user->getState('regist_id');
        if (isset($_REQUEST['key']) && $_REQUEST['key'] == md5($regist_id)) {
            Yii::app()->user->setState('regist_id', NULL);
            $this->render('thankyou_regist');
        } else {
            $this->redirect(Yii::app()->createUrl('account/signup'));
        }
    }

    /* load state */

    public function actionLoad_state() {

        if (Yii::app()->request->isPostRequest) {
            $processOutput = false;
            if (Yii::app()->request->isAjaxRequest) {
                $processOutput = true;
            }
            //
            $id = $_POST['id']; // string
            $model = States::model()->localized($this->lang)->findAllByAttributes(array('countryID' => $id));

            $this->renderPartial('_select_states', array(
                'model' => $model
                    ), false, $processOutput);
        } else {
            $this->redirect(Yii::app()->createUrl('home'));
        }
    }

    /* load city */

    public function actionLoad_city() {

        if (Yii::app()->request->isPostRequest) {
            $processOutput = false;
            if (Yii::app()->request->isAjaxRequest) {
                $processOutput = true;
            }
            //
            $id = $_POST['id']; // string
            $model = Cities::model()->localized($this->lang)->findAllByAttributes(array('stateID' => $id));

            $this->renderPartial('_select_cities', array(
                'model' => $model
                    ), false, $processOutput);
        } else {
            $this->redirect(Yii::app()->createUrl('home'));
        }
    }

    // check exist Email
    public function actionExistsEmail() {
        $result = NULL;
        $email = isset($_POST['SignupForm']['email']) ? $_POST['SignupForm']['email'] : '';

        if ($email != '') {

            $result = AUsers::model()->existsEmail($email)->find();
            if ($result != NULL) {
                echo 'false'; // false -> exist Email
            } else {

                echo 'true'; // true -> free to use this email
            }
        } else {
            echo 'false';
        }
    }

    // check exist Username
    public function actionExistsUsername() {
        $result = NULL;
        $username = isset($_POST['SignupForm']['username']) ? $_POST['SignupForm']['username'] : '';

        if ($username != '') {

            $result = AUsers::model()->existsUsername($username)->find();
            if ($result != NULL) {
                echo 'false'; // false -> exist Email
            } else {

                echo 'true'; // true -> free to use this email
            }
        } else {
            echo 'false';
        }
    }

    /* ------------------------------------------------------------------------ */

    public function actionActivation() {
        if (isset($_GET['email']) && isset($_GET['activkey'])) {
            $email = $_GET['email'];
            $activkey = $_GET['activkey'];
            $find = AUsers::model()->notsafe()->findByAttributes(array('email' => $email));
            if (isset($find) && $find->status) {
                $this->render('message', array(
                    'content' => Common::t('You account is active.', 'account')
                ));
            } elseif (isset($find->activkey) && ($find->activkey == $activkey)) {
                $find->activkey = AUsers::encrypting(microtime());
                $find->status = 1;
                $find->save();
                $this->render('message', array(
                    'content' => Common::t('Your account is activated.', 'account')
                ));
            } else {
                $this->render('message', array(
                    'content' => Common::t('Incorrect activation URL.', 'account')
                ));
            }
        } else {
            $this->render('message', array(
                'content' => Common::t('Incorrect activation URL.', 'account')
            ));
        }
    }

    /* ------------------------------------------------------------------------ */

    public function actionDo_login() {
        if (Yii::app()->user->isGuest) {
            $model = new AUserLogin;

            // collect user input data
            if (isset($_POST['username']) && isset($_POST['password'])) {
                $model->username = $_POST['username'];
                $model->password = $_POST['password'];
                // validate user input and redirect to previous page if valid
                if ($model->validate()) {
                    $this->lastViset();

                    $arr_resp = array(
                        'CODE' => 'OK',
                    );
                    $message = json_encode($arr_resp);
                    echo $message;
                    Yii::app()->end();
                } else {
                    $arr_resp = array(
                        'CODE' => 'ERR',
                        'MESS' => Common::t('Please check your account login<br>Email Or Password is wrong', 'account')
                    );
                    $message = json_encode($arr_resp);
                    echo $message;
                    Yii::app()->end();
                }
            }
            // display the login form
            $arr_resp = array(
                'CODE' => 'ERR',
                'MESS' => Common::t('An error occurred', 'account')
            );
            $message = json_encode($arr_resp);
            echo $message;
            Yii::app()->end();
        } else {

            $this->redirect(Yii::app()->createUrl('home'));
        }
    }

    public function actionLogin() {

        $this->redirect(Yii::app()->createUrl('home'));
    }

    private function lastViset() {
        $lastVisit = User::model()->notsafe()->findByPk(Yii::app()->user->id);
        $lastVisit->lastvisit = time();
        $lastVisit->save();
    }

    /* ------------------------------------------------------------------------ */

    public function actionRecover() {
        $form = new AUserRecoveryForm;
        if (Yii::app()->user->id) {
            $this->redirect(Yii::app()->createUrl('profile'));
        } else {
            $email = ((isset($_GET['email'])) ? $_GET['email'] : '');
            $activkey = ((isset($_GET['activkey'])) ? $_GET['activkey'] : '');
            if ($email && $activkey) {
                $form2 = new AUserChangePassword;
                $find = AUsers::model()->notsafe()->findByAttributes(array('email' => $email));
                if (isset($find) && $find->activkey == $activkey) {
                    if (isset($_POST['AUserChangePassword'])) {
                        $form2->attributes = $_POST['AUserChangePassword'];
                        if ($form2->validate()) {
                            $find->password = AUsers::encrypting($form2->password);
                            $find->activkey = AUsers::encrypting(microtime() . $form2->password);
                            if ($find->status == 0) {
                                $find->status = 1;
                            }
                            $find->save();
                            Yii::app()->user->setFlash('recoveryMessage', Common::t("New password is saved.", 'account'));
                            $this->redirect(Yii::app()->createUrl('account/recover'));
                        }
                    }
                    $this->render('changepassword', array('form' => $form2));
                } else {
                    Yii::app()->user->setFlash('recoveryMessage', Common::t("Incorrect recovery link.", 'account'));
                    $this->redirect(Yii::app()->createUrl('account/recover'));
                }
            } else {
                if (isset($_POST['AUserRecoveryForm'])) {
                    $form->attributes = $_POST['AUserRecoveryForm'];
                    if ($form->validate()) {
                        $user = AUsers::model()->notsafe()->findbyPk($form->user_id);

                        $holder['email'] = $user->email;
                        $holder['activkey'] = $user->activkey;
                        AUsers::sendRecoverMail($user->email, $holder);

                        Yii::app()->user->setFlash('recoveryMessage', Common::t("Please check your email. An instructions was sent to your email address.", 'account'));
                        $this->refresh();
                    }
                }
                $this->render('recovery', array('form' => $form));
            }
        }
    }

    public function actionUpload() {

        $file = $_FILES['AProfiles'];

        // check for error, if there are any errors during upload
        if ($file['error']['image'] > 0) {
            $arr_resp = array(
                array(
                    'CODE' => 'ERR',
                    'MESS' => Common::t('An error ocurred when uploading.', 'post')
                ),
            );
            $message = json_encode($arr_resp);
            echo "<script>parent.response_message('$message')</script>";
            exit;
        }

        if (!getimagesize($file['tmp_name']['image'])) {
            $arr_resp = array(
                array(
                    'CODE' => 'ERR',
                    'MESS' => Common::t('Please ensure you are uploading an image.', 'post')
                ),
            );
            $message = json_encode($arr_resp);
            echo "<script>parent.response_message('$message')</script>";
            exit;
        }

        // check file type
        switch (strtolower($file['type']['image'])) {
            case 'image/png':
            case 'image/gif':
            case 'image/jpeg':
            case 'image/pjpeg':
                break;
            default :
                $arr_resp = array(
                    array(
                        'CODE' => 'ERR',
                        'MESS' => Common::t('Unsupported filetype uploaded.', 'post')
                    ),
                );
                $message = json_encode($arr_resp);
                echo "<script>parent.response_message('$message')</script>";
                exit;
                break;
        }

        // Is file size is less than allowed size.
        if ($file['size']['image'] > 5242880) {
            $arr_resp = array(
                array(
                    'CODE' => 'ERR',
                    'MESS' => Common::t('File size is too big!', 'post')
                ),
            );
            $message = json_encode($arr_resp);
            echo "<script>parent.response_message('$message')</script>";
            exit;
        }

        // create instance
        $profile = AProfiles::model()->findByPk(Yii::app()->user->id);

        // for upload
        $cf = Yii::app()->file;
        $upload_path = Yii::app()->params['set_avatars_path'];
        $cf->createDir(0755, $upload_path);
        //
        $upload = Yii::app()->upload;
        $upload->folder = $upload_path;
        $upload->ThumbSquareSize = 158;
        
        if (CUploadedFile::getInstance($profile, 'image') != NULL) {
            $upload->old_file = $profile->image;
            $upload->post = $profile->image = CUploadedFile::getInstance($profile, 'image');
            // upload
            $profile->image = $upload->process();
            // save database
            if ($profile->save()) {
                $arr_resp = array(
                    array(
                        'CODE' => 'OK',
                        'MESS' => Yii::app()->baseUrl . '/avatars/' . 'thumb_' . $profile->image
                    ),
                );
                $message = json_encode($arr_resp);
                echo "<script>parent.response_message('$message')</script>";
            } else {
                $arr_resp = array(
                    array(
                        'CODE' => 'ERR',
                        'MESS' => Common::t('An error occurred', 'post')
                    ),
                );
                $message = json_encode($arr_resp);
                echo "<script>parent.response_message('$message')</script>";
            }
        } else {
            $arr_resp = array(
                array(
                    'CODE' => 'ERR',
                    'MESS' => Common::t('An error occurred', 'post'),
                ),
            );
            $message = json_encode($arr_resp);
            echo "<script>parent.response_message('$message')</script>";
        }
    }

}
