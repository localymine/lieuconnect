<?php

/**
 * This is the model class for table "read_later".
 *
 * The followings are the available columns in table 'read_later':
 * @property string $object_id
 * @property string $user_id
 * @property integer $flag_read
 * @property string $create_date
 */
class ReadLater extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'read_later';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('object_id, user_id', 'required'),
            array('flag_read', 'numerical', 'integerOnly' => true),
            array('object_id, user_id', 'length', 'max' => 20),
            array('create_date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('object_id, user_id, flag_read, create_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'post_id' => array(self::BELONGS_TO, 'Post', 'id'),
            'post_userid' => array(self::BELONGS_TO, 'Post', 'post_author'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'object_id' => 'Object',
            'user_id' => 'User',
            'flag_read' => '0: unread; 1: read',
            'create_date' => 'Create Date',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('object_id', $this->object_id, true);
        $criteria->compare('user_id', $this->post_author, true);
        $criteria->compare('flag_read', $this->flag_read);
        $criteria->compare('create_date', $this->create_date, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ReadLater the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function check_exist($object_id, $user_id) {

        $criteria = new CDbCriteria;
        $criteria->mergeWith(array(
            'condition' => 'object_id = :object_id AND user_id = :user_id',
            'params' => array('object_id' => (int) $object_id, 'user_id' => (int) $user_id),
        ));
        $model = ReadLater::model()->find($criteria);

        return $model;
    }

    public function update_status_read($object_id) {
        
        $this->model()->updateAll(array('flag_read' => 1), 'object_id = ' . (int) $object_id . ' AND user_id = ' . Yii::app()->user->id);
    }
    
    public function delete($object_id){
        
        $this->model()->deleteAll('object_id = :object_id AND user_id = :user_id', array('object_id' => (int) $object_id, 'user_id' => Yii::app()->user->id));
    }
    
    public static function total_unread(){
        $criteria = new CDbCriteria;
        $criteria->mergeWith(array(
            'condition' => 'flag_read = :flag_read AND user_id = :user_id',
            'params' => array('flag_read' => 0, 'user_id' => Yii::app()->user->id),
        ));
        $model = ReadLater::model()->count($criteria);

        return $model;
    }

}