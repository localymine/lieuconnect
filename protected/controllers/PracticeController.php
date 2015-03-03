<?php

class PracticeController extends MyStudentController {

    public $lang = 'vi';
    public $limit = 25;
    
    public function init() {
        $this->lang = Yii::app()->language;

        $this->limit = 25;

        parent::init();
    }
    
    public function actionIndex() {

        $page = 1;

        if (Yii::app()->request->isAjaxRequest) {
            $page = Yii::app()->user->getState('front_practice');
            $page += 1;
            $result = PracticeTest::model()->localized($this->lang)->get_list($this->limit, $page)->findAll();
            Yii::app()->user->setState('front_practice', $page);
            if ($result != NULL) {
                $this->renderPartial('_entry', array('result' => $result), false, true);
            }
        } else {
            Yii::app()->user->setState('front_practice', 1);
            $result = PracticeTest::model()->localized($this->lang)->get_list($this->limit, $page)->findAll();
            $this->render('index', array(
                'result' => $result
            ));
        }
    }

}
