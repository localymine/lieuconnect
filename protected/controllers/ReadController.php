<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReadController
 *
 * @author khangld
 */
class ReadController extends FrontController {

    public function actionIndex() {
        if (Yii::app()->user->id != 0) {
            $model = new ReadLater;

            $model->object_id = $_POST['id'];
            $model->user_id = Yii::app()->user->id;
            $model->create_date = Common::get_current_date();

            if ($model->check_exist($model->object_id, $model->user_id) == NULL) {
                if ($model->save()) {
                    echo 1;
                } else {
                    echo 0;
                }
            } else {
                echo 0;
            }
        } else {
            echo -1;
        }
    }

}
