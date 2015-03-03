<?php

class SiteController extends Controller {

    public $layout = '//layouts/column_error';

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            $this->render('error' . $error['code'], array('error' => $error));
        } else {
            throw new CHttpException(404, 'Page not found.');
        }
    }

}
