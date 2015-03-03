<?php

class BoxController extends MyStudentController {

    public $lang = 'vi';
    public $limit = 25;

    public function init() {
        $this->lang = Yii::app()->language;

        $this->limit = 25;

        parent::init();
    }

    public function actionIndex() {

        $post_type = NULL;
        $page = 1;
        if (Yii::app()->request->isAjaxRequest) {
            $page = Yii::app()->user->getState('front_inbox_readlater');
            $page += 1;
            $result = Post::model()->localized($this->lang)->get_read_later($post_type, $this->limit, $page)->findAll();
            Yii::app()->user->setState('front_inbox_readlater', $page);
            if ($result != NULL) {
                $this->renderPartial('_entry_all', array('result' => $result), false, true);
            }
        } else {
            Yii::app()->user->setState('front_inbox_readlater', 1);
            $result = Post::model()->localized($this->lang)->get_read_later($post_type, $this->limit, $page)->findAll();
            $this->render('index', array(
                'result' => $result,
            ));
        }
    }

    public function actionReadLater() {
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

    public function actionDeleteAll() {
        if (Yii::app()->request->isAjaxRequest) {
            if (Yii::app()->user->id != 0) {
                
                $model = new ReadLater;
                $data = json_decode($_POST['data']);
                
                foreach ($data as $post_id) {
                    if ($model->check_exist($post_id, Yii::app()->user->id) != NULL) {
                        $model->delete($post_id);
                    }
                }
                echo 1;
            } else {
                echo -1;
            }
        } else {
            echo -1;
        }
    }
    
    public function actionMark_as_read() {
        if (Yii::app()->request->isAjaxRequest) {
            if (Yii::app()->user->id != 0) {
                
                $model = new ReadLater;
                $data = json_decode($_POST['data']);
                
                foreach ($data as $post_id) {
                    $model->update_status_read($post_id);
                }
                echo 1;
            } else {
                echo -1;
            }
        } else {
            echo -1;
        }
    }
    
    public function actionDelete() {
        if (Yii::app()->user->id != 0) {

            $model = new ReadLater;
            $object_id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

            if ($model->check_exist($object_id, Yii::app()->user->id) != NULL) {
                $model->delete($object_id);
                echo 1;
            } else {
                echo 0;
            }
        } else {
            echo -1;
        }
    }

    public function actionScholarship() {

        $post_type = 'scholarship';
        $page = 1;
        if (Yii::app()->request->isAjaxRequest) {
            $page = Yii::app()->user->getState('front_inbox_scholarship');
            $page += 1;
            $result = Post::model()->localized($this->lang)->get_read_later($post_type, $this->limit, $page)->findAll();
            Yii::app()->user->setState('front_inbox_scholarship', $page);
            if ($result != NULL) {
                $this->renderPartial('_entry_one', array('result' => $result), false, true);
            }
        } else {
            Yii::app()->user->setState('front_inbox_scholarship', 1);
            $result = Post::model()->localized($this->lang)->get_read_later($post_type, $this->limit, $page)->findAll();
            $this->render('scholarship', array(
                'result' => $result,
            ));
        }
    }

    public function actionInternship() {

        $post_type = 'internship';
        $page = 1;
        if (Yii::app()->request->isAjaxRequest) {
            $page = Yii::app()->user->getState('front_inbox_internship');
            $page += 1;
            $result = Post::model()->localized($this->lang)->get_read_later($post_type, $this->limit, $page)->findAll();
            Yii::app()->user->setState('front_inbox_internship', $page);
            if ($result != NULL) {
                $this->renderPartial('_entry_one', array('result' => $result), false, true);
            }
        } else {
            Yii::app()->user->setState('front_inbox_internship', 1);
            $result = Post::model()->localized($this->lang)->get_read_later($post_type, $this->limit, $page)->findAll();
            $this->render('internship', array(
                'result' => $result,
            ));
        }
    }

    public function actionCollege() {

        $post_type = 'college';
        $page = 1;
        if (Yii::app()->request->isAjaxRequest) {
            $page = Yii::app()->user->getState('front_inbox_college');
            $page += 1;
            $result = Post::model()->localized($this->lang)->get_read_later($post_type, $this->limit, $page)->findAll();
            Yii::app()->user->setState('front_inbox_college', $page);
            if ($result != NULL) {
                $this->renderPartial('_entry_one', array('result' => $result), false, true);
            }
        } else {
            Yii::app()->user->setState('front_inbox_college', 1);
            $result = Post::model()->localized($this->lang)->get_read_later($post_type, $this->limit, $page)->findAll();
            $this->render('college', array(
                'result' => $result,
            ));
        }
    }

    public function actionRead($id) {

        $this->layout = '//layouts/column1';

        $post = Post::model()->localized($this->lang)->read_post((int) $id)->find();
        if ($post->read_later[0]->flag_read == 0) {
            ReadLater::model()->update_status_read((int) $id);
        }
        //
        $this->render('show', array(
            'post' => $post,
        ));
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
