<?php

class LogoutController extends Controller {

    public $defaultAction = 'logout';

    /**
     * Logout the current user and redirect to returnLogoutUrl.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        clearstatcache();
        session_start();
        session_destroy();
        $this->redirect(Yii::app()->controller->module->returnLogoutUrl);
    }

}
