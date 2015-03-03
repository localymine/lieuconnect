<?php

class DefaultController extends Controller {

    public function actionIndex() {

        Yii::app()->request->redirect(Yii::app()->createUrl('user/profile'));

//        $this->render('index');
    }

}
