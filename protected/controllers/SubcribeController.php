<?php

class SubcribeController extends FrontController {

    public $lang = 'vi';

    public function init() {
        $this->lang = Yii::app()->language;
    }

    public function actionExists() {
        $result = NULL;
        $email = $_POST['email'];

        if ($email != '') {

            $result = SubcribeMail::model()->existsEmail($email)->find();
            if ($result != NULL) {
                echo 'false'; // false -> exist Email
            } else {

                echo 'true'; // true -> free to use this email
            }
        } else {
            echo 'false';
        }
    }

    public function actionRegist() {

        $model = new SubcribeMail;
        if (isset($_POST)) {

            $holder['email'] = $_POST['email'];
            $model->attributes = $holder;

            if ($model->validate()) {
                if ($model->save()) {

                    $mail = new YiiMailer;

                    $sender = Yii::app()->params['sender'];
                    $mail->setFrom(Yii::app()->params['from'], $sender);
                    $mail->setTo($holder['email']);
                    $mail->setSubject(Common::t('Frist NewsLetter', 'mail'));
                    $mail->setView('newsletter');
                    //
                    $param = http_build_query(array(
                        'email' => $model->email,
                        'cp' => $model->compare_key . md5(date('Y:m:d H:i:s')) . sha1(date('Y:m:d H:i:s'))
                    ));
                    $data['unsubcribe_link'] = Yii::app()->params['siteUrl'] . '/subcribe/stop?' . $param;
                    $mail->setData($data);
                    $mail->send();

                    echo 1;
                    Yii::app()->end();
                }
            }
        } else {
            echo 0;
            Yii::app()->end();
        }
    }

    public function actionStop() {

        if (isset($_REQUEST) && $_REQUEST != NULL) {
            if (isset($_REQUEST['cp']) && $_REQUEST['cp'] != '') {
                $compare_key = substr($_REQUEST['cp'], 0, 32);
                
                $result = SubcribeMail::model()->check_compare_key($compare_key)->find();
                if ($result != NULL){
                    // exist compare key
                    SubcribeMail::model()->unsubcribe($compare_key);
                    $this->render('stop');
                    Yii::app()->end();
                } else {
                    // invalid compare key
                    $this->render('invalid');
                    Yii::app()->end();
                }
            }
        }
        $this->render('invalid');
    }

    // Uncomment the following methods and override them if needed
    /*
      public function filters()
      {
      // return the filter configuration for this controller, e.g.:
      return array(
      'inlineFilterName',
      array(
      'class'=>'path.to.FilterClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }

      public function actions()
      {
      // return external action classes, e.g.:
      return array(
      'action1'=>'path.to.ActionClass',
      'action2'=>array(
      'class'=>'path.to.AnotherActionClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }
     */
}
