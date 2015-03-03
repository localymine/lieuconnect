<?php

class PracticeTestController extends MyController {

     public function accessRules() {
        $module = Yii::app()->getModule('user');
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('changeStatus'),
                'users' => array('@'),
            ),
        );
    }

    public function actionChangeStatus() {
        $model = new PracticeTest;
        
        $model->change_status($_REQUEST['id']);
        
        $this->redirect(Yii::app()->createUrl('backend/practiceTest'));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $form_title = 'Update';
        $update = 1;

        $model = $this->loadModel($id, true);

        if (isset($_POST['PracticeTest'])) {
            $model->attributes = $_POST['PracticeTest'];
            if ($model->validate()) {
                $model->save();
                $this->redirect(Yii::app()->createUrl('backend/practiceTest'));
            }
        }
        
        $post = $model->order_by_sort()->findAll();

        $this->render('index', array(
            'model' => $model,
            'form_title' => $form_title,
            'update' => $update,
            'post' => $post,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id, true)->delete();
        $this->redirect(Yii::app()->createUrl('backend/practiceTest'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $form_title = 'Add New';
        $update = 0;
        $model = new PracticeTest;

        if (isset($_POST['PracticeTest'])) {
            $model->attributes = $_POST['PracticeTest'];
            $model->post_author = Yii::app()->user->id;
            //
            if ($model->validate()) {
                $model->save();
                $this->redirect(Yii::app()->createUrl('backend/practiceTest'));
            }
        }

        $post = $model->order_by_sort()->findAll();

        $this->render('index', array(
            'form_title' => $form_title,
            'update' => $update,
            'model' => $model,
            'post' => $post,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new PracticeTest('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PracticeTest']))
            $model->attributes = $_GET['PracticeTest'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return PracticeTest the loaded model
     * @throws CHttpException
     */
    public function loadModel($id, $ml = false) {
        if ($ml) {
            $model = PracticeTest::model()->multilang()->findByPk((int) $id);
        } else {
            $model = PracticeTest::model()->findByPk((int) $id);
        }
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
