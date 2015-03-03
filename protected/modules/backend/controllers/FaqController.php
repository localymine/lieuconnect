<?php

class FaqController extends MyController {

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
                'actions' => array('index', 'view', 'delete', 'create', 'update'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
//                'actions' => array('create', 'update'),
//                'users' => array('*'),
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

    public function actionStatus() {

        $id = $_POST['id'];
        $model = Page::model()->change_status($id, 'faq');

        echo $model->disp_flag;
    }

    public function actionDelete() {

        $id = $_POST['id'];
        $post_type = 'faq';

        echo Page::model()->delete_post($id, $post_type);
    }

    public function actionIndex() {

        $model = new Page;
        $item_count = 0;
        $page_size = Yii::app()->params['pageSize'];
        $condition = '';
        $pages = NULL;
        $post_type = 'faq';
        $keyword = '';

        if (isset($_REQUEST['keyword'])) {
            $keyword = $_REQUEST['keyword'];
        }

        // administrator, see all stories of another
        $condition = " post_type LIKE '$post_type' ";
        if (trim($keyword) != '') {
            $keyword = mysql_real_escape_string($keyword);
            $t_keys = Common::make_keywords($keyword);
            $condition .= " AND (post_title LIKE '%$keyword%' ";
            foreach ($t_keys as $key) {
                $condition .= " OR post_title LIKE '%$key%' ";
            }
            $condition .= ")";
        }
        $item_count = $model->count($condition);

        // the pagination itself
        $pages = new CPagination($item_count);
        $pages->setPageSize($page_size);
        if (trim($keyword) != '') {
            $pages->params = array('keyword' => $keyword);
        }

        $criteria = new CDbCriteria(array(
            'condition' => $condition,
            'order' => 'create_date DESC',
            'limit' => $pages->limit,
            'offset' => $pages->offset,
        ));

        $posts = $model->multilang()->findAll($criteria);

        $this->render('index', array(
            'posts' => $posts,
            'item_count' => $item_count,
            'page_size' => $page_size,
            'pages' => $pages,
            'keyword' => $keyword,
        ));
    }

    public function actionUpdate($id) {

        $form_title = 'Update FAQ';
        $update = 1;

        $select_categories = array();

        $model = $this->loadModel($id, true);
        $model_term_relationships = new TermFaqRelationships;

        if (!isset($_POST['Page']['category'])) {
            $hold_categories = array();
            $categories = $model_term_relationships->get_relate_terms((int) $id, 'category')->findAll();
            if ($categories != NULL) {
                foreach ($categories as $row) {
                    $select_categories[] = $row->term_taxonomy_id;
                }
            }
        } else {
            $select_categories = $_POST['Page']['category'];
        }

        if (isset($_POST['Page'])) {
            $model->attributes = $_POST['Page'];

            if ($model->validate()) {

                if ($model->save()) {

                    $model_term_relationships->object_id = $model->id;

                    //delete all relationships
                    $model_term_relationships->deleteAllByAttributes(array('object_id' => (int) $id));

                    //relations with category
                    if (isset($_POST['Page']['category'])) {
                        $categories_id = $_POST['Page']['category'];
                        if ($categories_id != NULL) {
                            foreach ($categories_id as $value) {
                                $model_term_relationships->term_taxonomy_id = $value;
                                $model_term_relationships->isNewRecord = true;
                                $model_term_relationships->save();
                            }
                        }
                    }

                    // redirect
                    $this->redirect(array('update', 'id' => $id));
                }
            }
        }


        $this->render('_form', array(
            'form_title' => $form_title,
            'update' => $update,
            'model' => $model,
            'select_categories' => $select_categories,
        ));
    }

    public function actionCreate() {

        $form_title = 'Add New FAQ';
        $update = 0;

        $select_categories = array();

        $model = new Page;
        $model_term_relationships = new TermFaqRelationships;

        if (isset($_POST['Page'])) {

            $model->attributes = $_POST['Page'];
            $model->post_author = Yii::app()->user->id;
            $model->post_type = 'faq';

            $select_categories = isset($_POST['Page']['category']) ? $_POST['Page']['category'] : NULL;

            if ($model->validate()) {


                if ($model->save()) {

                    $model_term_relationships->object_id = $model->id;

                    //relations with category
                    if (isset($_POST['Page']['category'])) {
                        $categories_id = $_POST['Page']['category'];
                        if ($categories_id != NULL) {
                            foreach ($categories_id as $value) {
                                $model_term_relationships->term_taxonomy_id = $value;
                                $model_term_relationships->isNewRecord = true;
                                $model_term_relationships->save();
                            }
                        }
                    }

                    // redirect
                    $this->redirect(array('update', 'id' => $model->id));
                }
            }
        }

        $this->render('_form', array(
            'form_title' => $form_title,
            'update' => $update,
            'model' => $model,
            'select_categories' => $select_categories,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Post the loaded model
     * @throws CHttpException
     */
    public function loadModel($id, $ml = false) {
        if ($ml) {
            $model = Page::model()->multilang()->findByPk((int) $id);
        } else {
            $model = Page::model()->findByPk((int) $id);
        }
        if ($model === null)
            throw new CHttpException(404, 'The requested post does not exist.');
        return $model;
    }

    /**
     * This is the action to handle external exceptions.
     */
//    public function actionError() {
//        if ($error = Yii::app()->errorHandler->error) {
//            if (Yii::app()->request->isAjaxRequest)
//                echo $error['message'];
//            else
//                $this->render('error', $error);
//        }
//    }
}
