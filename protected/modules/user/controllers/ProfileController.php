<?php

class ProfileController extends Controller {

    public $defaultAction = 'profile';
    public $layout = '//layouts/column1';

    /**
     * @var CActiveRecord the currently loaded data model instance.
     */
    private $_model;

    /**
     * Shows a particular model.
     */
    public function actionProfile() {
        $model = $this->loadUser();

        $posts = '';
        $limit = 3;
        $page = 1;

        if (Yii::app()->request->isAjaxRequest) {
            $page = Yii::app()->user->getState('feed_page');
            $page += 1;
            $posts = Post::model()->get_new_feed($limit, $page);
            Yii::app()->user->setState('feed_page', $page);
            $this->renderPartial('_new_feed', array('posts' => $posts), false);
        } else {
            Yii::app()->user->setState('feed_page', 1);
            //
            $posts = Post::model()->get_new_feed($limit, $page);
            $this->render('profile', array(
                'model' => $model,
                'profile' => $model->profile,
                'posts' => $posts,
            ));
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     */
    public function actionEdit() {
        $model = $this->loadUser();
        $profile = $model->profile;

        // for upload
        $cf = Yii::app()->file;
        $upload_path = Yii::app()->params['set_avatars_path'];
        $cf->createDir(0755, $upload_path);
        //
        $upload = Yii::app()->upload;
        $upload->folder = $upload_path;
        
        if (isset($_POST['User'])) {

            $model->attributes = $_POST['User'];
            $profile->attributes = $_POST['Profile'];

            if (CUploadedFile::getInstance($profile, 'image') != NULL) {
                $upload->old_file = $profile->image;
                $upload->post = $profile->image = CUploadedFile::getInstance($profile, 'image');
            }

            if ($model->validate() && $profile->validate()) {

                $profile->image = $upload->process();

                $model->save();
                $profile->save();
                Yii::app()->user->updateSession();

                // 
                $arr_resp = array(
                    array('mess' => 'OK'),
                );
                $error_mess = json_encode($arr_resp);
                echo "<script>parent.error_profile_edit('$error_mess')</script>";
                exit;
            } else
                $profile->validate();

            $arr_error = array_merge($model->getErrors(), $profile->getErrors());
            $arr_resp = array(
                array('mess' => 'NG'),
                array('resp' => $arr_error),
            );
            $error_mess = json_encode($arr_resp);
            echo "<script>parent.error_profile_edit('$error_mess')</script>";
        }
    }

    /**
     * Change password
     */
    public function actionChangepassword() {
        $model = new UserChangePassword;
        if (Yii::app()->user->id) {

            if (isset($_POST['UserChangePassword'])) {
                $model->attributes = $_POST['UserChangePassword'];
                if ($model->validate()) {
                    $new_password = User::model()->notsafe()->findbyPk(Yii::app()->user->id);
                    $new_password->password = UserModule::encrypting($model->password);
                    $new_password->activkey = UserModule::encrypting(microtime() . $model->password);
                    $new_password->save();
//                    Yii::app()->user->setFlash('profileMessage', UserModule::t("New password is saved."));
//                    $this->redirect(array('/user/profile'));

                    $arr_resp = array(
                        array('mess' => 'OK'),
                    );
                    $error_mess = json_encode($arr_resp);
                    echo "<script>parent.error_change_password('$error_mess')</script>";
                    exit;
                }

                $arr_error = array_merge($model->getErrors());
                $arr_resp = array(
                    array('mess' => 'NG'),
                    array('resp' => $arr_error),
                );
                $error_mess = json_encode($arr_resp);
                echo "<script>parent.error_change_password('$error_mess')</script>";
            }
            // $this->render('changepassword', array('model' => $model));
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
     */
    public function loadUser() {
        if ($this->_model === null) {
            if (Yii::app()->user->id) {
                $this->_model = Yii::app()->controller->module->user();
                //
                if ($this->_model->superuser == 0) {
                    $this->redirect(Yii::app()->controller->module->logoutUrl);
                }
            }
            if ($this->_model === null)
                $this->redirect(Yii::app()->controller->module->loginUrl);
        }
        return $this->_model;
    }

}
