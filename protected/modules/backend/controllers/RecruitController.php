<?php

class RecruitController extends MyController {
    
    public function accessRules() {
        $module = Yii::app()->getModule('user');
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('export', 'mark_read'),
                'users' => array('@'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView() {
        $id = (int) $_POST['id'];
        $model = $this->loadModel($id);

        $arr['follow'] = $model->post->post_title;
        $arr['first_name'] = $model->first_name;
        $arr['last_name'] = $model->last_name;
        $arr['gender'] = AUsers::itemAlias('Gender', $model->gender);
        $arr['birth_date'] = Common::date_format($model->birth_date, 'Y-m-d');
        $arr['email'] = $model->email;
        $arr['phone'] = $model->phone;
        $arr['address'] = $model->address;
        $arr['state'] = $model->state->stateName;
        $arr['city'] = $model->city->cityName;
        $arr['country'] = $model->country->countryName;
        $arr['school_name'] = $model->school_name;
        $arr['grade_year'] = $model->grade_year;
        $arr['gpa'] = $model->gpa;
        $arr['feeling'] = $model->feeling->title;
        $arr['create_date'] = $model->create_date;
        //
        $arr['status'] = '';
        if ($model->status == 0) {
            $model->status($id);
            $arr['status'] = 'R';
        }
        //
        echo json_encode($arr);
    }
    
    public function actionMark_read() {
        $ids = $_POST['id'];
        $model = new Recruit;
        foreach ($ids as $id) {
            $model->status($id);
        }
        echo 1;
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete() {
        $id = (int) $_POST['id'];
        $this->loadModel($id)->delete();
        echo 1;
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new Recruit;
        $item_count = 0;
        $page_size = 50;
        $pages = NULL;
        
        $post_type = '';
        $conditon = '';
        if (isset($_GET['category']) && $_GET['category'] != '') {
            $post_type = $_GET['category'];
            $conditon = "post_type = '$post_type'";
        }
        
        $criteria = new CDbCriteria(array(
            'condition' => $conditon,
        ));
        
        $item_count = $model->count($criteria);

        // the pagination itself
        $pages = new CPagination($item_count);
        $pages->setPageSize($page_size);

        $criteria = new CDbCriteria(array(
            'condition' => $conditon,
            'order' => 'create_date DESC',
            'limit' => $pages->limit,
            'offset' => $pages->offset,
        ));
        $posts = $model->findAll($criteria);

        $this->render('index', array(
            'posts' => $posts,
            'item_count' => $item_count,
            'page_size' => $page_size,
            'pages' => $pages,
            'post_type' => $post_type,
        ));
    }

    /**
     * export to csv.
     */
    public function actionExport() {

        if (isset($_POST['Recruit'])) {

            $data = array();
            $Criteria = new CDbCriteria();
            $Criteria->condition = ' DATE_FORMAT(create_date,"%Y-%m-%d") BETWEEN "' . $_POST['Recruit']['startDate'] . '" AND "' . $_POST['Recruit']['endDate'] . '"';
            $model = Recruit::model()->findAll($Criteria);
            $i = 0;
            foreach ($model as $row) {
                $data[$i]['no'] = $i;
                $data[$i]['id'] = $row->id;
                $data[$i]['post_title'] = $row->post->post_title;
                $data[$i]['first_name'] = $row->first_name;
                $data[$i]['last_name'] = $row->last_name;
                $data[$i]['gender'] = ($row->gender == -1) ? 'NA' : ($row->gender == 1) ? 'Male' : 'Female';
                $data[$i]['birth_date'] = Common::date_format($row->birth_date, 'Y-m-d');
                $data[$i]['email'] = $row->email;
                $data[$i]['phone'] = $row->phone;
                $data[$i]['address'] = $row->address;
                $data[$i]['state'] = $row->state->stateName;
                $data[$i]['city'] = $row->city->cityName;
                $data[$i]['country'] = $row->country->countryName;
                $data[$i]['zipcode'] = $row->zipcode;
                $data[$i]['school_name'] = $row->school_name;
                $data[$i]['grade_year'] = $row->grade_year;
                $data[$i]['gpa'] = $row->gpa;
                $data[$i]['feeling'] = $row->feeling->title;
                $data[$i]['create_date'] = $row->create_date;
                $i++;
            }

            /* excel xml */
            $fields[0]['no'] = "No.";
            $fields[0]['id'] = "ID";
            $fields[0]['post_title'] = "Post Title";
            $fields[0]['first_name'] = "First Name";
            $fields[0]['last_name'] = "Last Name";
            $fields[0]['gender'] = "Gender";
            $fields[0]['birth_date'] = "Birth Date";
            $fields[0]['email'] = "Email";
            $fields[0]['phone'] = "Phone";
            $fields[0]['address'] = "Address";
            $fields[0]['state'] = "State";
            $fields[0]['city'] = "City";
            $fields[0]['country'] = "Country";
            $fields[0]['zipcode'] = "Zip/Postal";
            $fields[0]['school_name'] = "School Name";
            $fields[0]['grade_year'] = "Grade Year";
            $fields[0]['gpa'] = "GPA";
            $fields[0]['feeling'] = "Feeling";
            $fields[0]['create_date'] = "Create Date";

            $filename = 'Recruit_' . $_POST['Recruit']['startDate'] . '_' . $_POST['Recruit']['endDate'] . '-' . microtime();

            Yii::import('application.extensions.Excel_XML');
            $xls = new Excel_XML('UTF-8', 'UTF-8', date('Y-M'));
            $xls->addArray($fields);
            $xls->addArray($data);
            $xls->generateXML($filename);
            exit;
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Recruit the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Recruit::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Recruit $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'recruit-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}