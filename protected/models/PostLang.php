<?php

/**
 * This is the model class for table "post_lang".
 *
 * The followings are the available columns in table 'post_lang':
 * @property string $l_id
 * @property string $post_id
 * @property string $lang_id
 * @property string $l_post_title
 * @property string $l_provided_by
 * @property string $l_post_content
 * @property string $l_post_excerpt
 * @property string $l_quote_author
 * @property string $l_image
 * @property string $l_feature_image
 * @property string $l_award
 * @property string $l_s_award
 * @property string $l_post_name
 * @property string $l_slug
 */
class PostLang extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'post_lang';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('post_id, lang_id, l_post_title, l_post_content, l_post_name', 'required'),
            array('post_id, l_s_award', 'length', 'max' => 20),
            array('lang_id', 'length', 'max' => 6),
            array('l_provided_by, l_image, l_feature_image, l_slug', 'length', 'max' => 255),
            array('l_award', 'length', 'max' => 45),
            array('l_quote_author', 'length', 'max' => 128),
            array('l_post_excerpt', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('l_id, post_id, lang_id, l_post_title, l_provided_by, l_post_content, l_post_excerpt, l_image, l_feature_image, l_award, l_s_award, l_post_name, l_slug', 'safe', 'on' => 'search'),
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
            'post_id' => 'Post',
            'lang_id' => 'Lang',
            'l_post_title' => 'L Post Title',
            'l_provided_by' => 'L Provided By',
            'l_post_content' => 'L Post Content',
            'l_post_excerpt' => 'L Post Excerpt',
            'l_image' => 'L Image',
            'l_feature_image' => 'L Feature Image',
            'l_award' => 'L Award',
            'l_s_award' => 'L S Award',
            'l_post_name' => 'L Post Name',
            'l_slug' => 'L Slug',
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
        $criteria->compare('post_id', $this->post_id, true);
        $criteria->compare('lang_id', $this->lang_id, true);
        $criteria->compare('l_post_title', $this->l_post_title, true);
        $criteria->compare('l_provided_by', $this->l_provided_by, true);
        $criteria->compare('l_post_content', $this->l_post_content, true);
        $criteria->compare('l_post_excerpt', $this->l_post_excerpt, true);
        $criteria->compare('l_image', $this->l_image, true);
        $criteria->compare('l_feature_image', $this->l_feature_image, true);
        $criteria->compare('l_award', $this->l_award, true);
        $criteria->compare('l_s_award', $this->l_s_award, true);
        $criteria->compare('l_post_name', $this->l_post_name, true);
        $criteria->compare('l_slug', $this->l_slug, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PostLang the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
