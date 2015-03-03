<?php

class WinnerController extends FrontController {

    public $lang = 'vi';
    public $post_type = 'winner';
    public $limit_our_picks = 12;

    public function init() {
        $this->lang = Yii::app()->language;

        $this->limit_our_picks = Yii::app()->setting->getValue('SIZE_OF_OUR_PICKS_SCHOLARSHIP');
    }

    public function actionIndex() {
        $this->render('index');
    }

    public function actionShow($slug) {
        $post_from_slug = $this->loadModelSlug($slug);
        $post = $this->loadModel($post_from_slug->id, true);
        //
        $model_our_picks = PickRelationships::model()->get_our_picks('scholarship', $this->limit_our_picks)->findAll();
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
