<?php

class PostController extends Controller {

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

    public function actionLoadTags() {

        $model_tags = TermTaxonomy::model()->tags()->findAll();
        $arr_tags = array();
        foreach ($model_tags as $sub) {
            $arr_tags[] = $sub->terms['name'];
        }

        $tags_json = json_encode($arr_tags);

        echo $tags_json;
    }

    private function create_tags($tags_json) {
        $tags_id = array();
        $tags_arr = json_decode($tags_json);
        foreach ($tags_arr as $value) {

            if ($value != NULL) {
                $model = TermsLang::model()->findByAttributes(array('l_name' => $value));
                if ($model == NULL) {
                    $tags_id[] = Terms::create_basic_tag($value);
                }
            }
        }
        return $tags_id;
    }

    private function get_tags($tags_json) {
        $tags_id = array();
        $tags_arr = json_decode($tags_json);
        foreach ($tags_arr as $value) {
            $model = Terms::model()->findByAttributes(array('name' => $value));
            $tags_id[] = $model->id;
        }
        return $tags_id;
    }

    /* Monthly Winner - Begin */

    public function actionStatusWinner() {

        $id = $_POST['id'];
        $model = Post::model()->change_status($id, 'winner');

        echo $model->disp_flag;
    }

    public function actionDeleteWinner() {

        $id = $_POST['id'];
        $post_type = 'winner';

        $module_user = Yii::app()->getModule('user');
        $admin = $module_user->isAdmin();

        if ($admin == 1) {
            echo Post::model()->delete_post($id, $post_type);
        } elseif ($admin == 2) {
            echo Post::model()->delete_post($id, $post_type, Yii::app()->user->user_id);
        }
    }

    public function actionWinner() {

        $model = new Post;
        $item_count = 0;
        $page_size = Yii::app()->params['pageSize'];
        $condition = '';
        $pages = NULL;
        $post_type = 'winner';
        $keyword = '';

        if (isset($_REQUEST['keyword'])) {
            $keyword = $_REQUEST['keyword'];
        }

        $module_user = Yii::app()->getModule('user');
        $admin = $module_user->isAdmin();

        if ($admin == 1) {
            // administrator, see all stories of another
            $condition = " post_type = '$post_type' ";
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
                'order' => 'post_date DESC',
                'limit' => $pages->limit,
                'offset' => $pages->offset,
            ));

            $posts = $model->multilang()->findAll($criteria);
        } elseif ($admin == 2) {
            // normal admin, only see what they posted
            $condition = " post_type = '$post_type' AND post_author = " . Yii::app()->user->user_id;
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
                'order' => 'post_date DESC',
                'limit' => $pages->limit,
                'offset' => $pages->offset,
            ));

            $posts = $model->multilang()->findAll($criteria);
        }

        $this->render('winner/index', array(
            'posts' => $posts,
            'item_count' => $item_count,
            'page_size' => $page_size,
            'pages' => $pages,
            'keyword' => $keyword,
        ));
    }

    public function actionUpdateWinner($id) {

        $form_title = 'Update Winner';
        $update = 1;

        $tags_arr = array();
        $tags_json = '[""]';
        $select_categories = NULL;

        $model = $this->loadModel($id, true);
        $model_term_relationships = new TermRelationships;

        // for upload
        $cf = Yii::app()->file;
        $upload_path = Yii::app()->params['set_winner_path'];
        $cf->createDir(0755, $upload_path);
        //
        $upload = Yii::app()->upload;
        $upload->folder = $upload_path;
        
        if (!isset($_POST['Post']['category'])) {
            $hold_categories = array();
            $categories = $model_term_relationships->get_relate_terms((int) $id, 'category')->findAll();
            if ($categories != NULL) {
                foreach ($categories as $row) {
                    $select_categories[] = $row->term_taxonomy_id;
                }
            }
        } else {
            $select_categories = $_POST['Post']['category'];
        }

        if (!isset($_POST['Post']['tags'])) {
            $tags = $model_term_relationships->get_relate_terms((int) $id, 'tag')->findAll();
            if ($tags != NULL) {
                foreach ($tags as $row) {
                    $tags_arr[] = $row->termtaxonomy->terms['name'];
                }
                $tags_json = json_encode($tags_arr);
            }
        }

        if (isset($_POST['Post'])) {
            $model->attributes = $_POST['Post'];

            if ($model->validate()) {

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

                /*                 * *** */
                if (isset($_POST['Post']['slug'])) {
                    foreach (Yii::app()->params['translatedLanguages'] as $l => $lang) {
                        if ($l === Yii::app()->params['defaultLanguage']) {
                            $name = $_POST['Post']['post_title'] . '-' . $_POST['Post']['winner_in_month'] . '-' . date('Y');
                            $slug = $_POST['Post']['slug'];
                            if ($_POST['Post']['slug'] == '') {
                                // auto make slug from name
                                $model->slug = Slug::create($name);
                            } else {
                                // make slug from slug input
                                $model->slug = Slug::create($slug);
                            }
                            // convert title to alphabet
                            $model->post_name = Slug::to_alphabet($name);
                        } else {
                            $l_slug = 'slug_' . $l;
                            $l_name = 'post_title_' . $l;
                            $name = $_POST['Post'][$l_name] . '-' . $_POST['Post']['winner_in_month'] . '-' . date('Y');
                            $slug = $_POST['Post'][$l_slug];
                            if ($_POST['Post'][$l_slug] == '') {
                                // auto make slug from name
                                $model->{$l_slug} = Slug::create($name);
                            } else {
                                // make slug from slug input
                                $model->{$l_slug} = Slug::create($slug);
                            }
                            // convert title to alphabet
                            $l_post_name = 'post_name_' . $l;
                            $model->{$l_post_name} = Slug::to_alphabet($name);
                        }
                    }
                }
                /*                 * *** */

                if ($model->save()) {

                    $model_term_relationships->object_id = $model->id;

                    //delete all relationships
                    $model_term_relationships->deleteAllByAttributes(array('object_id' => (int) $id));

                    //relations with category
                    if (isset($_POST['Post']['category'])) {
                        $categories_id = $_POST['Post']['category'];
                        if ($categories_id != NULL) {
                            foreach ($categories_id as $value) {
                                $model_term_relationships->term_taxonomy_id = $value;
                                $model_term_relationships->isNewRecord = true;
                                $model_term_relationships->save();
                            }
                        }
                    }

                    // relations with tags
                    if (isset($_POST['Post']['tags'])) {
                        $this->create_tags($_POST['Post']['tags']);

                        $tags_id = $this->get_tags($_POST['Post']['tags']);
                        foreach ($tags_id as $value) {
                            $model_term_relationships->term_taxonomy_id = $value;
                            $model_term_relationships->isNewRecord = true;
                            $model_term_relationships->save();
                        }
                    }

                    // redirect
                    $this->redirect(array('updateWinner', 'id' => $id));
                }
            }
        }

        $this->render('winner/_form', array(
            'form_title' => $form_title,
            'update' => $update,
            'model' => $model,
            'tags_json' => (isset($_POST['Post']['tags'])) ? $_POST['Post']['tags'] : $tags_json,
            'select_categories' => $select_categories,
        ));
    }

    public function actionAddWinner() {

        $form_title = 'Add New Winner';
        $update = 0;

        $tags_json = '[""]';
        $select_categories = array();

        $model = new Post;
        $model_term_relationships = new TermRelationships;

        // for upload
        $cf = Yii::app()->file;
        $upload_path = Yii::app()->params['set_winner_path'];
        $cf->createDir(0755, $upload_path);
        //
        $upload = Yii::app()->upload;
        $upload->folder = $upload_path;
        
        if (isset($_POST['Post'])) {

            $model->attributes = $_POST['Post'];

            $select_categories = isset($_POST['Post']['category']) ? $_POST['Post']['category'] : NULL;

            if ($model->validate()) {

                // upload image
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

                $model->post_type = 'winner';

                /*                 * * * */
                if (isset($_POST['Post']['slug'])) {
                    foreach (Yii::app()->params['translatedLanguages'] as $l => $lang) {
                        if ($l === Yii::app()->params['defaultLanguage']) {
                            $name = $_POST['Post']['post_title'] . '-' . $_POST['Post']['winner_in_month'] . '-' . date('Y');
                            $slug = $_POST['Post']['slug'];
                            if ($_POST['Post']['slug'] == '') {
                                // auto make slug from name
                                $model->slug = Slug::create($name);
                            } else {
                                // make slug from slug input
                                $model->slug = Slug::create($slug);
                            }
                            // convert title to alphabet
                            $model->post_name = Slug::to_alphabet($name);
                        } else {
                            $l_slug = 'slug_' . $l;
                            $l_name = 'post_title_' . $l;
                            $name = $_POST['Post'][$l_name] . '-' . $_POST['Post']['winner_in_month'] . '-' . date('Y');
                            $slug = $_POST['Post'][$l_slug];
                            if ($_POST['Post'][$l_slug] == '') {
                                // auto make slug from name
                                $model->{$l_slug} = Slug::create($name);
                            } else {
                                // make slug from slug input
                                $model->{$l_slug} = Slug::create($slug);
                            }
                            // convert title to alphabet
                            $l_post_name = 'post_name_' . $l;
                            $model->{$l_post_name} = Slug::to_alphabet($name);
                        }
                    }
                }
                /*                 * * * */

                if ($model->save()) {

                    $model_term_relationships->object_id = $model->id;

                    //relations with category
                    if (isset($_POST['Post']['category'])) {
                        $categories_id = $_POST['Post']['category'];
                        if ($categories_id != NULL) {
                            foreach ($categories_id as $value) {
                                $model_term_relationships->term_taxonomy_id = $value;
                                $model_term_relationships->isNewRecord = true;
                                $model_term_relationships->save();
                            }
                        }
                    }

                    // relations with tags
                    if (isset($_POST['Post']['tags'])) {
                        $this->create_tags($_POST['Post']['tags']);

                        $tags_id = $this->get_tags($_POST['Post']['tags']);
                        foreach ($tags_id as $value) {
                            $model_term_relationships->term_taxonomy_id = $value;
                            $model_term_relationships->isNewRecord = true;
                            $model_term_relationships->save();
                        }
                    }

                    // redirect
                    $this->redirect(array('updateWinner', 'id' => $model->id));
                }
            }
        }

        $this->render('winner/_form', array(
            'form_title' => $form_title,
            'update' => $update,
            'model' => $model,
            'tags_json' => (isset($_POST['Post']['tags'])) ? $_POST['Post']['tags'] : $tags_json,
            'select_categories' => $select_categories,
        ));
    }

    /* Monthly Winner - End */

    /* Story - Begin */

    public function actionStatusStory() {

        $id = $_POST['id'];
        $model = Post::model()->change_status($id, 'story');

        echo $model->disp_flag;
    }

    public function actionDeleteStory() {

        $id = $_POST['id'];
        $post_type = 'story';

        $module_user = Yii::app()->getModule('user');
        $admin = $module_user->isAdmin();

        if ($admin == 1) {
            echo Post::model()->delete_post($id, $post_type);
        } elseif ($admin == 2) {
            echo Post::model()->delete_post($id, $post_type, Yii::app()->user->user_id);
        }
    }

    public function actionStory() {

        $model = new Post;
        $item_count = 0;
        $page_size = Yii::app()->params['pageSize'];
        $condition = '';
        $pages = NULL;
        $post_type = 'story';
        $keyword = '';

        if (isset($_REQUEST['keyword'])) {
            $keyword = $_REQUEST['keyword'];
        }

        $module_user = Yii::app()->getModule('user');
        $admin = $module_user->isAdmin();

        if ($admin == 1) {
            // administrator, see all stories of another
            $condition = " post_type = '$post_type' ";
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
                'order' => 'post_date DESC',
                'limit' => $pages->limit,
                'offset' => $pages->offset,
            ));

            $posts = $model->multilang()->findAll($criteria);
        } elseif ($admin == 2) {
            // normal admin, only see what they posted
            $condition = " post_type = '$post_type' AND post_author = " . Yii::app()->user->user_id;
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
                'order' => 'post_date DESC',
                'limit' => $pages->limit,
                'offset' => $pages->offset,
            ));

            $posts = $model->multilang()->findAll($criteria);
        }

        $this->render('stories/index', array(
            'posts' => $posts,
            'item_count' => $item_count,
            'page_size' => $page_size,
            'pages' => $pages,
            'keyword' => $keyword,
        ));
    }

    public function actionUpdatePickStory() {

        $post_type = 'story';
        $pick_ids = json_decode($_POST['data']);

        $result = PickRelationships::model()->delete_add_picks($post_type, $pick_ids);

        echo $result;
    }

    public function actionPickStory() {

        $model = new Post;
        $item_count = 0;
//        $page_size = Yii::app()->params['pageSize'];
        $page_size = 10;
        $condition = '';
        $pages = NULL;
        $post_type = 'story';
        $pick_list = '';

        $module_user = Yii::app()->getModule('user');
        $admin = $module_user->isAdmin();

        $our_picks = PickRelationships::model()->get_relate_pick($post_type)->findAll();

        if ($admin == 1) {
            // administrator, see all stories of another
            $condition = " post_type = '$post_type' ";
            $item_count = $model->count($condition);

            // the pagination itself
            $pages = new CPagination($item_count);
            $pages->setPageSize($page_size);

            $criteria = new CDbCriteria(array(
                'condition' => $condition,
                'order' => 'post_date DESC',
                'limit' => $pages->limit,
                'offset' => $pages->offset,
            ));

            $posts = $model->multilang()->findAll($criteria);
        } elseif ($admin == 2) {
            // normal admin, only see what they posted
            $condition = " post_type = '$post_type' AND post_author = " . Yii::app()->user->user_id;
            $item_count = $model->count($condition);

            // the pagination itself
            $pages = new CPagination($item_count);
            $pages->setPageSize($page_size);

            $criteria = new CDbCriteria(array(
                'condition' => $condition,
                'order' => 'post_date DESC',
                'limit' => $pages->limit,
                'offset' => $pages->offset,
            ));

            $posts = $model->multilang()->findAll($criteria);
        }

        $list = '';
        if (Yii::app()->request->isAjaxRequest) {

            $list = $this->renderPartial('stories/_list', array(
                'posts' => $posts,
                    ), false);
        } else {
            $this->render('stories/ourpicks', array(
                'posts' => $posts,
                'item_count' => $item_count,
                'page_size' => $page_size,
                'pages' => $pages,
                'list' => $list,
                'our_picks' => $our_picks,
            ));
        }
    }

    public function actionUpdateStory($id) {

        $form_title = 'Update Story';
        $update = 1;

        $tags_arr = array();
        $tags_json = '[""]';
        $select_categories = array();
        $select_post_group = array();

        $model = $this->loadModel($id, true);
        $model_term_relationships = new TermRelationships;

        // for upload
        $cf = Yii::app()->file;
        $upload_path = Yii::app()->params['set_story_path'];
        $cf->createDir(0755, $upload_path);
        //
        $upload = Yii::app()->upload;
        $upload->folder = $upload_path;
        
        if (!isset($_POST['Post']['category'])) {
            $hold_categories = array();
            $categories = $model_term_relationships->get_relate_terms((int) $id, 'category')->findAll();
            if ($categories != NULL) {
                foreach ($categories as $row) {
                    $select_categories[] = $row->term_taxonomy_id;
                }
            }
        } else {
            $select_categories = $_POST['Post']['category'];
        }
        
        if (!isset($_POST['Post']['post_group'])) {
            $select_post_group[] = $model->post_group;
        }

        if (!isset($_POST['Post']['tags'])) {
            $tags = $model_term_relationships->get_relate_terms((int) $id, 'tag')->findAll();
            if ($tags != NULL) {
                foreach ($tags as $row) {
                    $tags_arr[] = $row->termtaxonomy->terms['name'];
                }
                $tags_json = json_encode($tags_arr);
            }
        }

        if (isset($_POST['Post'])) {
            $model->attributes = $_POST['Post'];
            $post_group = isset($_POST['Post']['post_group']) ? $_POST['Post']['post_group'][0] : '';
            $model->post_group = $post_group;

            if ($model->validate()) {

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

                /*                 * *** */
                if (isset($_POST['Post']['slug'])) {
                    foreach (Yii::app()->params['translatedLanguages'] as $l => $lang) {
                        if ($l === Yii::app()->params['defaultLanguage']) {
                            $name = $_POST['Post']['post_title'];
                            $slug = $_POST['Post']['slug'];
                            if ($_POST['Post']['slug'] == '') {
                                // auto make slug from name
                                $model->slug = Slug::create($name);
                            } else {
                                // make slug from slug input
                                $model->slug = Slug::create($slug);
                            }
                            // convert title to alphabet
                            $model->post_name = Slug::to_alphabet($name);
                        } else {
                            $l_slug = 'slug_' . $l;
                            $l_name = 'post_title_' . $l;
                            $name = $_POST['Post'][$l_name];
                            $slug = $_POST['Post'][$l_slug];
                            if ($_POST['Post'][$l_slug] == '') {
                                // auto make slug from name
                                $model->{$l_slug} = Slug::create($name);
                            } else {
                                // make slug from slug input
                                $model->{$l_slug} = Slug::create($slug);
                            }
                            // convert title to alphabet
                            $l_post_name = 'post_name_' . $l;
                            $model->{$l_post_name} = Slug::to_alphabet($name);
                        }
                    }
                }
                /*                 * *** */

                if ($model->save()) {

                    $model_term_relationships->object_id = $model->id;

                    //delete all relationships
                    $model_term_relationships->deleteAllByAttributes(array('object_id' => (int) $id));

                    //relations with category
                    if (isset($_POST['Post']['category'])) {
                        $categories_id = $_POST['Post']['category'];
                        if ($categories_id != NULL) {
                            foreach ($categories_id as $value) {
                                $model_term_relationships->term_taxonomy_id = $value;
                                $model_term_relationships->isNewRecord = true;
                                $model_term_relationships->save();
                            }
                        }
                    }

                    // relations with tags
                    if (isset($_POST['Post']['tags'])) {
                        $this->create_tags($_POST['Post']['tags']);

                        $tags_id = $this->get_tags($_POST['Post']['tags']);
                        $i = 0;
                        foreach ($tags_id as $value) {
                            $model_term_relationships->term_taxonomy_id = $value;
                            $model_term_relationships->term_order = $i++;
                            $model_term_relationships->isNewRecord = true;
                            $model_term_relationships->save();
                        }
                    }

                    // redirect
                    $this->redirect(array('updateStory', 'id' => $id));
                }
            }
        }

        $this->render('stories/_form', array(
            'form_title' => $form_title,
            'update' => $update,
            'model' => $model,
            'tags_json' => (isset($_POST['Post']['tags'])) ? $_POST['Post']['tags'] : $tags_json,
            'select_categories' => $select_categories,
            'select_post_group' => $select_post_group,
        ));
    }

    public function actionAddStory() {

        $form_title = 'Add New Story';
        $update = 0;

        $tags_json = '[""]';
        $select_categories = array();
        $select_post_group = array();

        $model = new Post;
        $model_term_relationships = new TermRelationships;

        // for upload
        $cf = Yii::app()->file;
        $upload_path = Yii::app()->params['set_story_path'];
        $cf->createDir(0755, $upload_path);
        //
        $upload = Yii::app()->upload;
        $upload->folder = $upload_path;
        
        if (isset($_POST['Post'])) {

            $model->attributes = $_POST['Post'];

            $select_categories = isset($_POST['Post']['category']) ? $_POST['Post']['category'] : NULL;
            $select_post_group = isset($_POST['Post']['post_group']) ? $_POST['Post']['post_group'] : NULL;
            $model->post_group = $select_post_group[0];

            if ($model->validate()) {

                // upload image
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

                $model->post_type = 'story';

                /*                 * * * */
                if (isset($_POST['Post']['slug'])) {
                    foreach (Yii::app()->params['translatedLanguages'] as $l => $lang) {
                        if ($l === Yii::app()->params['defaultLanguage']) {
                            $name = $_POST['Post']['post_title'];
                            $slug = $_POST['Post']['slug'];
                            if ($_POST['Post']['slug'] == '') {
                                // auto make slug from name
                                $model->slug = Slug::create($name);
                            } else {
                                // make slug from slug input
                                $model->slug = Slug::create($slug);
                            }
                            // convert title to alphabet
                            $model->post_name = Slug::to_alphabet($name);
                        } else {
                            $l_slug = 'slug_' . $l;
                            $l_name = 'post_title_' . $l;
                            $name = $_POST['Post'][$l_name];
                            $slug = $_POST['Post'][$l_slug];
                            if ($_POST['Post'][$l_slug] == '') {
                                // auto make slug from name
                                $model->{$l_slug} = Slug::create($name);
                            } else {
                                // make slug from slug input
                                $model->{$l_slug} = Slug::create($slug);
                            }
                            // convert title to alphabet
                            $l_post_name = 'post_name_' . $l;
                            $model->{$l_post_name} = Slug::to_alphabet($name);
                        }
                    }
                }
                /*                 * * * */

                if ($model->save()) {

                    $model_term_relationships->object_id = $model->id;

                    //relations with category
                    if (isset($_POST['Post']['category'])) {
                        $categories_id = $_POST['Post']['category'];
                        if ($categories_id != NULL) {
                            foreach ($categories_id as $value) {
                                $model_term_relationships->term_taxonomy_id = $value;
                                $model_term_relationships->isNewRecord = true;
                                $model_term_relationships->save();
                            }
                        }
                    }

                    // relations with tags
                    if (isset($_POST['Post']['tags'])) {
                        $this->create_tags($_POST['Post']['tags']);

                        $tags_id = $this->get_tags($_POST['Post']['tags']);
                        $i = 0;
                        foreach ($tags_id as $value) {
                            $model_term_relationships->term_taxonomy_id = $value;
                            $model_term_relationships->term_order = $i++;
                            $model_term_relationships->isNewRecord = true;
                            $model_term_relationships->save();
                        }
                    }

                    // redirect
                    $this->redirect(array('updateStory', 'id' => $model->id));
                }
            }
        }

        $this->render('stories/_form', array(
            'form_title' => $form_title,
            'update' => $update,
            'model' => $model,
            'tags_json' => (isset($_POST['Post']['tags'])) ? $_POST['Post']['tags'] : $tags_json,
            'select_categories' => $select_categories,
            'select_post_group' => $select_post_group,
        ));
    }

    /* Story - End */

    /* Scholarship - Begin */

    public function actionStatusScholarship() {

        $id = $_POST['id'];
        $model = Post::model()->change_status($id, 'scholarship');

        echo $model->disp_flag;
    }

    public function actionDeleteScholarship() {

        $id = $_POST['id'];
        $post_type = 'scholarship';

        $module_user = Yii::app()->getModule('user');
        $admin = $module_user->isAdmin();

        if ($admin == 1) {
            echo Post::model()->delete_post($id, $post_type);
        } elseif ($admin == 2) {
            echo Post::model()->delete_post($id, $post_type, Yii::app()->user->user_id);
        }
    }

    public function actionScholarship() {

        $model = new Post;
        $item_count = 0;
        $page_size = Yii::app()->params['pageSize'];
        $condition = '';
        $pages = NULL;
        $post_type = 'scholarship';
        $keyword = '';

        if (isset($_REQUEST['keyword'])) {
            $keyword = $_REQUEST['keyword'];
        }

        $module_user = Yii::app()->getModule('user');
        $admin = $module_user->isAdmin();

        if ($admin == 1) {
            // administrator, see all stories of another
            $condition = " post_type = '$post_type' ";
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
                'order' => 'post_date DESC',
                'limit' => $pages->limit,
                'offset' => $pages->offset,
            ));

            $posts = $model->multilang()->findAll($criteria);
        } elseif ($admin == 2) {
            // normal admin, only see what they posted
            $condition = " post_type = '$post_type' AND post_author = " . Yii::app()->user->user_id;
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
                'order' => 'post_date DESC',
                'limit' => $pages->limit,
                'offset' => $pages->offset,
            ));

            $posts = $model->multilang()->findAll($criteria);
        }

        $this->render('scholarship/index', array(
            'posts' => $posts,
            'item_count' => $item_count,
            'page_size' => $page_size,
            'pages' => $pages,
            'keyword' => $keyword,
        ));
    }

    public function actionUpdatePickScholarship() {

        $post_type = 'scholarship';
        $pick_ids = json_decode($_POST['data']);

        $result = PickRelationships::model()->delete_add_picks($post_type, $pick_ids);

        echo $result;
    }

    public function actionPickScholarship() {

        $model = new Post;
        $item_count = 0;
//        $page_size = Yii::app()->params['pageSize'];
        $page_size = 10;
        $condition = '';
        $pages = NULL;
        $post_type = 'scholarship';
        $pick_list = '';

        $module_user = Yii::app()->getModule('user');
        $admin = $module_user->isAdmin();

        $our_picks = PickRelationships::model()->get_relate_pick($post_type)->findAll();

        if ($admin == 1) {
            // administrator, see all stories of another
            $condition = " post_type = '$post_type' ";
            $item_count = $model->count($condition);

            // the pagination itself
            $pages = new CPagination($item_count);
            $pages->setPageSize($page_size);

            $criteria = new CDbCriteria(array(
                'condition' => $condition,
                'order' => 'post_date DESC',
                'limit' => $pages->limit,
                'offset' => $pages->offset,
            ));

            $posts = $model->multilang()->findAll($criteria);
        } elseif ($admin == 2) {
            // normal admin, only see what they posted
            $condition = " post_type = '$post_type' AND post_author = " . Yii::app()->user->user_id;
            $item_count = $model->count($condition);

            // the pagination itself
            $pages = new CPagination($item_count);
            $pages->setPageSize($page_size);

            $criteria = new CDbCriteria(array(
                'condition' => $condition,
                'order' => 'post_date DESC',
                'limit' => $pages->limit,
                'offset' => $pages->offset,
            ));

            $posts = $model->multilang()->findAll($criteria);
        }

        $list = '';
        if (Yii::app()->request->isAjaxRequest) {

            $list = $this->renderPartial('scholarship/_list', array(
                'posts' => $posts,
                    ), false);
        } else {
            $this->render('scholarship/ourpicks', array(
                'posts' => $posts,
                'item_count' => $item_count,
                'page_size' => $page_size,
                'pages' => $pages,
                'list' => $list,
                'our_picks' => $our_picks,
            ));
        }
    }

    public function actionUpdateScholarship($id) {

        $form_title = 'Update Scholarship';
        $update = 1;

        $tags_arr = array();
        $tags_json = '[""]';
        $select_categories = array();
        $select_provider = array();
        $select_type_of_school = array();

        $model = $this->loadModel($id, true);
        $model_term_relationships = new TermRelationships;

        // for upload
        $cf = Yii::app()->file;
        $upload_path = Yii::app()->params['set_scholarship_path'];
        $cf->createDir(0755, $upload_path);
        //
        $upload_path_school_logo = Yii::app()->params['set_school_logo'];
        $cf->createDir(0755, $upload_path_school_logo);
        //
        $upload = Yii::app()->upload;
        $upload->folder = $upload_path;
        
        if (!isset($_POST['Post']['category'])) {
            $hold_categories = array();
            $categories = $model_term_relationships->get_relate_terms((int) $id, 'category')->findAll();
            if ($categories != NULL) {
                foreach ($categories as $row) {
                    $select_categories[] = $row->term_taxonomy_id;
                }
            }
        } else {
            $select_categories = $_POST['Post']['category'];
        }
        
        if (!isset($_POST['Post']['prvider'])) {
            $select_provider[] = $model->provider;
        }
        
        if (!isset($_POST['Post']['type_of_school'])) {
            $select_type_of_school[] = $model->type_of_school;
        }

        if (!isset($_POST['Post']['tags'])) {
            $tags = $model_term_relationships->get_relate_terms((int) $id, 'tag')->findAll();
            if ($tags != NULL) {
                foreach ($tags as $row) {
                    $tags_arr[] = $row->termtaxonomy->terms['name'];
                }
                $tags_json = json_encode($tags_arr);
            }
        }

        if (isset($_POST['Post'])) {
            $model->attributes = $_POST['Post'];
            
            $provider = isset($_POST['Post']['provider']) ? $_POST['Post']['provider'][0] : '';
            $type_of_schoool = isset($_POST['Post']['type_of_school']) ? $_POST['Post']['type_of_school'][0] : '';
            $model->provider = $provider;
            $model->type_of_school = $type_of_schoool;

            if ($model->validate()) {

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
                
                // upload school logo
                $upload->folder = $upload_path_school_logo;
                if (CUploadedFile::getInstance($model, 'school_logo') != NULL) {
                    $upload->old_file = $model->school_logo;
                    $upload->post = $model->school_logo = CUploadedFile::getInstance($model, 'school_logo');
                    $model->school_logo = $upload->normal();
                }

                /*                 * *** */
                if (isset($_POST['Post']['slug'])) {
                    foreach (Yii::app()->params['translatedLanguages'] as $l => $lang) {
                        if ($l === Yii::app()->params['defaultLanguage']) {
                            $name = $_POST['Post']['post_title'];
                            $slug = $_POST['Post']['slug'];
                            if ($_POST['Post']['slug'] == '') {
                                // auto make slug from name
                                $model->slug = Slug::create($name);
                            } else {
                                // make slug from slug input
                                $model->slug = Slug::create($slug);
                            }
                            // get amount
                            $award = $_POST['Post']['award'];
                            $model->s_award = Common::get_amount($award);
                            // convert title to alphabet
                            $model->post_name = Slug::to_alphabet($name);
                        } else {
                            $l_slug = 'slug_' . $l;
                            $l_name = 'post_title_' . $l;
                            $name = $_POST['Post'][$l_name];
                            $slug = $_POST['Post'][$l_slug];
                            if ($_POST['Post'][$l_slug] == '') {
                                // auto make slug from name
                                $model->{$l_slug} = Slug::create($name);
                            } else {
                                // make slug from slug input
                                $model->{$l_slug} = Slug::create($slug);
                            }
                            // get amount
                            $l_award = 'award_' . $l;
                            $l_s_award = 's_award_' . $l;
                            $award = $_POST['Post'][$l_award];
                            $model->{$l_s_award} = Common::get_amount($award);
                            // convert title to alphabet
                            $l_post_name = 'post_name_' . $l;
                            $model->{$l_post_name} = Slug::to_alphabet($name);
                        }
                    }
                }
                /*                 * *** */

                $model->deadline = $_POST['Post']['deadline'] . ' 00:00:00';

                if ($model->save()) {

                    $model_term_relationships->object_id = $model->id;

                    //delete all relationships
                    $model_term_relationships->deleteAllByAttributes(array('object_id' => (int) $id));

                    //relations with category
                    if (isset($_POST['Post']['category'])) {
                        $categories_id = $_POST['Post']['category'];
                        if ($categories_id != NULL) {
                            foreach ($categories_id as $value) {
                                $model_term_relationships->term_taxonomy_id = $value;
                                $model_term_relationships->isNewRecord = true;
                                $model_term_relationships->save();
                            }
                        }
                    }

                    // relations with tags
                    if (isset($_POST['Post']['tags'])) {
                        $this->create_tags($_POST['Post']['tags']);

                        $tags_id = $this->get_tags($_POST['Post']['tags']);
                        $i = 0;
                        foreach ($tags_id as $value) {
                            $model_term_relationships->term_taxonomy_id = $value;
                            $model_term_relationships->term_order = $i++;
                            $model_term_relationships->isNewRecord = true;
                            $model_term_relationships->save();
                        }
                    }

                    // redirect
                    $this->redirect(array('updateScholarship', 'id' => $id));
                }
            }
        }


        $this->render('scholarship/_form', array(
            'form_title' => $form_title,
            'update' => $update,
            'model' => $model,
            'tags_json' => (isset($_POST['Post']['tags'])) ? $_POST['Post']['tags'] : $tags_json,
            'select_categories' => $select_categories,
            'select_provider' => $select_provider,
            'select_type_of_school' => $select_type_of_school,
        ));
    }

    public function actionAddScholarship() {

        $form_title = 'Add New Scholarship';
        $update = 0;

        $tags_json = '[""]';
        $select_categories = array();
        $select_provider = array();
        $select_type_of_school = array();

        $model = new Post;
        $model_term_relationships = new TermRelationships;

        // for upload
        $cf = Yii::app()->file;
        $upload_path = Yii::app()->params['set_scholarship_path'];
        $cf->createDir(0755, $upload_path);
        //
        $upload_path_school_logo = Yii::app()->params['set_school_logo'];
        $cf->createDir(0755, $upload_path_school_logo);
        //
        $upload = Yii::app()->upload;
        $upload->folder = $upload_path;
        
        if (isset($_POST['Post'])) {

            $model->attributes = $_POST['Post'];

            $select_categories = isset($_POST['Post']['category']) ? $_POST['Post']['category'] : NULL;
            $select_provider = isset($_POST['Post']['provider']) ? $_POST['Post']['provider'] : NULL;
            $select_type_of_school = isset($_POST['Post']['type_of_school']) ? $_POST['Post']['type_of_school'] : NULL;
            $model->provider = $select_provider[0];
            $model->type_of_school = $select_type_of_school[0];

            if ($model->validate()) {

                // upload image
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
                
                // upload school logo
                $upload->folder = $upload_path_school_logo;
                if (CUploadedFile::getInstance($model, 'school_logo') != NULL) {
                    $upload->post = $model->school_logo = CUploadedFile::getInstance($model, 'school_logo');
                    $model->school_logo = $upload->normal();
                }

                $model->post_type = 'scholarship';

                /*                 * * * */
                if (isset($_POST['Post']['slug'])) {
                    foreach (Yii::app()->params['translatedLanguages'] as $l => $lang) {
                        if ($l === Yii::app()->params['defaultLanguage']) {
                            $name = $_POST['Post']['post_title'];
                            $slug = $_POST['Post']['slug'];
                            if ($_POST['Post']['slug'] == '') {
                                // auto make slug from name
                                $model->slug = Slug::create($name);
                            } else {
                                // make slug from slug input
                                $model->slug = Slug::create($slug);
                            }
                            // get amount
                            $award = $_POST['Post']['award'];
                            $model->s_award = Common::get_amount($award);
                            // convert title to alphabet
                            $model->post_name = Slug::to_alphabet($name);
                        } else {
                            $l_slug = 'slug_' . $l;
                            $l_name = 'post_title_' . $l;
                            $name = $_POST['Post'][$l_name];
                            $slug = $_POST['Post'][$l_slug];
                            if ($_POST['Post'][$l_slug] == '') {
                                // auto make slug from name
                                $model->{$l_slug} = Slug::create($name);
                            } else {
                                // make slug from slug input
                                $model->{$l_slug} = Slug::create($slug);
                            }
                            // get amount
                            $l_award = 'award_' . $l;
                            $l_s_award = 's_award_' . $l;
                            $award = $_POST['Post'][$l_award];
                            $model->{$l_s_award} = Common::get_amount($award);
                            // convert title to alphabet
                            $l_post_name = 'post_name_' . $l;
                            $model->{$l_post_name} = Slug::to_alphabet($name);
                        }
                    }
                }
                /*                 * * * */

                $model->deadline = $_POST['Post']['deadline'] . ' 00:00:00';

                if ($model->save()) {

                    $model_term_relationships->object_id = $model->id;

                    //relations with category
                    if (isset($_POST['Post']['category'])) {
                        $categories_id = $_POST['Post']['category'];
                        if ($categories_id != NULL) {
                            foreach ($categories_id as $value) {
                                $model_term_relationships->term_taxonomy_id = $value;
                                $model_term_relationships->isNewRecord = true;
                                $model_term_relationships->save();
                            }
                        }
                    }

                    // relations with tags
                    if (isset($_POST['Post']['tags'])) {
                        $this->create_tags($_POST['Post']['tags']);

                        $tags_id = $this->get_tags($_POST['Post']['tags']);
                        $i = 0;
                        foreach ($tags_id as $value) {
                            $model_term_relationships->term_taxonomy_id = $value;
                            $model_term_relationships->term_order = $i++;
                            $model_term_relationships->isNewRecord = true;
                            $model_term_relationships->save();
                        }
                    }

                    // redirect
                    $this->redirect(array('updateScholarship', 'id' => $model->id));
                }
            }
        }

        $this->render('scholarship/_form', array(
            'form_title' => $form_title,
            'update' => $update,
            'model' => $model,
            'tags_json' => (isset($_POST['Post']['tags'])) ? $_POST['Post']['tags'] : $tags_json,
            'select_categories' => $select_categories,
            'select_provider' => $select_provider,
            'select_type_of_school' => $select_type_of_school,
        ));
    }

    /* Scholarship - End */

    /* Internship - Begin */

    public function actionStatusInternship() {

        $id = $_POST['id'];
        $model = Post::model()->change_status($id, 'internship');

        echo $model->disp_flag;
    }

    public function actionDeleteInternship() {

        $id = $_POST['id'];
        $post_type = 'internship';

        $module_user = Yii::app()->getModule('user');
        $admin = $module_user->isAdmin();

        if ($admin == 1) {
            echo Post::model()->delete_post($id, $post_type);
        } elseif ($admin == 2) {
            echo Post::model()->delete_post($id, $post_type, Yii::app()->user->user_id);
        }
    }

    public function actionInternship() {

        $model = new Post;
        $item_count = 0;
        $page_size = Yii::app()->params['pageSize'];
        $condition = '';
        $pages = NULL;
        $post_type = 'internship';
        $keyword = '';

        if (isset($_REQUEST['keyword'])) {
            $keyword = $_REQUEST['keyword'];
        }

        $module_user = Yii::app()->getModule('user');
        $admin = $module_user->isAdmin();

        if ($admin == 1) {
            // administrator, see all stories of another
            $condition = " post_type = '$post_type' ";
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
                'order' => 'post_date DESC',
                'limit' => $pages->limit,
                'offset' => $pages->offset,
            ));

            $posts = $model->multilang()->findAll($criteria);
        } elseif ($admin == 2) {
            // normal admin, only see what they posted
            $condition = " post_type = '$post_type' AND post_author = " . Yii::app()->user->user_id;
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
                'order' => 'post_date DESC',
                'limit' => $pages->limit,
                'offset' => $pages->offset,
            ));

            $posts = $model->multilang()->findAll($criteria);
        }

        $this->render('internship/index', array(
            'posts' => $posts,
            'item_count' => $item_count,
            'page_size' => $page_size,
            'pages' => $pages,
            'keyword' => $keyword,
        ));
    }

    public function actionUpdatePickInternship() {

        $post_type = 'internship';
        $pick_ids = json_decode($_POST['data']);

        $result = PickRelationships::model()->delete_add_picks($post_type, $pick_ids);

        echo $result;
    }

    public function actionPickInternship() {

        $model = new Post;
        $item_count = 0;
//        $page_size = Yii::app()->params['pageSize'];
        $page_size = 10;
        $condition = '';
        $pages = NULL;
        $post_type = 'internship';
        $pick_list = '';

        $module_user = Yii::app()->getModule('user');
        $admin = $module_user->isAdmin();

        $our_picks = PickRelationships::model()->get_relate_pick($post_type)->findAll();

        if ($admin == 1) {
            // administrator, see all stories of another
            $condition = " post_type = '$post_type' ";
            $item_count = $model->count($condition);

            // the pagination itself
            $pages = new CPagination($item_count);
            $pages->setPageSize($page_size);

            $criteria = new CDbCriteria(array(
                'condition' => $condition,
                'order' => 'post_date DESC',
                'limit' => $pages->limit,
                'offset' => $pages->offset,
            ));

            $posts = $model->multilang()->findAll($criteria);
        } elseif ($admin == 2) {
            // normal admin, only see what they posted
            $condition = " post_type = '$post_type' AND post_author = " . Yii::app()->user->user_id;
            $item_count = $model->count($condition);

            // the pagination itself
            $pages = new CPagination($item_count);
            $pages->setPageSize($page_size);

            $criteria = new CDbCriteria(array(
                'condition' => $condition,
                'order' => 'post_date DESC',
                'limit' => $pages->limit,
                'offset' => $pages->offset,
            ));

            $posts = $model->multilang()->findAll($criteria);
        }

        $list = '';
        if (Yii::app()->request->isAjaxRequest) {

            $list = $this->renderPartial('internship/_list', array(
                'posts' => $posts,
                    ), false);
        } else {
            $this->render('internship/ourpicks', array(
                'posts' => $posts,
                'item_count' => $item_count,
                'page_size' => $page_size,
                'pages' => $pages,
                'list' => $list,
                'our_picks' => $our_picks,
            ));
        }
    }

    public function actionUpdateInternship($id) {

        $form_title = 'Update Internship';
        $update = 1;

        $tags_arr = array();
        $tags_json = '[""]';
        $select_categories = array();
        $select_provider = array();
        $select_type_of_school = array();

        $model = $this->loadModel($id, true);
        $model_term_relationships = new TermRelationships;

        // for upload
        $cf = Yii::app()->file;
        $upload_path = Yii::app()->params['set_internship_path'];
        $cf->createDir(0755, $upload_path);
        //
        $upload_path_school_logo = Yii::app()->params['set_school_logo'];
        $cf->createDir(0755, $upload_path_school_logo);
        //
        $upload = Yii::app()->upload;
        $upload->folder = $upload_path;
        
        if (!isset($_POST['Post']['category'])) {
            $hold_categories = array();
            $categories = $model_term_relationships->get_relate_terms((int) $id, 'category')->findAll();
            if ($categories != NULL) {
                foreach ($categories as $row) {
                    $select_categories[] = $row->term_taxonomy_id;
                }
            }
        } else {
            $select_categories = $_POST['Post']['category'];
        }
        
        if (!isset($_POST['Post']['prvider'])) {
            $select_provider[] = $model->provider;
        }
        
        if (!isset($_POST['Post']['type_of_school'])) {
            $select_type_of_school[] = $model->type_of_school;
        }

        if (!isset($_POST['Post']['tags'])) {
            $tags = $model_term_relationships->get_relate_terms((int) $id, 'tag')->findAll();
            if ($tags != NULL) {
                foreach ($tags as $row) {
                    $tags_arr[] = $row->termtaxonomy->terms['name'];
                }
                $tags_json = json_encode($tags_arr);
            }
        }

        if (isset($_POST['Post'])) {
            $model->attributes = $_POST['Post'];
            
            $provider = $_POST['Post']['provider'];
            $type_of_schoool = isset($_POST['Post']['type_of_school']) ? $_POST['Post']['type_of_school'][0] : '';
            $model->provider = $provider[0];
            $model->type_of_school = $type_of_schoool;
            
            if ($model->validate()) {
                
                //
                $model->flag_credit = isset($_POST['Post']['flag_credit']) ? 1 : 0;
                $model->flag_partime = isset($_POST['Post']['flag_partime']) ? 1 : 0;
                $model->flag_fulltime = isset($_POST['Post']['flag_fulltime']) ? 1 : 0;
                $model->flag_paid = isset($_POST['Post']['flag_paid']) ? 1 : 0;

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
                
                // upload school logo
                $upload->folder = $upload_path_school_logo;
                if (CUploadedFile::getInstance($model, 'school_logo') != NULL) {
                    $upload->old_file = $model->school_logo;
                    $upload->post = $model->school_logo = CUploadedFile::getInstance($model, 'school_logo');
                    $model->school_logo = $upload->normal();
                }

                /*                 * *** */
                if (isset($_POST['Post']['slug'])) {
                    foreach (Yii::app()->params['translatedLanguages'] as $l => $lang) {
                        if ($l === Yii::app()->params['defaultLanguage']) {
                            $name = $_POST['Post']['post_title'];
                            $slug = $_POST['Post']['slug'];
                            if ($_POST['Post']['slug'] == '') {
                                // auto make slug from name
                                $model->slug = Slug::create($name);
                            } else {
                                // make slug from slug input
                                $model->slug = Slug::create($slug);
                            }
                            // get amount
                            $award = $_POST['Post']['award'];
                            $model->s_award = Common::get_amount($award);
                            // convert title to alphabet
                            $model->post_name = Slug::to_alphabet($name);
                            //
                            $job_title = $_POST['Post']['job_title'];
                            $model->job_title_clean = Slug::to_alphabet($job_title);
                            //
                            $position = $_POST['Post']['position'];
                            $model->position_clean = Slug::to_alphabet($position);
                        } else {
                            $l_slug = 'slug_' . $l;
                            $l_name = 'post_title_' . $l;
                            $name = $_POST['Post'][$l_name];
                            $slug = $_POST['Post'][$l_slug];
                            if ($_POST['Post'][$l_slug] == '') {
                                // auto make slug from name
                                $model->{$l_slug} = Slug::create($name);
                            } else {
                                // make slug from slug input
                                $model->{$l_slug} = Slug::create($slug);
                            }
                            // get amount
                            $l_award = 'award_' . $l;
                            $l_s_award = 's_award_' . $l;
                            $award = $_POST['Post'][$l_award];
                            $model->{$l_s_award} = Common::get_amount($award);
                            // convert title to alphabet
                            $l_post_name = 'post_name_' . $l;
                            $model->{$l_post_name} = Slug::to_alphabet($name);
                            //
                            $l_job_title = 'job_title_' . $l;
                            $l_job_title_clean = 'job_title_clean_' . $l;
                            $job_title = $_POST['Post'][$l_job_title];
                            $model->{$l_job_title_clean} = Slug::to_alphabet($job_title);
                            //
                            $l_position = 'position_' . $l;
                            $l_position_clean = 'position_clean_' . $l;
                            $position = $_POST['Post'][$l_position];
                            $model->{$l_position_clean} = Slug::to_alphabet($position);
                        }
                    }
                }
                /*                 * *** */

                $model->deadline = $_POST['Post']['deadline'] . ' 00:00:00';

                if ($model->save()) {

                    $model_term_relationships->object_id = $model->id;

                    //delete all relationships
                    $model_term_relationships->deleteAllByAttributes(array('object_id' => (int) $id));

                    //relations with category
                    if (isset($_POST['Post']['category'])) {
                        $categories_id = $_POST['Post']['category'];
                        if ($categories_id != NULL) {
                            foreach ($categories_id as $value) {
                                $model_term_relationships->term_taxonomy_id = $value;
                                $model_term_relationships->isNewRecord = true;
                                $model_term_relationships->save();
                            }
                        }
                    }

                    // relations with tags
                    if (isset($_POST['Post']['tags'])) {
                        $this->create_tags($_POST['Post']['tags']);

                        $tags_id = $this->get_tags($_POST['Post']['tags']);
                        $i = 0;
                        foreach ($tags_id as $value) {
                            $model_term_relationships->term_taxonomy_id = $value;
                            $model_term_relationships->term_order = $i++;
                            $model_term_relationships->isNewRecord = true;
                            $model_term_relationships->save();
                        }
                    }

                    // redirect
                    $this->redirect(array('updateInternship', 'id' => $id));
                }
            }
        }


        $this->render('internship/_form', array(
            'form_title' => $form_title,
            'update' => $update,
            'model' => $model,
            'tags_json' => (isset($_POST['Post']['tags'])) ? $_POST['Post']['tags'] : $tags_json,
            'select_categories' => $select_categories,
            'select_provider' => $select_provider,
            'select_type_of_school' => $select_type_of_school,
        ));
    }

    public function actionAddInternship() {

        $form_title = 'Add New Internship';
        $update = 0;

        $tags_json = '[""]';
        $select_categories = array();
        $select_provider = array();
        $select_type_of_school = array();

        $model = new Post;
        $model_term_relationships = new TermRelationships;

        // for upload
        $cf = Yii::app()->file;
        $upload_path = Yii::app()->params['set_internship_path'];
        $cf->createDir(0755, $upload_path);
        //
        $upload_path_school_logo = Yii::app()->params['set_school_logo'];
        $cf->createDir(0755, $upload_path_school_logo);
        //
        $upload = Yii::app()->upload;
        $upload->folder = $upload_path;
        
        if (isset($_POST['Post'])) {

            $model->attributes = $_POST['Post'];

            $select_categories = isset($_POST['Post']['category']) ? $_POST['Post']['category'] : NULL;
            $select_provider = isset($_POST['Post']['provider']) ? $_POST['Post']['provider'] : NULL;
            $select_type_of_school = isset($_POST['Post']['type_of_school']) ? $_POST['Post']['type_of_school'] : NULL;
            $model->provider = $select_provider[0];
            $model->type_of_school = $select_type_of_school[0];

            if ($model->validate()) {
                
                //
                $model->flag_credit = isset($_POST['Post']['flag_credit']) ? 1 : 0;
                $model->flag_partime = isset($_POST['Post']['flag_partime']) ? 1 : 0;
                $model->flag_fulltime = isset($_POST['Post']['flag_fulltime']) ? 1 : 0;
                $model->flag_paid = isset($_POST['Post']['flag_paid']) ? 1 : 0;

                // upload image
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
                
                // upload school logo
                $upload->folder = $upload_path_school_logo;
                if (CUploadedFile::getInstance($model, 'school_logo') != NULL) {
                    $upload->post = $model->school_logo = CUploadedFile::getInstance($model, 'school_logo');
                    $model->school_logo = $upload->normal();
                }

                $model->post_type = 'internship';

                /*                 * * * */
                if (isset($_POST['Post']['slug'])) {
                    foreach (Yii::app()->params['translatedLanguages'] as $l => $lang) {
                        if ($l === Yii::app()->params['defaultLanguage']) {
                            $name = $_POST['Post']['post_title'];
                            $slug = $_POST['Post']['slug'];
                            if ($_POST['Post']['slug'] == '') {
                                // auto make slug from name
                                $model->slug = Slug::create($name);
                            } else {
                                // make slug from slug input
                                $model->slug = Slug::create($slug);
                            }
                            // get amount
                            $award = $_POST['Post']['award'];
                            $model->s_award = Common::get_amount($award);
                            // convert title to alphabet
                            $model->post_name = Slug::to_alphabet($name);
                            //
                            $job_title = $_POST['Post']['job_title'];
                            $model->job_title_clean = Slug::to_alphabet($job_title);
                            //
                            $position = $_POST['Post']['position'];
                            $model->position_clean = Slug::to_alphabet($position);
                        } else {
                            $l_slug = 'slug_' . $l;
                            $l_name = 'post_title_' . $l;
                            $name = $_POST['Post'][$l_name];
                            $slug = $_POST['Post'][$l_slug];
                            if ($_POST['Post'][$l_slug] == '') {
                                // auto make slug from name
                                $model->{$l_slug} = Slug::create($name);
                            } else {
                                // make slug from slug input
                                $model->{$l_slug} = Slug::create($slug);
                            }
                            // get amount
                            $l_award = 'award_' . $l;
                            $l_s_award = 's_award_' . $l;
                            $award = $_POST['Post'][$l_award];
                            $model->{$l_s_award} = Common::get_amount($award);
                            // convert title to alphabet
                            $l_post_name = 'post_name_' . $l;
                            $model->{$l_post_name} = Slug::to_alphabet($name);
                            //
                            $l_job_title = 'job_title_' . $l;
                            $l_job_title_clean = 'job_title_clean_' . $l;
                            $job_title = $_POST['Post'][$l_job_title];
                            $model->{$l_job_title_clean} = Slug::to_alphabet($job_title);
                            //
                            $l_position = 'position_' . $l;
                            $l_position_clean = 'position_clean_' . $l;
                            $position = $_POST['Post'][$l_position];
                            $model->{$l_position_clean} = Slug::to_alphabet($position);
                        }
                    }
                }
                /*                 * * * */

                $model->deadline = $_POST['Post']['deadline'] . ' 00:00:00';

                if ($model->save()) {

                    $model_term_relationships->object_id = $model->id;

                    //relations with category
                    if (isset($_POST['Post']['category'])) {
                        $categories_id = $_POST['Post']['category'];
                        if ($categories_id != NULL) {
                            foreach ($categories_id as $value) {
                                $model_term_relationships->term_taxonomy_id = $value;
                                $model_term_relationships->isNewRecord = true;
                                $model_term_relationships->save();
                            }
                        }
                    }

                    // relations with tags
                    if (isset($_POST['Post']['tags'])) {
                        $this->create_tags($_POST['Post']['tags']);

                        $tags_id = $this->get_tags($_POST['Post']['tags']);
                        $i = 0;
                        foreach ($tags_id as $value) {
                            $model_term_relationships->term_taxonomy_id = $value;
                            $model_term_relationships->term_order = $i++;
                            $model_term_relationships->isNewRecord = true;
                            $model_term_relationships->save();
                        }
                    }

                    // redirect
                    $this->redirect(array('updateInternship', 'id' => $model->id));
                }
            }
        }

        $this->render('internship/_form', array(
            'form_title' => $form_title,
            'update' => $update,
            'model' => $model,
            'tags_json' => (isset($_POST['Post']['tags'])) ? $_POST['Post']['tags'] : $tags_json,
            'select_categories' => $select_categories,
            'select_provider' => $select_provider,
            'select_type_of_school' => $select_type_of_school,
        ));
    }

    /* Internship - End */

    /* College - Begin */

    public function actionStatusCollege() {

        $id = $_POST['id'];
        $model = Post::model()->change_status($id, 'college');

        echo $model->disp_flag;
    }

    public function actionDeleteCollege() {

        $id = $_POST['id'];
        $post_type = 'college';

        $module_user = Yii::app()->getModule('user');
        $admin = $module_user->isAdmin();

        if ($admin == 1) {
            echo Post::model()->delete_post($id, $post_type);
        } elseif ($admin == 2) {
            echo Post::model()->delete_post($id, $post_type, Yii::app()->user->user_id);
        }
    }

    public function actionCollege() {

        $model = new Post;
        $item_count = 0;
        $page_size = Yii::app()->params['pageSize'];
        $condition = '';
        $pages = NULL;
        $post_type = 'college';
        $keyword = '';

        if (isset($_REQUEST['keyword'])) {
            $keyword = $_REQUEST['keyword'];
        }

        $module_user = Yii::app()->getModule('user');
        $admin = $module_user->isAdmin();

        if ($admin == 1) {
            // administrator, see all stories of another
            $condition = " post_type = '$post_type' ";
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
                'order' => 'post_date DESC',
                'limit' => $pages->limit,
                'offset' => $pages->offset,
            ));

            $posts = $model->multilang()->findAll($criteria);
        } elseif ($admin == 2) {
            // normal admin, only see what they posted
            $condition = " post_type = '$post_type' AND post_author = " . Yii::app()->user->user_id;
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
                'order' => 'post_date DESC',
                'limit' => $pages->limit,
                'offset' => $pages->offset,
            ));

            $posts = $model->multilang()->findAll($criteria);
        }

        $this->render('college/index', array(
            'posts' => $posts,
            'item_count' => $item_count,
            'page_size' => $page_size,
            'pages' => $pages,
            'keyword' => $keyword,
        ));
    }

    public function actionUpdatePickCollege() {

        $post_type = 'college';
        $pick_ids = json_decode($_POST['data']);

        $result = PickRelationships::model()->delete_add_picks($post_type, $pick_ids);

        echo $result;
    }

    public function actionPickCollege() {

        $model = new Post;
        $item_count = 0;
//        $page_size = Yii::app()->params['pageSize'];
        $page_size = 10;
        $condition = '';
        $pages = NULL;
        $post_type = 'college';
        $pick_list = '';

        $module_user = Yii::app()->getModule('user');
        $admin = $module_user->isAdmin();

        $our_picks = PickRelationships::model()->get_relate_pick($post_type)->findAll();

        if ($admin == 1) {
            // administrator, see all stories of another
            $condition = " post_type = '$post_type' ";
            $item_count = $model->count($condition);

            // the pagination itself
            $pages = new CPagination($item_count);
            $pages->setPageSize($page_size);

            $criteria = new CDbCriteria(array(
                'condition' => $condition,
                'order' => 'post_date DESC',
                'limit' => $pages->limit,
                'offset' => $pages->offset,
            ));

            $posts = $model->multilang()->findAll($criteria);
        } elseif ($admin == 2) {
            // normal admin, only see what they posted
            $condition = " post_type = '$post_type' AND post_author = " . Yii::app()->user->user_id;
            $item_count = $model->count($condition);

            // the pagination itself
            $pages = new CPagination($item_count);
            $pages->setPageSize($page_size);

            $criteria = new CDbCriteria(array(
                'condition' => $condition,
                'order' => 'post_date DESC',
                'limit' => $pages->limit,
                'offset' => $pages->offset,
            ));

            $posts = $model->multilang()->findAll($criteria);
        }

        $list = '';
        if (Yii::app()->request->isAjaxRequest) {

            $list = $this->renderPartial('college/_list', array(
                'posts' => $posts,
                    ), false);
        } else {
            $this->render('college/ourpicks', array(
                'posts' => $posts,
                'item_count' => $item_count,
                'page_size' => $page_size,
                'pages' => $pages,
                'list' => $list,
                'our_picks' => $our_picks,
            ));
        }
    }

    public function actionUpdateCollege($id) {

        $form_title = 'Update College';
        $update = 1;

        $tags_arr = array();
        $tags_json = '[""]';
        $select_categories = array();
        $select_type_of_school = array();
        $select_campus_setting = array();

        $model = $this->loadModel($id, true);
        $model_term_relationships = new TermRelationships;

        // for upload
        $cf = Yii::app()->file;
        $upload_path = Yii::app()->params['set_college_path'];
        $cf->createDir(0755, $upload_path);
        //
        $upload_path_school_logo = Yii::app()->params['set_school_logo'];
        $cf->createDir(0755, $upload_path_school_logo);
        //
        $upload = Yii::app()->upload;
        $upload->folder = $upload_path;
        
        if (!isset($_POST['Post']['category'])) {
            $hold_categories = array();
            $categories = $model_term_relationships->get_relate_terms((int) $id, 'category')->findAll();
            if ($categories != NULL) {
                foreach ($categories as $row) {
                    $select_categories[] = $row->term_taxonomy_id;
                }
            }
        } else {
            $select_categories = $_POST['Post']['category'];
        }
        
        if (!isset($_POST['Post']['type_of_school'])) {
            $select_type_of_school[] = $model->type_of_school;
        }
        
        if (!isset($_POST['Post']['campus'])) {
            $select_campus_setting[] = $model->campus;
        }

        if (!isset($_POST['Post']['tags'])) {
            $tags = $model_term_relationships->get_relate_terms((int) $id, 'tag')->findAll();
            if ($tags != NULL) {
                foreach ($tags as $row) {
                    $tags_arr[] = $row->termtaxonomy->terms['name'];
                }
                $tags_json = json_encode($tags_arr);
            }
        }

        if (isset($_POST['Post'])) {
            
            $model->attributes = $_POST['Post'];
            
            $type_of_schoool = isset($_POST['Post']['type_of_school']) ? $_POST['Post']['type_of_school'][0] : '';
            $campus_setting = isset($_POST['Post']['campus']) ? $_POST['Post']['campus'][0] : '';
            $model->type_of_school = $type_of_schoool;
            $model->campus = $campus_setting;
            
            if ($model->validate()) {
                
                // upload image - update
                foreach (Yii::app()->params['translatedLanguages'] as $l => $lang) {
                    if ($l === Yii::app()->params['defaultLanguage']) {
                        if (CUploadedFile::getInstance($model, 'image') != NULL) {
                            $upload->old_file = $model->image;
                            $upload->post = $model->image = CUploadedFile::getInstance($model, 'image');
                            $model->image = $upload->normal();
                        }
                        // college logo
                        if (CUploadedFile::getInstance($model, 'feature_image') != NULL) {
                            $upload->old_file = $model->feature_image;
                            $upload->post = $model->feature_image = CUploadedFile::getInstance($model, 'feature_image');
                            $model->feature_image = $upload->normal();
                        }
                    } else {
                        $l_image = 'image_' . $l;
                        if (CUploadedFile::getInstance($model, $l_image) != NULL) {
                            $upload->old_file = $model->{$l_image};
                            $upload->post = $model->{$l_image} = CUploadedFile::getInstance($model, $l_image);
                            $model->{$l_image} = $upload->normal();
                        }
                        // college logo
                        $l_feature_image = 'feature_image_' . $l;
                        if (CUploadedFile::getInstance($model, $l_feature_image) != NULL) {
                            $upload->old_file = $model->{$l_feature_image};
                            $upload->post = $model->{$l_feature_image} = CUploadedFile::getInstance($model, $l_feature_image);
                            $model->{$l_feature_image} = $upload->normal();
                        }
                    }
                }
                
                // upload school logo
                $upload->folder = $upload_path_school_logo;
                if (CUploadedFile::getInstance($model, 'school_logo') != NULL) {
                    $upload->old_file = $model->school_logo;
                    $upload->post = $model->school_logo = CUploadedFile::getInstance($model, 'school_logo');
                    $model->school_logo = $upload->normal();
                }

                /*                 * *** */
                if (isset($_POST['Post']['slug'])) {
                    foreach (Yii::app()->params['translatedLanguages'] as $l => $lang) {
                        if ($l === Yii::app()->params['defaultLanguage']) {
                            $name = $_POST['Post']['post_title'];
                            $slug = $_POST['Post']['slug'];
                            if ($_POST['Post']['slug'] == '') {
                                // auto make slug from name
                                $model->slug = Slug::create($name);
                            } else {
                                // make slug from slug input
                                $model->slug = Slug::create($slug);
                            }
                            // get amount
//                            $award = $_POST['Post']['award'];
                            $award = $_POST['Post']['give_out_tutition'];
                            $model->s_award = Common::get_amount($award);
                            // convert title to alphabet
                            $model->post_name = Slug::to_alphabet($name);
                        } else {
                            $l_slug = 'slug_' . $l;
                            $l_name = 'post_title_' . $l;
                            $name = $_POST['Post'][$l_name];
                            $slug = $_POST['Post'][$l_slug];
                            if ($_POST['Post'][$l_slug] == '') {
                                // auto make slug from name
                                $model->{$l_slug} = Slug::create($name);
                            } else {
                                // make slug from slug input
                                $model->{$l_slug} = Slug::create($slug);
                            }
                            // get amount
                            $l_award = 'award_' . $l;
                            $l_s_award = 's_award_' . $l;
//                            $award = $_POST['Post'][$l_award];
                            $award = $_POST['Post']['give_out_tutition'];
                            $model->{$l_s_award} = Common::get_amount($award);
                            // convert title to alphabet
                            $l_post_name = 'post_name_' . $l;
                            $model->{$l_post_name} = Slug::to_alphabet($name);
                        }
                    }
                }
                /*                 * *** */

                $model->deadline = $_POST['Post']['deadline'] . ' 00:00:00';

                if ($model->save()) {

                    $model_term_relationships->object_id = $model->id;

                    //delete all relationships
                    $model_term_relationships->deleteAllByAttributes(array('object_id' => (int) $id));

                    //relations with category
                    if (isset($_POST['Post']['category'])) {
                        $categories_id = $_POST['Post']['category'];
                        if ($categories_id != NULL) {
                            foreach ($categories_id as $value) {
                                $model_term_relationships->term_taxonomy_id = $value;
                                $model_term_relationships->isNewRecord = true;
                                $model_term_relationships->save();
                            }
                        }
                    }

                    // relations with tags
                    if (isset($_POST['Post']['tags'])) {
                        $this->create_tags($_POST['Post']['tags']);

                        $tags_id = $this->get_tags($_POST['Post']['tags']);
                        $i = 0;
                        foreach ($tags_id as $value) {
                            $model_term_relationships->term_taxonomy_id = $value;
                            $model_term_relationships->term_order = $i++;
                            $model_term_relationships->isNewRecord = true;
                            $model_term_relationships->save();
                        }
                    }

                    // redirect
                    $this->redirect(array('updateCollege', 'id' => $id));
                }
            }
        }
        
        $this->render('college/_form', array(
            'form_title' => $form_title,
            'update' => $update,
            'model' => $model,
            'tags_json' => (isset($_POST['Post']['tags'])) ? $_POST['Post']['tags'] : $tags_json,
            'select_categories' => $select_categories,
            'select_type_of_school' => $select_type_of_school,
            'select_campus_setting' => $select_campus_setting,
        ));
    }

    public function actionAddCollege() {

        $form_title = 'Add New College';
        $update = 0;

        $tags_json = '[""]';
        $select_categories = array();
        $select_type_of_school = array();
        $select_campus_setting = array();

        $model = new Post;
        $model_term_relationships = new TermRelationships;

        // for upload
        $cf = Yii::app()->file;
        $upload_path = Yii::app()->params['set_college_path'];
        $cf->createDir(0755, $upload_path);
        //
        $upload_path_school_logo = Yii::app()->params['set_school_logo'];
        $cf->createDir(0755, $upload_path_school_logo);
        //
        $upload = Yii::app()->upload;
        $upload->folder = $upload_path;
        
        if (isset($_POST['Post'])) {

            $model->attributes = $_POST['Post'];

            $select_categories = isset($_POST['Post']['category']) ? $_POST['Post']['category'] : NULL;
            $select_type_of_school = isset($_POST['Post']['type_of_school']) ? $_POST['Post']['type_of_school'] : NULL;
            $select_campus_setting = isset($_POST['Post']['campus']) ? $_POST['Post']['campus'] : NULL;
            $model->type_of_school = $select_type_of_school[0];
            $model->campus = $select_campus_setting[0];

            if ($model->validate()) {
                
                // upload image
                foreach (Yii::app()->params['translatedLanguages'] as $l => $lang) {
                    if ($l === Yii::app()->params['defaultLanguage']) {
                        if (CUploadedFile::getInstance($model, 'image') != NULL) {
                            $upload->post = $model->image = CUploadedFile::getInstance($model, 'image');
                            $model->image = $upload->normal();
                        }
                        // college logo
                        if (CUploadedFile::getInstance($model, 'feature_image') != NULL) {
                            $upload->post = $model->feature_image = CUploadedFile::getInstance($model, 'feature_image');
                            $model->feature_image = $upload->normal();
                        }
                    } else {
                        $l_image = 'image_' . $l;
                        if (CUploadedFile::getInstance($model, $l_image) != NULL) {
                            $upload->post = $model->{$l_image} = CUploadedFile::getInstance($model, $l_image);
                            $model->{$l_image} = $upload->normal();
                        }
                        // college logo
                        $l_feature_image = 'feature_image_' . $l;
                        if (CUploadedFile::getInstance($model, $l_feature_image) != NULL) {
                            $upload->post = $model->{$l_feature_image} = CUploadedFile::getInstance($model, $l_feature_image);
                            $model->{$l_feature_image} = $upload->normal();
                        }
                    }
                }
                
                // upload school logo
                $upload->folder = $upload_path_school_logo;
                if (CUploadedFile::getInstance($model, 'school_logo') != NULL) {
                    $upload->post = $model->school_logo = CUploadedFile::getInstance($model, 'school_logo');
                    $model->school_logo = $upload->normal();
                }

                $model->post_type = 'college';

                /*                 * * * */
                if (isset($_POST['Post']['slug'])) {
                    foreach (Yii::app()->params['translatedLanguages'] as $l => $lang) {
                        if ($l === Yii::app()->params['defaultLanguage']) {
                            $name = $_POST['Post']['post_title'];
                            $slug = $_POST['Post']['slug'];
                            if ($_POST['Post']['slug'] == '') {
                                // auto make slug from name
                                $model->slug = Slug::create($name);
                            } else {
                                // make slug from slug input
                                $model->slug = Slug::create($slug);
                            }
                            // get amount
//                            $award = $_POST['Post']['award'];
                            $award = $_POST['Post']['give_out_tutition'];
                            $model->s_award = Common::get_amount($award);
                            // convert title to alphabet
                            $model->post_name = Slug::to_alphabet($name);
                        } else {
                            $l_slug = 'slug_' . $l;
                            $l_name = 'post_title_' . $l;
                            $name = $_POST['Post'][$l_name];
                            $slug = $_POST['Post'][$l_slug];
                            if ($_POST['Post'][$l_slug] == '') {
                                // auto make slug from name
                                $model->{$l_slug} = Slug::create($name);
                            } else {
                                // make slug from slug input
                                $model->{$l_slug} = Slug::create($slug);
                            }
                            // get amount
                            $l_award = 'award_' . $l;
                            $l_s_award = 's_award_' . $l;
//                            $award = $_POST['Post'][$l_award];
                            $award = $_POST['Post']['give_out_tutition'];
                            $model->{$l_s_award} = Common::get_amount($award);
                            // convert title to alphabet
                            $l_post_name = 'post_name_' . $l;
                            $model->{$l_post_name} = Slug::to_alphabet($name);
                        }
                    }
                }
                /*                 * * * */

                $model->deadline = $_POST['Post']['deadline'] . ' 00:00:00';

                if ($model->save()) {

                    $model_term_relationships->object_id = $model->id;

                    //relations with category
                    if (isset($_POST['Post']['category'])) {
                        $categories_id = $_POST['Post']['category'];
                        if ($categories_id != NULL) {
                            foreach ($categories_id as $value) {
                                $model_term_relationships->term_taxonomy_id = $value;
                                $model_term_relationships->isNewRecord = true;
                                $model_term_relationships->save();
                            }
                        }
                    }

                    // relations with tags
                    if (isset($_POST['Post']['tags'])) {
                        $this->create_tags($_POST['Post']['tags']);

                        $tags_id = $this->get_tags($_POST['Post']['tags']);
                        $i = 0;
                        foreach ($tags_id as $value) {
                            $model_term_relationships->term_taxonomy_id = $value;
                            $model_term_relationships->term_order = $i++;
                            $model_term_relationships->isNewRecord = true;
                            $model_term_relationships->save();
                        }
                    }

                    // redirect
                    $this->redirect(array('updateCollege', 'id' => $model->id));
                }
            }
        }

        $this->render('college/_form', array(
            'form_title' => $form_title,
            'update' => $update,
            'model' => $model,
            'tags_json' => (isset($_POST['Post']['tags'])) ? $_POST['Post']['tags'] : $tags_json,
            'select_categories' => $select_categories,
            'select_type_of_school' => $select_type_of_school,
            'select_campus_setting' => $select_campus_setting,
        ));
    }

    /* College - End */

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Post the loaded model
     * @throws CHttpException
     */
    public function loadModel($id, $ml = false) {
        if ($ml) {
            $model = Post::model()->multilang()->findByPk((int) $id);
        } else {
            $model = Post::model()->findByPk((int) $id);
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
