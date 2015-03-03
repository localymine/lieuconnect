<?php

class FeelingController extends MyController {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $form_title = 'Add New';
        $update = 0;

        $model = new Feeling;

        if (isset($_POST['Feeling'])) {
            $model->attributes = $_POST['Feeling'];
            if ($model->save())
                $this->redirect(Yii::app()->createUrl('backend/feeling'));
        }

        $this->render('index', array(
            'model' => $model,
            'form_title' => $form_title,
            'update' => $update,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {

        $form_title = 'Update ' . $id;
        $update = 1;

        $model = $this->loadModel($id, true);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Feeling'])) {
            $model->attributes = $_POST['Feeling'];
            if ($model->save())
                $this->redirect(Yii::app()->createUrl('backend/feeling'));
        }

        $this->render('index', array(
            'model' => $model,
            'form_title' => $form_title,
            'update' => $update,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id, true)->delete();
        $this->redirect(Yii::app()->createUrl('backend/feeling'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {

        $form_title = 'Add New';
        $update = 0;

        $model = new Feeling;

        if (isset($_POST['Feeling'])) {
            $model->attributes = $_POST['Feeling'];
            if ($model->save())
                $this->redirect(Yii::app()->createUrl('backend/feeling'));
        }

        $this->render('index', array(
            'model' => $model,
            'form_title' => $form_title,
            'update' => $update,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Feeling the loaded model
     * @throws CHttpException
     */
    public function loadModel($id, $ml = false) {
        if ($ml) {
            $model = Feeling::model()->multilang()->findByPk((int) $id);
        } else {
            $model = Feeling::model()->findByPk($id);
        }
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Feeling $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'feeling-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
