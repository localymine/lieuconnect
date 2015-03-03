<?php

/**
 * This is the model class for table "advertise_relation".
 *
 * The followings are the available columns in table 'advertise_relation':
 * @property string $ads_id
 * @property string $interest
 */
class AdvertiseRelation extends CActiveRecord {
    
    public $f_interest = ''; // form

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'advertise_relation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('ads_id, interest', 'required'),
            array('ads_id', 'length', 'max' => 20),
            array('interest', 'length', 'max' => 128),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('ads_id, interest', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'advertise' => array(self::BELONGS_TO, 'Advertise', 'ads_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'ads_id' => 'Ads',
            'interest' => 'Interest',
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

        $criteria->compare('ads_id', $this->ads_id, true);
        $criteria->compare('interest', $this->interest, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AdvertiseRelation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
