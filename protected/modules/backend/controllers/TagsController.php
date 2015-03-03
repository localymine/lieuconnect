<?php

class TagsController extends MyController {

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
                'actions' => array('index', 'view', 'delete'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
//                'actions' => array('create', 'update'),
//                'users' => array('@'),
            ),
//            array('allow', // allow admin user to perform 'admin' and 'delete' actions
//                'actions' => array('admin', 'delete'),
//                'users' => array('*'),
//            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {

        $form_title = 'Update Tag ' . $id;
        $update = 1;
        $model = $this->loadModel($id, true);
        $model_term_taxonomy = TermTaxonomy::model()->findByPk((int) $id);

        if (!isset($_POST['TermTaxonomy'])) {
        }

        if (isset($_POST['Terms'])) {

            $model->attributes = $_POST['Terms'];
            $model_term_taxonomy->attributes = $_POST['TermTaxonomy'];

            foreach (Yii::app()->params['translatedLanguages'] as $l => $lang) {
                if ($l === Yii::app()->params['defaultLanguage']) {
                    
                } else {
                    $slug = new Slug;
                    $l_slug = 'slug_' . $l;
                    $l_name = 'name_' . $l;
                    $name = $_POST['Terms'][$l_name];
                    $slug = $_POST['Terms'][$l_slug];
                    if ($_POST['Terms'][$l_slug] == '') {
                        // auto make slug from name
                        $model->{$l_slug} = Slug::create($name);
                    } else {
                        // make slug from slug input
                        $model->{$l_slug} = Slug::create($slug);
                    }
                }
            }

            if ($model->validate()) {

                $transaction = Yii::app()->db->beginTransaction();
                try {
                    $model->save();
                    //
                    $model_term_taxonomy->save();
                    //
                    $transaction->commit();
                    //
                    $this->redirect(Yii::app()->createUrl('backend/tags'));
                    Yii::app()->end;
                } catch (Exception $e) {
                    $transaction->rollBack();
                    throw new CHttpException(null, "catch transaction, " . $e->getMessage());
                }
            }
        }

        $this->render('index', array(
            'form_title' => $form_title,
            'update' => $update,
            'model' => $model,
            'model_term_taxonomy' => $model_term_taxonomy,
        ));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {

        $form_title = 'Add New Tag';
        $update = 0;
        $model = new Terms;
        $model_term_taxonomy = new TermTaxonomy;

        if (isset($_POST['Terms'])) {

            $model->attributes = $_POST['Terms'];

            foreach (Yii::app()->params['translatedLanguages'] as $l => $lang) {
                if ($l === Yii::app()->params['defaultLanguage']) {
                
                } else {
                    $l_slug = 'slug_' . $l;
                    $l_name = 'name_' . $l;
                    $name = $_POST['Terms'][$l_name];
                    $slug = $_POST['Terms'][$l_slug];
                    if ($_POST['Terms'][$l_slug] == '') {
                        // auto make slug from name
                        $model->{$l_slug} = Slug::create($name);
                    } else {
                        // make slug from slug input
                        $model->{$l_slug} = Slug::create($slug);
                    }
                }
            }

            if ($model->validate()) {

                $transaction = Yii::app()->db->beginTransaction();
                try {
                    $model->save();
                    $model_term_taxonomy->attributes = $_POST['TermTaxonomy'];
                    $model_term_taxonomy->term_id = $model->id;
                    $model_term_taxonomy->taxonomy = 'tag';
                    //
                    $model_term_taxonomy->save();
                    //
                    $transaction->commit();
                    //
                    $this->redirect(Yii::app()->createUrl('backend/tags'));
                    Yii::app()->end;
                } catch (Exception $e) {
                    $transaction->rollBack();
                    throw new CHttpException(null, "catch transaction, " . $e->getMessage());
                }
            }
        }

        $this->render('index', array(
            'form_title' => $form_title,
            'update' => $update,
            'model' => $model,
            'model_term_taxonomy' => $model_term_taxonomy,
        ));
    }


    // Delete tag
    public function actionDelete($id) {

        $transaction = Yii::app()->db->beginTransaction();
        $model_relationships = new TermRelationships;
        $model_term_taxonomy = new TermTaxonomy;
        
        try {
            $t = $model_relationships->findByAttributes(array('term_taxonomy_id' => (int) $id));
            if ($t != NULL) {
                $criteria = new CDbCriteria(array(
                    'condition' => "term_taxonomy_id = :id",
                    'params' => array(':id' => (int) $id),
                ));
                $model_relationships->deleteAll($criteria);
            }
            //
            $criteria = new CDbCriteria(array(
                'condition' => "taxonomy = 'tag' AND term_taxonomy_id = :id",
                'params' => array(':id' => (int) $id),
            ));
            $model_term_taxonomy->deleteAll($criteria);
            //
            $this->loadModel((int) $id, true)->delete();
            //
            $transaction->commit();
            //
            $this->redirect(Yii::app()->createUrl('backend/tags'));
            Yii::app()->end;
        } catch (Exception $e) {
            $transaction->rollBack();
            throw new CHttpException(null, "catch transaction, " . $e->getMessage());
        }

    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Terms the loaded model
     * @throws CHttpException
     */
    public function loadModel($id, $ml = false) {
        if ($ml) {
            $model = Terms::model()->multilang()->findByPk((int) $id);
        } else {
            $model = Terms::model()->findByPk((int) $id);
        }
        if ($model === null)
            throw new CHttpException(404, 'The requested post does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Terms $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'terms-form') {
            echo CActiveForm::validate($model
            );
            Yii::app()->end();
        }
    }

}
