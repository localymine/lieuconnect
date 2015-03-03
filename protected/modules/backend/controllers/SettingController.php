<?php

class SettingController extends MyController {
    
    public function actionIndex() {

        $model = new Setting;
        $entry = Setting::model()->sort()->only_show()->findAll();
        
        if (isset($_POST['Setting'])){
            $post = $_POST['Setting'];
            foreach ($post as $key => $value){
                Setting::model()->updateAll(array('value' => trim($value)), "name = :key", array('key' => $key));
            }
            $this->redirect(Yii::app()->createUrl('backend/setting'));
            Yii::app()->end();
        }
        
        $this->render('index', array(
            'model' => $model,
            'entry' => $entry,
        ));
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Recruit the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Setting::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Recruit $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'recruit-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}