<?php

class AdvertiseController extends MyController {

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

        $arr['company'] = $model->company;
        $arr['first_name'] = $model->first_name;
        $arr['last_name'] = $model->last_name;
        $arr['email'] = $model->email;
        $arr['type'] = Advertise::itemAlias('type', $model->type);
        $interest = AdvertiseRelation::model()->findAllByAttributes(array('ads_id' => $id));
        $t_arr = array();
        foreach ($interest as $value) {
            $t_arr[] = Advertise::itemAlias('interest', $value->interest);
        }
        $arr['interest'] = implode('<br>', $t_arr);
        $arr['description'] = $model->description;
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
        $model = new Advertise;
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
        AdvertiseRelation::model()->deleteAll('ads_id = :id', array(':id' => 2));
        echo 1;
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {


        $model = new Advertise;
        $item_count = 0;
        $page_size = 50;
        $pages = NULL;

        $item_count = $model->count();

        // the pagination itself
        $pages = new CPagination($item_count);
        $pages->setPageSize($page_size);

        $criteria = new CDbCriteria(array(
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
        ));
    }

    /**
     * export to csv.
     */
    public function actionExport() {

        if (isset($_POST['Advertise'])) {

            $data = array();
            $Criteria = new CDbCriteria();
            $Criteria->condition = ' DATE_FORMAT(create_date,"%Y-%m-%d") BETWEEN "' . $_POST['Advertise']['startDate'] . '" AND "' . $_POST['Advertise']['endDate'] . '"';
            $model = Advertise::model()->findAll($Criteria);
            $i = 0;
            foreach ($model as $row) {
                $data[$i]['no'] = $i;
                $data[$i]['id'] = $row->id;
                $data[$i]['company'] = $row->company;
                $data[$i]['first_name'] = $row->first_name;
                $data[$i]['last_name'] = $row->last_name;
                $data[$i]['email'] = $row->email;
                $data[$i]['type'] = Advertise::itemAlias('type', $row->type);
                //
                $interest_str = '';
                $interest = AdvertiseRelation::model()->findAllByAttributes(array('ads_id' => $row->id));
                if ($interest != NULL) {
                    foreach ($interest as $item) {
                        $arr_interest[] = Advertise::itemAlias('interest', $item->interest);
                    }
                    $interest_str = implode(', ', $arr_interest);
                }
                $data[$i]['interest'] = $interest_str;
                $data[$i]['description'] = $row->description;
                $data[$i]['create_date'] = $row->create_date;
                $i++;
            }

            /* excel xml */
            $fields[0]['no'] = "No.";
            $fields[0]['id'] = "ID";
            $fields[0]['company'] = "Post Title";
            $fields[0]['first_name'] = "First Name";
            $fields[0]['last_name'] = "Last Name";
            $fields[0]['email'] = "Email";
            $fields[0]['type'] = "Phone";
            $fields[0]['interest'] = "City";
            $fields[0]['description'] = "Country";
            $fields[0]['create_date'] = "Create Date";

            $filename = 'Advertise_' . $_POST['Advertise']['startDate'] . '_' . $_POST['Advertise']['endDate'] . '-' . microtime();

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
        $model = Advertise::model()->findByPk($id);
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
