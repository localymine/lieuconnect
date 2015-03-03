<?php

/**
 * This is the model class for table "slide_lang".
 *
 * The followings are the available columns in table 'slide_lang':
 * @property string $l_id
 * @property string $slide_id
 * @property string $l_title
 * @property string $l_description
 * @property string $l_image
 */
class SlideLang extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'slide_lang';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('slide_id, l_image', 'required'),
            array('slide_id', 'length', 'max' => 20),
            array('l_title, l_image', 'length', 'max' => 256),
            array('l_description', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('l_id, slide_id, l_title, l_description, l_image', 'safe', 'on' => 'search'),
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
            'l_id' => 'L',
            'slide_id' => 'Slide',
            'l_title' => 'L Title',
            'l_description' => 'L Description',
            'l_image' => 'L Image',
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

        $criteria->compare('l_id', $this->l_id, true);
        $criteria->compare('slide_id', $this->slide_id, true);
        $criteria->compare('l_title', $this->l_title, true);
        $criteria->compare('l_description', $this->l_description, true);
        $criteria->compare('l_image', $this->l_image, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return SlideLang the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
