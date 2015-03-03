<?php

class HomeController extends FrontController {

    public $defaultAction = 'index';
    public $lang = 'vi';
    public $limit = 5;
    public $layout = '//layouts/home';

    public function init() {
        $this->lang = Yii::app()->language;
    }
    
    public function actionIndex(){
        
        $model = Slide::model()->localized($this->lang)->get_slide($this->limit)->findAll();
        
        $this->render('home-slider', array(
            'model' => $model,
        ));
    }

//    public function actionBk() {
        
//        $limit = 3;
//        $page = 1;
//
//        if (Yii::app()->request->isAjaxRequest) {
//            $page = Yii::app()->user->getState('front_page_more_story');
//            $page += 1;
//            $model_story = Post::model()->localized($this->lang)->get_story($limit, $page)->findAll();
//            Yii::app()->user->setState('front_page_more_story', $page);
//            if ($model_story != NULL) {
//                $this->renderPartial('_lilelyconnnect_story', array('posts' => $model_story), false);
//            } else {
//                echo 0;
//            }
//        } else {
//            Yii::app()->user->setState('front_page_more_story', 1);
//            //
//            $model_winner = Post::model()->localized($this->lang)->winner()->sort_by_date()->findAll();
//            $model_story = Post::model()->localized($this->lang)->get_story($limit, $page)->findAll();
//
//            $this->render('index', array(
//                'model_winner' => $model_winner,
//                'model_story' => $model_story,
//            ));
//        }
//    }

}
