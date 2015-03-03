<?php

class ResumeController extends MyStudentController {

    public $lang = 'vi';

    public function init() {
        $this->lang = Yii::app()->language;

        parent::init();
    }

    // BEGIN Extra -------------------------------------------------------------
    public function actionAdd_extra() {

        if (Yii::app()->request->isAjaxRequest) {
            $model = new ResumeExtra;

            $data = json_decode($_POST['data']);

            $model->user_id = Yii::app()->user->id;
            $model->resume_id = $_POST['reid'];
            $model->activity = $data->activity;
            $model->position = $data->position;
            $model->description = $data->description;
            $model->start = $data->start;
            if (!isset($data->uptonow)) {
                // not check
                $model->uptonow = 0;
                $model->end = $data->end;
            } else {
                // checked
                $model->uptonow = 1;
                $model->end = '0000-00-00';
            }

            if ($model->save()) {
                echo $model->id;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }

    public function actionUpdate_extra() {

        if (Yii::app()->request->isAjaxRequest) {
            $id = (int) $_POST['id'];
            $res_id = (int) $_POST['reid'];

            $model = ResumeExtra::model()->findByPk($id);

            $data = json_decode($_POST['data']);

            $model->user_id = Yii::app()->user->id;
            $model->resume_id = $res_id;
            $model->activity = $data->activity;
            $model->position = $data->position;
            $model->description = $data->description;
            $model->start = $data->start;
            if (!isset($data->uptonow)) {
                // not check
                $model->uptonow = 0;
                $model->end = $data->end;
            } else {
                // checked
                $model->uptonow = 1;
                $model->end = '0000-00-00';
            }

            if ($model->save()) {
                echo $model->id;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }

    public function actionDelete_extra() {
        if (Yii::app()->request->isAjaxRequest) {
            $id = (int) $_POST['id'];
            $re_id = (int) $_POST['reid'];
            echo ResumeExtra::model()->delete_item($id, $re_id);
        }
    }

    // END Extra ---------------------------------------------------------------
    // BEGIN Experience --------------------------------------------------------
    public function actionAdd_exper() {

        if (Yii::app()->request->isAjaxRequest) {
            $model = new ResumeExper;

            $data = json_decode($_POST['data']);

            $model->user_id = Yii::app()->user->id;
            $model->resume_id = $_POST['reid'];
            $model->employer = $data->employer;
            $model->position = $data->position;
            $model->industry_id = $data->industry;
            $model->description = $data->description;
            $model->start = $data->start;
            if (!isset($data->uptonow)) {
                // not check
                $model->uptonow = 0;
                $model->end = $data->end;
            } else {
                // checked
                $model->uptonow = 1;
                $model->end = '0000-00-00';
            }

            if ($model->save()) {
                echo $model->id;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }

    public function actionUpdate_exper() {

        if (Yii::app()->request->isAjaxRequest) {
            $id = (int) $_POST['id'];
            $res_id = (int) $_POST['reid'];

            $model = ResumeExper::model()->findByPk($id);

            $data = json_decode($_POST['data']);

            $model->user_id = Yii::app()->user->id;
            $model->resume_id = $res_id;
            $model->employer = $data->employer;
            $model->position = $data->position;
            $model->industry_id = $data->industry;
            $model->description = $data->description;
            $model->start = $data->start;
            if (!isset($data->uptonow)) {
                // not check
                $model->uptonow = 0;
                $model->end = $data->end;
            } else {
                // checked
                $model->uptonow = 1;
                $model->end = '0000-00-00';
            }

            if ($model->save()) {
                echo $model->id;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }

    public function actionDelete_exper() {
        if (Yii::app()->request->isAjaxRequest) {
            $id = (int) $_POST['id'];
            $re_id = (int) $_POST['reid'];
            echo ResumeExper::model()->delete_item($id, $re_id);
        }
    }

    // END Experience ----------------------------------------------------------
    // BEGIN Education ---------------------------------------------------------
    public function actionAdd_educa() {

        if (Yii::app()->request->isAjaxRequest) {
            $model = new ResumeEducation;

            $data = json_decode($_POST['data']);

            $model->user_id = Yii::app()->user->id;
            $model->resume_id = $_POST['reid'];
            $model->school_name = $data->school_name;
            $model->grade_year = $data->grade_year;
            $model->gpa = $data->gpa;
            $model->class_rank = $data->class_rank;

            if ($model->save()) {
                echo $model->id;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }

    public function actionUpdate_educa() {

        if (Yii::app()->request->isAjaxRequest) {
            $id = (int) $_POST['id'];
            $res_id = (int) $_POST['reid'];

            $model = ResumeEducation::model()->findByPk($id);

            $data = json_decode($_POST['data']);

            $model->user_id = Yii::app()->user->id;
            $model->resume_id = $res_id;
            $model->school_name = $data->school_name;
            $model->grade_year = $data->grade_year;
            $model->gpa = $data->gpa;
            $model->class_rank = $data->class_rank;

            if ($model->save()) {
                echo $model->id;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }

    public function actionDelete_educa() {
        if (Yii::app()->request->isAjaxRequest) {
            $id = (int) $_POST['id'];
            $re_id = (int) $_POST['reid'];
            echo ResumeEducation::model()->delete_item($id, $re_id);
        }
    }

    // END Education -----------------------------------------------------------
    // BEGIN Honors & Awards ---------------------------------------------------
    public function actionAdd_honor() {

        if (Yii::app()->request->isAjaxRequest) {
            $model = new ResumeHonorAward;

            $data = json_decode($_POST['data']);

            $model->user_id = Yii::app()->user->id;
            $model->resume_id = $_POST['reid'];
            $model->description = $data->description;

            if ($model->save()) {
                echo $model->id;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }

    public function actionUpdate_honor() {

        if (Yii::app()->request->isAjaxRequest) {
            $id = (int) $_POST['id'];
            $res_id = (int) $_POST['reid'];

            $model = ResumeHonorAward::model()->findByPk($id);

            $data = json_decode($_POST['data']);

            $model->user_id = Yii::app()->user->id;
            $model->resume_id = $res_id;
            $model->description = $data->description;

            if ($model->save()) {
                echo $model->id;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }

    public function actionDelete_honor() {
        if (Yii::app()->request->isAjaxRequest) {
            $id = (int) $_POST['id'];
            $re_id = (int) $_POST['reid'];
            echo ResumeHonorAward::model()->delete_item($id, $re_id);
        }
    }

    // END Honors & Awards -----------------------------------------------------
    // BEGIN Specialities & Skills ---------------------------------------------
    public function actionAdd_skill() {

        if (Yii::app()->request->isAjaxRequest) {
            $model = new ResumeSkill;

            $data = json_decode($_POST['data']);

            $model->user_id = Yii::app()->user->id;
            $model->resume_id = $_POST['reid'];
            $model->description = $data->description;

            if ($model->save()) {
                echo $model->id;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }

    public function actionUpdate_skill() {

        if (Yii::app()->request->isAjaxRequest) {
            $id = (int) $_POST['id'];
            $res_id = (int) $_POST['reid'];

            $model = ResumeSkill::model()->findByPk($id);

            $data = json_decode($_POST['data']);

            $model->user_id = Yii::app()->user->id;
            $model->resume_id = $res_id;
            $model->description = $data->description;

            if ($model->save()) {
                echo $model->id;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }

    public function actionDelete_skill() {
        if (Yii::app()->request->isAjaxRequest) {
            $id = (int) $_POST['id'];
            $re_id = (int) $_POST['reid'];
            echo ResumeSkill::model()->delete_item($id, $re_id);
        }
    }

    // END Specialities & Skills -----------------------------------------------
    // BEGIN Interest ----------------------------------------------------------
    public function actionAdd_interest() {

        if (Yii::app()->request->isAjaxRequest) {
            $model = new ResumeInterest;

            $data = implode(',', $_POST['data']);
            //
            $model->user_id = Yii::app()->user->id;
            $model->resume_id = $_POST['reid'];
            $model->description = $data;
            //
            if ($model->save()) {
                echo $model->id;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }

    public function actionUpdate_interest() {

        if (Yii::app()->request->isAjaxRequest) {
            $id = (int) $_POST['id'];
            $res_id = (int) $_POST['reid'];

            $model = ResumeInterest::model()->findByPk($id);

            $data = implode(',', $_POST['data']);
            //
            $model->user_id = Yii::app()->user->id;
            $model->resume_id = $res_id;
            $model->description = $data;

            if ($model->save()) {
                echo $model->id;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }

    public function actionDelete_interest() {
        if (Yii::app()->request->isAjaxRequest) {
            $id = (int) $_POST['id'];
            $re_id = (int) $_POST['reid'];
            echo ResumeInterest::model()->delete_item($id, $re_id);
        }
    }

    // END Interest ------------------------------------------------------------
    // BEGIN Favorite ----------------------------------------------------------
    public function actionAdd_favorite() {

        if (Yii::app()->request->isAjaxRequest) {
            $model = new ResumeFavorite;

            $data = json_decode($_POST['data']);
            //
            $model->user_id = Yii::app()->user->id;
            $model->resume_id = $_POST['reid'];
            $model->music = $data->music;
            $model->tvshow = $data->tvshow;
            $model->movie = $data->movie;
            $model->quote = $data->quote;
            $model->book = $data->book;
            $model->website = $data->website;
            //
            if ($model->save()) {
                echo $model->id;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }

    public function actionUpdate_favorite() {

        if (Yii::app()->request->isAjaxRequest) {
            $id = (int) $_POST['id'];
            $res_id = (int) $_POST['reid'];

            $model = ResumeFavorite::model()->findByPk($id);

            $data = json_decode($_POST['data']);
            //
            $model->user_id = Yii::app()->user->id;
            $model->resume_id = $res_id;
            $model->music = $data->music;
            $model->tvshow = $data->tvshow;
            $model->movie = $data->movie;
            $model->quote = $data->quote;
            $model->book = $data->book;
            $model->website = $data->website;

            if ($model->save()) {
                echo $model->id;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }

    public function actionDelete_favorite() {
        if (Yii::app()->request->isAjaxRequest) {
            $id = (int) $_POST['id'];
            $re_id = (int) $_POST['reid'];
            echo ResumeFavorite::model()->delete_item($id, $re_id);
        }
    }

    // END Favorite ------------------------------------------------------------

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = Resume::model()->findAll();

        $model_extra = new ResumeExtra;
        $data_extra = $model_extra->model()->findAllByAttributes(array('user_id' => Yii::app()->user->id, 'resume_id' => $model[0]->id));

        $model_exper = new ResumeExper;
        $data_exper = $model_exper->model()->findAllByAttributes(array('user_id' => Yii::app()->user->id, 'resume_id' => $model[1]->id));

        $model_education = new ResumeEducation;
        $data_education = $model_education->model()->findAllByAttributes(array('user_id' => Yii::app()->user->id, 'resume_id' => $model[2]->id));

        $model_honor = new ResumeHonorAward;
        $data_honor = $model_honor->model()->findAllByAttributes(array('user_id' => Yii::app()->user->id, 'resume_id' => $model[3]->id));

        $model_skill = new ResumeSkill;
        $data_skill = $model_skill->model()->findAllByAttributes(array('user_id' => Yii::app()->user->id, 'resume_id' => $model[4]->id));

        $model_interest = new ResumeInterest;
        $data_interest = $model_interest->model()->findAllByAttributes(array('user_id' => Yii::app()->user->id, 'resume_id' => $model[5]->id));

        $model_favorite = new ResumeFavorite;
        $data_favorite = $model_favorite->model()->findAllByAttributes(array('user_id' => Yii::app()->user->id, 'resume_id' => $model[6]->id));

        $this->render('index', array(
            'model' => $model,
            'model_extra' => $model_extra,
            'data_extra' => $data_extra,
            'model_exper' => $model_exper,
            'data_exper' => $data_exper,
            'model_education' => $model_education,
            'data_education' => $data_education,
            'model_honor' => $model_honor,
            'data_honor' => $data_honor,
            'model_skill' => $model_skill,
            'data_skill' => $data_skill,
            'model_interest' => $model_interest,
            'data_interest' => $data_interest,
            'model_favorite' => $model_favorite,
            'data_favorite' => $data_favorite,
        ));
    }

}
