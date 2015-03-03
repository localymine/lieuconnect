<?php

class CollegeController extends FrontController {

    public $lang = 'vi';
    public $post_type = 'college';
    public $limit_college = 20;
    public $cate_major = 'major';
    public $cate_location = 'location';
    public $pageSize = 20;
    public $limit_our_picks = 12;

    public function init() {
        $this->lang = Yii::app()->language;

        $this->limit_college = Yii::app()->setting->getValue('SIZE_OF_NEW_COLLEGE');
        $this->limit_our_picks = Yii::app()->setting->getValue('SIZE_OF_OUR_PICKS_COLLEGE');
    }

    public function actionIndex() {

        $model = new Post;

        // get new internship
        $model_college = Post::model()->localized($this->lang)->get_college($this->limit_college, 1)->findAll();

        $this->render('index', array(
            'select_major' => array(),
            'select_location' => array(),
            'model' => $model,
            'model_college' => $model_college,
        ));
    }

    public function actionShow($slug) {
        $post_from_slug = $this->loadModelSlug($slug);
        $post = $this->loadModel($post_from_slug->id, true);
        //
        // update read status
        if (!Yii::app()->user->isGuest) {
            if (isset($post->read_later[0]->flag_read) && $post->read_later[0]->flag_read == 0) {
                ReadLater::model()->update_status_read($post_from_slug->id);
            }
        }
        //
        $model_our_picks = PickRelationships::model()->get_our_picks($this->post_type, $this->limit_our_picks)->findAll();
        //
        $this->render('show', array(
            'post' => $post,
            'model_our_picks' => $model_our_picks,
        ));
    }

    public function loadModelSlug($slug) {
        
        foreach (Yii::app()->request->languages as $l => $language) {
            $model = Post::model()->localized($l)->get_post_by_slug($slug, $this->post_type)->find();
            if ($model !== null) {
                return $model;
            }
        }
        //
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
