<?php

/**
 * This is the model class for table "terms_lang".
 *
 * The followings are the available columns in table 'terms_lang':
 * @property string $l_id
 * @property string $term_id
 * @property string $lang_id
 * @property string $l_name
 */
class TermsLang extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'terms_lang';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('term_id, lang_id', 'required'),
            array('term_id', 'length', 'max' => 20),
            array('lang_id', 'length', 'max' => 6),
            array('l_name', 'length', 'max' => 200),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('l_id, term_id, lang_id, l_name', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
//            'terms' => array(self::HAS_ONE, 'TermsLang', 'term_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'l_id' => 'L',
            'term_id' => 'Term',
            'lang_id' => 'Lang',
            'l_name' => 'L Name',
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
        $criteria->compare('term_id', $this->term_id, true);
        $criteria->compare('lang_id', $this->lang_id, true);
        $criteria->compare('l_name', $this->l_name, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TermsLang the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
