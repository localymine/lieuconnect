<?php

/**
 * This is the model class for table "subcribe_mail".
 *
 * The followings are the available columns in table 'subcribe_mail':
 * @property string $id
 * @property string $email
 * @property string $compare_key
 * @property string $create_date
 * @property string $modified_date
 * @property integer $status
 */
class SubcribeMail extends CActiveRecord {

    const STATUS_NOACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const SECRET = 'O|d6}PIoae)2K{4p=FV}uGG-8V~h]p^{=EGgJ0S->SG8>MmKg>MM|smiB#|l5SUT';

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'subcribe_mail';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('email', 'required'),
            array('status', 'numerical', 'integerOnly' => true),
            array('email', 'length', 'max' => 256),
            array('email', 'checkExistsEmail'),
            array('compare_key', 'length', 'max' => 512),
            array('create_date, modified_date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, email, compare_key, create_date, modified_date, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'email' => 'Email',
            'compare_key' => 'Compare Key',
            'create_date' => 'Create Date',
            'modified_date' => 'Modified Date',
            'status' => '0: deleted; 1: active',
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('compare_key', $this->compare_key, true);
        $criteria->compare('create_date', $this->create_date, true);
        $criteria->compare('modified_date', $this->modified_date, true);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return SubcribeMail the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function beforeSave() {

        if ($this->isNewRecord) {
            $this->create_date = $this->modified_date = new CDbExpression('NOW()');
        } else {
            $this->modified_date = new CDbExpression('NOW()');
        }

        $this->email = strtolower(trim($this->email));


        $this->compare_key = md5($this->email . self::SECRET);

        return parent::beforeSave();
    }

    public function existsEmail($email) {

        $email = strtolower(trim($email));

        $this->getDbCriteria()->mergeWith(array(
            'condition' => "email = :email AND status = :status",
            'order' => 'create_date DESC',
            'limit' => 1,
            'params' => array('email' => $email, 'status' => self::STATUS_ACTIVE)
        ));

        return $this;
    }

    public function checkExistsEmail() {

        $email = strtolower(trim($this->email));

        if ($email != '') {

            $result = SubcribeMail::model()->existsEmail($email)->find();
            if ($result != NULL) {
                $this->addError('email', Common::t('Email exists'));
                return false; // exist Email
            }
            return true;
        }
        return false;
    }

    public function unsubcribe($compare_key) {
        $connection = Yii::app()->db;

        $sql = " UPDATE `subcribe_mail` "
                . " SET STATUS = " . self::STATUS_NOACTIVE
                . " WHERE `compare_key` = '$compare_key' "
                . " ORDER BY create_date DESC "
                . " LIMIT 1 ";

        $connection->createCommand($sql)->execute();
    }

    public function check_compare_key($compare_key) {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => "compare_key = :compare_key AND status = :status",
            'order' => 'create_date DESC',
            'limit' => 1,
            'params' => array('compare_key' => $compare_key, 'status' => self::STATUS_ACTIVE)
        ));

        return $this;
    }

}
