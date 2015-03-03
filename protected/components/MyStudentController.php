<?php

/**
 * Description of MyController
 *
 * @author khangld
 */
class MyStudentController extends CController {

    public $layout = '//layouts/column2';
    public $menu = array();
    public $breadcrumbs = array();

    public function init() {

        if (Yii::app()->request->isAjaxRequest) {
            if (Yii::app()->user->isGuest) {
                $arr_resp = array(
                    'CODE' => 'ERR',
                    'MESS' => Common::t('Please Login', 'post')
                );
                $message = json_encode($arr_resp);
                echo $message;
		Yii::app()->end();
            }
        }

        if (Yii::app()->user->isGuest) {
            Yii::app()->request->redirect(Yii::app()->createUrl('account/signup'));
            Yii::app()->end();
        }

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
