<?php

/**
 * This is the model class for table "advertise".
 *
 * The followings are the available columns in table 'advertise':
 * @property string $id
 * @property string $company
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property integer $type
 * @property string $description
 * @property string $create_date
 */
class Advertise extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'advertise';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('company, first_name, last_name, email', 'length', 'max' => 256),
            array('company, first_name, last_name, email, type', 'required'),
            array('email', 'email'),
            array('description, create_date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, company, first_name, last_name, email, type, description, create_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'ads_relate' => array(self::HAS_MANY, 'AdvertiseRelation', 'id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'company' => Common::t('Company', 'post'),
            'first_name' => Common::t('First Name', 'post'),
            'last_name' => Common::t('Last Name', 'post'),
            'email' => Common::t('Email', 'post'),
            'type' => Common::t('Organization Type', 'post'),
            'description' => Common::t('Description', 'post'),
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('company', $this->company, true);
        $criteria->compare('first_name', $this->first_name, true);
        $criteria->compare('last_name', $this->last_name, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('type', $this->type);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('create_date', $this->create_date, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Advertise the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function beforeSave() {
    
    	$this->company = Common::clean_text($this->company);
	$this->first_name = Common::clean_text($this->first_name);
	$this->last_name = Common::clean_text($this->last_name);
	$this->description = Common::clean_text($this->description);

        if ($this->isNewRecord) {
            $this->create_date = new CDbExpression('NOW()');
        }

        return TRUE;
    }

    public static function itemAlias($type, $code = NULL) {
        $_items = array(
            // Organization Type
            'type' => array(
                'agency' => Common::t('Agency'),
                'for-profit' => Common::t('For-Profit'),
                'non-profit' => Common::t('Non-Profit'),
            ),
            // interested
            'interest' => array(
                'learn-more' => Common::t('Learning more about advertising options '),
                'brand-effort' => Common::t('Branding Efforts'),
                'content-distribution' => Common::t('Content Distribution'),
                'issue-product' => Common::t('Issue/Product Awareness'),
            ),
        );
        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
        else
            return isset($_items[$type]) ? $_items[$type] : false;
    }
    
    public function status($id){
        $sql = " UPDATE advertise SET status = 1 WHERE id = '" . (int) $id . "'";
        Yii::app()->db->createCommand($sql)->execute();

        return Post::model()->findByPk((int) $id);
    }
}
