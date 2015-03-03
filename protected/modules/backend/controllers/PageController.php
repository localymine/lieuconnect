<?php

class PageController extends MyController {

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
                'actions' => array('index', 'view', 'delete', 'create', 'update', 'status'),
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
        $model = Page::model()->change_status($id);
        echo $model->disp_flag;
    }

    public function actionDelete() {

        $id = $_POST['id'];
        $post_type = 'winner';

        $module_user = Yii::app()->getModule('user');
        $admin = $module_user->isAdmin();

        if ($admin == 1) {
            echo Page::model()->delete_post($id, $post_type);
        } elseif ($admin == 2) {
            echo Page::model()->delete_post($id, $post_type, Yii::app()->user->user_id);
        }
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
        $condition = " post_type NOT LIKE '$post_type' ";
        if (trim($keyword) != '') {
            $t_keys = Common::make_keywords($keyword);
            $condition .= " (post_title LIKE '%$keyword%' ";
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

        $form_title = 'Update Page';
        $update = 1;

        $model = $this->loadModel($id, true);

        if (isset($_POST['Page'])) {
            $model->attributes = $_POST['Page'];

            if ($model->validate()) {
                if ($model->save()) {
                    // redirect
                    $this->redirect(array('update', 'id' => $id));
                }
            }
        }

        $this->render('_form', array(
            'form_title' => $form_title,
            'update' => $update,
            'model' => $model,
        ));
    }

    public function actionCreate() {

        $form_title = 'Add New Page';
        $update = 0;

        $model = new Page;

        if (isset($_POST['Page'])) {

            $model->attributes = $_POST['Page'];
            $model->post_author = Yii::app()->user->id;

            if ($model->validate()) {

                if ($model->save()) {
                    // redirect
                    $this->redirect(array('update', 'id' => $model->id));
                }
            }
        }

        $this->render('_form', array(
            'form_title' => $form_title,
            'update' => $update,
            'model' => $model,
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

}
