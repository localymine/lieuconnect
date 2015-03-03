<?php

/**
 * Description of MyController
 *
 * @author khangld
 */
class FrontController extends CController {

    public $layout = '//layouts/column1';
    public $menu = array();
    public $breadcrumbs = array();

    public function init() {

        Yii::app()->theme = 'classic';
        parent::init();
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else {
//                $this->render('error' . $error['code'], array('error' => $error));
                $this->render('error', array('error' => $error));
            }
        }
    }

    public function loadModel($id, $ml = false) {
        if ($ml) {
            $model = Post::model()->localized($this->lang)->findByPk((int) $id);
        } else {
            $model = Post::model()->findByPk((int) $id);
        }
        if ($model === null)
            throw new CHttpException(404, 'The requested post does not exist.');
        return $model;
    }

}
