<?php

class StoryController extends FrontController {

    public $lang = 'vi';
    public $post_type = 'story';
    public $limit = 6;
    public $limit_our_picks = 12;
    public $limit_our_curators = 12;
    public $limit_posts_of_curator = 12;

    public function init() {
        $this->lang = Yii::app()->language;

        $this->limit = Yii::app()->setting->getValue('SIZE_OF_STORY');
        $this->limit_our_picks = Yii::app()->setting->getValue('SIZE_OF_OUR_PICKS_STORY');
        $this->limit_our_curators = Yii::app()->setting->getValue('SIZE_OF_OUR_CURATORS');
        $this->limit_posts_of_curator = Yii::app()->setting->getValue('SIZE_OF_POST_OF_CURATOR');
    }

    public function actionIndex() {

        $page = 1;
        $group = isset($_GET['group']) ? $_GET['group'] : '';

        if (Yii::app()->request->isAjaxRequest) {
            $page = Yii::app()->user->getState('front_page_more_story');
            $page += 1;
            $model_story = Post::model()->localized($this->lang)->get_story($group, $this->limit, $page)->findAll();
            Yii::app()->user->setState('front_page_more_story', $page);
            if ($model_story != NULL) {
                $this->renderPartial('_story', array('posts' => $model_story), false, true);
            }
        } else {
            Yii::app()->user->setState('front_page_more_story', 1);
            //
//            $model_winner = Post::model()->localized($this->lang)->winner()->sort_by_date()->findAll();
            $model_story = Post::model()->localized($this->lang)->get_story($group, $this->limit, $page)->findAll();

            $this->render('index', array(
//                'model_winner' => $model_winner,
                'model_story' => $model_story,
                'group' => $group,
            ));
        }
    }

    public function actionCurator($id) {
        $page = 1;

        if (Yii::app()->request->isAjaxRequest) {
            $page = Yii::app()->user->getState('front_page_more_story');
            $page += 1;
            $model_story = Post::model()->localized($this->lang)->get_post_by_author((int) $id, $this->limit_posts_of_curator, $page)->findAll();
            Yii::app()->user->setState('front_page_more_story', $page);
            if ($model_story != NULL) {
                $this->renderPartial('_curator_post', array('posts' => $model_story), false, true);
            }
        } else {
            Yii::app()->user->setState('front_page_more_story', 1);
            //
            $model_story = Post::model()->localized($this->lang)->get_post_by_author((int) $id, $this->limit_posts_of_curator, $page)->findAll();
            $model_user = AUsers::model()->findByPk((int) $id);

            $this->render('curator', array(
                'model_user' => $model_user,
                'model_story' => $model_story,
                'id' => $id,
            ));
        }
    }

    public function actionShow($slug) {
        $post_from_slug = $this->loadModelSlug($slug);
        $post = $this->loadModel($post_from_slug->id, true);
        //
        $model_our_picks = PickRelationships::model()->get_our_picks($this->post_type, $this->limit_our_picks)->findAll();
        //
        $this->render('show', array(
            'post' => $post,
            'model_our_picks' => $model_our_picks,
        ));
    }

    public function actionOur_curators() {
        $page = 1;

        if (Yii::app()->request->isAjaxRequest) {
            $page = Yii::app()->user->getState('front_page_more_curators');
            $page += 1;
            $model_user = AUsers::model()->get_curators($this->limit_our_curators, $page)->findAll();
            Yii::app()->user->setState('front_page_more_curators', $page);

            $this->renderPartial('_our_curators_post', array('model_user' => $model_user), false, true);
        } else {
            Yii::app()->user->setState('front_page_more_curators', 1);
            //
            $model_user = AUsers::model()->get_curators($this->limit_our_curators, $page)->findAll();

            $this->render('our_curators', array(
                'model_user' => $model_user,
            ));
        }
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

//    public function actionBk() {
//        
//        $model = Post::model()->localized($this->lang)->get_story(1)->find();
//        
//        $model_our_picks = PickRelationships::model()->get_our_picks($this->post_type, $this->limit_our_picks)->findAll();
//        
//        $this->render('index', array(
//            'lang' => $this->lang,
//            'model' => $model,
//            'model_our_picks' => $model_our_picks,
//        ));
//    }
}
