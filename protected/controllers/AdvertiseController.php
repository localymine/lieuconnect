<?php

class AdvertiseController extends FrontController {

    public $lang = 'vi';

    public function init() {
        $this->lang = Yii::app()->language;
    }

    public function actionIndex() {

        $model = new Advertise;
        $model_ads_relate = new AdvertiseRelation;

        if (isset($_POST['Advertise'])) {

            $model->attributes = $_POST['Advertise'];

            if ($model->validate()) {
                if ($model->save()) {
                    $ads_id = $model->id;
                    if (isset($_POST['AdvertiseRelation'])){
                        $f_interest = $_POST['AdvertiseRelation']['f_interest'];
                        foreach($f_interest as $value) {
                            $model_ads_relate->isNewRecord = true;
                            $model_ads_relate->ads_id = $ads_id;
                            $model_ads_relate->interest = $value;
                            $model_ads_relate->save();
                        }
                    }
                    //
                    Yii::app()->user->setState('ads_id', $ads_id);
                    $seed = md5($ads_id);
                    $this->redirect(Yii::app()->createUrl('advertise/thankyou', array('authDate' => date('Ymd'), 'key' => $seed)));
                    Yii::app()->end();
                }
            }
        }

        $this->render('index', array(
            'model' => $model,
            'model_ads_relate' => $model_ads_relate,
        ));
    }

    public function actionThankyou() {

        $ads_id = Yii::app()->user->getState('ads_id');
        if (isset($_REQUEST['key']) && $_REQUEST['key'] == md5($ads_id)) {
            Yii::app()->user->setState('ads_id', NULL);
            $this->render('thankyou');
        } else {
            $this->redirect(Yii::app()->createUrl('advertise'));
        }
    }

}
