<?php

class TermsController extends MyController {

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

    public function actionLoadTerms() {
        if (Yii::app()->request->isAjaxRequest) {
            $step = 5;
            $taxonomy = 'category';
            $show_in = isset($_POST['show_in']) ? $_POST['show_in'] : 'winner';
            $select = isset($_POST['select']) ? json_decode($_POST['select']) : NULL;
            $update = isset($_POST['update']) ? $_POST['update'] : 0;

            $this->renderPartial('_list_selection_category', array(
                'taxonomy' => $taxonomy,
                'show_in' => $show_in,
                'step' => $step,
                'select' => $select,
                'update' => $update,
            ));
        } else {
            echo 0;
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {

        $form_title = 'Update Category ' . $id;
        $update = 1;
        $select = array();
        $select_update = array();
        $model = $this->loadModel($id, true);
        $model_term_taxonomy = TermTaxonomy::model()->findByPk((int) $id);

        if (isset($_POST['Terms'])) {

            $model->attributes = $_POST['Terms'];
            $model_term_taxonomy->attributes = $_POST['TermTaxonomy'];

            foreach (Yii::app()->params['translatedLanguages'] as $l => $lang) {
                if ($l === Yii::app()->params['defaultLanguage']) {
                    
                } else {
                    $l_slug = 'slug_' . $l;
                    $l_name = 'name_' . $l;
                    $name = $_POST['Terms'][$l_name];
                    $slug = $_POST['Terms'][$l_slug];
                    if ($_POST['Terms'][$l_slug] == '') {
                        // auto make slug from name
                        $model->{$l_slug} = Slug::create($name)  . '-' . $l;
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
                    if ($_POST['TermTaxonomy']['term_id'] != -1) {
                        $model_term_taxonomy->parent = $_POST['TermTaxonomy']['term_id'];
                    }
                    //
                    $model_term_taxonomy->term_id = (int) $id;
                    $model_term_taxonomy->save();
                    //
                    $transaction->commit();
                    //
                    $this->redirect(Yii::app()->createUrl('backend/terms'));
                    Yii::app()->end;
                } catch (Exception $e) {
                    $transaction->rollBack();
                    throw new CHttpException(null, "catch transaction, " . $e->getMessage());
                }
            }
        }
        $select[] = (int) $id;
        $select_update[] = isset($_POST['TermTaxonomy']['term_id']) ? $_POST['TermTaxonomy']['term_id'] : $select_update;
        $category_list = $model_term_taxonomy->findAllByAttributes(array('taxonomy' => 'category'));

        $select_show_in = (isset($_POST['TermTaxonomy']['show_in'])) ? $_POST['TermTaxonomy']['show_in'] : $model->termtaxonomy[0]['show_in'];

        $this->render('index', array(
            'form_title' => $form_title,
            'update' => $update,
            'model' => $model,
            'model_term_taxonomy' => $model_term_taxonomy,
            'category_list' => $category_list,
            'select' => $select,
            'select_update' => $select_update,
            'select_show_in' => $select_show_in,
        ));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {

        $form_title = 'Add New Category';
        $update = 0;
        $select = array();
        $select_update = array();
        $model = new Terms;
        $model_term_taxonomy = new TermTaxonomy;

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
                        $model->{$l_slug} = Slug::create($name)  . '-' . $l;
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
                    $model_term_taxonomy->term_id = $model->id;
                    $model_term_taxonomy->taxonomy = 'category';
                    $model_term_taxonomy->parent = $_POST['TermTaxonomy']['term_id'];
                    //
                    $model_term_taxonomy->save();
                    //
                    $transaction->commit();
                    //
                    $this->redirect(Yii::app()->createUrl('backend/terms'));
                    Yii::app()->end;
                } catch (Exception $ex) {
                    $transaction->rollBack();
                    throw new CHttpException(null, "catch transaction, " . $e->getMessage());
                }
            }
        }

        $select[] = (isset($_POST['TermTaxonomy']['term_id'])) ? $_POST['TermTaxonomy']['term_id'] : NULL;
        $category_list = $model_term_taxonomy->findAllByAttributes(array('taxonomy' => 'category'));

        $select_show_in = (isset($_POST['TermTaxonomy']['show_in'])) ? $_POST['TermTaxonomy']['show_in'] : 'major';

        $this->render('index', array(
            'form_title' => $form_title,
            'update' => $update,
            'model' => $model,
            'model_term_taxonomy' => $model_term_taxonomy,
            'category_list' => $category_list,
            'select' => $select,
            'select_update' => $select_update,
            'select_show_in' => $select_show_in,
        ));
    }

    // Delete category
    public function actionDelete($id) {

        if (TermTaxonomy::model()->has_child('category', (int) $id)) {
            // has many childs, can't delete
            $this->redirect(Yii::app()->createUrl('backend/terms'));
            Yii::app()->end();
        }

        TermTaxonomy::model()->delete_tree($id);
        //
        $this->redirect(Yii::app()->createUrl('backend/terms'));
        Yii::app()->end();
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
