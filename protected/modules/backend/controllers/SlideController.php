<?php

class SlideController extends MyController {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
//            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'update', 'delete', 'sort'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
//                'actions' => array('create', 'update'),
//                'users' => array('@'),
            ),
//            array('allow', // allow admin user to perform 'admin' and 'delete' actions
//                'actions' => array('admin', 'delete'),
//                'users' => array('admin'),
//            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }
    
    public function actionSort() {
        
        $sort_ids = json_decode($_POST['data']);
        
        $result = Slide::model()->update_sort($sort_ids);
        
        echo $result;
        
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        
        $form_title = 'Update Item';
        $update = 1;
        $model = $this->loadModel($id, true);
        
        // for upload
        $cf = Yii::app()->file;
        $upload_path = Yii::app()->params['set_slide_path'];
        $cf->createDir(0755, $upload_path);
        //
        $upload = Yii::app()->upload;
        $upload->folder = $upload_path;

        if (isset($_POST['Slide'])) {
            
            $model->attributes = $_POST['Slide'];
            
            if ($model->validate()){
                
                // upload image - update
                foreach (Yii::app()->params['translatedLanguages'] as $l => $lang) {
                    if ($l === Yii::app()->params['defaultLanguage']) {
                        if (CUploadedFile::getInstance($model, 'image') != NULL) {
                            $upload->old_file = $model->image;
                            $upload->post = $model->image = CUploadedFile::getInstance($model, 'image');
                            $model->image = $upload->normal();
                        }
                    } else {
                        $l_image = 'image_' . $l;
                        if (CUploadedFile::getInstance($model, $l_image) != NULL) {
                            $upload->old_file = $model->{$l_image};
                            $upload->post = $model->{$l_image} = CUploadedFile::getInstance($model, $l_image);
                            $model->{$l_image} = $upload->normal();
                        }
                    }
                }
                
                if ($model->save()) {
                    $this->redirect(Yii::app()->createUrl('backend/slide'));
                }
            }
        }

        $this->render('index', array(
            'form_title' => $form_title,
            'update' => $update,
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id, true)->delete();

        $this->redirect(Yii::app()->createUrl('backend/slide'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $form_title = 'Add New Item';
        $update = 0;
        $model = new Slide;
        
        // for upload
        $cf = Yii::app()->file;
        $upload_path = Yii::app()->params['set_slide_path'];
        $cf->createDir(0755, $upload_path);
        //
        $upload = Yii::app()->upload;
        $upload->folder = $upload_path;
        
        if (isset($_POST['Slide'])) {
            
            $model->attributes = $_POST['Slide'];
            
            if ($model->validate()){
                
                // upload image -- add new
                foreach (Yii::app()->params['translatedLanguages'] as $l => $lang) {
                    if ($l === Yii::app()->params['defaultLanguage']) {
                        if (CUploadedFile::getInstance($model, 'image') != NULL) {
                            $upload->post = $model->image = CUploadedFile::getInstance($model, 'image');
                            $model->image = $upload->normal();
                        }
                    } else {
                        $l_image = 'image_' . $l;
                        if (CUploadedFile::getInstance($model, $l_image) != NULL) {
                            $upload->post = $model->{$l_image} = CUploadedFile::getInstance($model, $l_image);
                            $model->{$l_image} = $upload->normal();
                        }
                    }
                }
                
                $rs = Slide::model()->get_max();
                $model->sorting = $rs['max'] + 1;
                
                if ($model->save()) {
                    $this->redirect(Yii::app()->createUrl('backend/slide'));
                    Yii::app()->end();
                }
            }
        }

        $this->render('index', array(
            'form_title' => $form_title,
            'update' => $update,
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Slide the loaded model
     * @throws CHttpException
     */
    public function loadModel($id, $ml = false) {
        if ($ml) {
            $model = Slide::model()->multilang()->findByPk((int) $id);
        } else {
            $model = Slide::model()->findByPk((int) $id);
        }
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
