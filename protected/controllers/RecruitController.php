<?php

class RecruitController extends MyStudentController {

    public $limit_apply_in_day = 5;

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionApply() {

        if (Yii::app()->request->isAjaxRequest) {
            $model = new Recruit;

            $count = $model->count_apply_in_day()->count();

            if ($count <= $this->limit_apply_in_day) {
                
                $data = json_decode($_POST['data']);
                $model->attributes = $data;

                $model->post_id = $data->post_id;
                $post = Post::model()->get_post_type($data->post_id)->findAll();
                $model->post_type = $post[0]->post_type;
                $model->first_name = $data->firstname;
                $model->last_name = $data->lastname;
                $model->address = $data->address;
                $model->country_id = $data->country;
                $model->state_id = $data->state;
                $model->city_id = $data->city;
                $model->zipcode = $data->zipcode;
                $model->email = $data->email;
                $model->phone = $data->phone;
                $model->gender = $data->gender;
                $model->birth_date = $data->birth_date;
                $model->grade_year = $data->grade_year;
                $model->school_name = $data->school_name;
                $model->gpa = $data->gpa;
                $model->feeling_id = $data->feeling;

                if ($model->save()) {
                    echo 1;
                } else {
                    echo 0;
                }
            } else {
                echo -1;
            }
        } else {
            echo 0;
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Recruit;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Recruit'])) {
            $model->attributes = $_POST['Recruit'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

}
