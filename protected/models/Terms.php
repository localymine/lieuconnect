<?php

/**
 * This is the model class for table "terms".
 *
 * The followings are the available columns in table 'terms':
 * @property string $id
 * @property string $name
 * @property string $slug
 */
class Terms extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'terms';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('name, slug', 'length', 'max' => 200),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, slug', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'termtaxonomy' => array(self::HAS_MANY, 'TermTaxonomy', 'term_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
//            'name' => Yii::t('terms', 'Category name'),
            'name' => 'Name',
            'slug' => 'Slug',
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('slug', $this->slug, true);

        return new CActiveDataProvider($this, array(
//            'criteria' => $criteria,
            'criteria' => $this->ml->modifySearchCriteria($criteria),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Terms the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function behaviors() {
        return array(
            'ml' => array(
                'class' => 'application.models.behaviors.MultilingualBehavior',
                'langClassName' => 'TermsLang',
                'langTableName' => 'terms_lang',
                'langForeignKey' => 'term_id',
                'langField' => 'lang_id',
                'localizedAttributes' => array('name', 'slug'), //attributes of the model to be translated
                'localizedPrefix' => 'l_',
                'languages' => Yii::app()->params['translatedLanguages'], // array of your translated languages. Example : array('fr' => 'Français', 'en' => 'English')
                'defaultLanguage' => Yii::app()->params['defaultLanguage'], //your main language. Example : 'fr'
                'createScenario' => 'insert',
                'localizedRelation' => 'i18nPost',
                'multilangRelation' => 'multilangPost',
                'forceOverwrite' => false,
                'forceDelete' => true,
                'dynamicLangClass' => true, //Set to true if you don't want to create a 'PostLang.php' in your models folder
            ),
//            'SlugBehavior' => array(
//                'class' => 'application.models.behaviors.SlugBehavior',
//                'slug_col' => 'slug',
//                'title_col' => 'name',
//                'max_slug_chars' => 125,
//                'overwrite' => false
//            ),
            'sluggable' => array(
                'class' => 'ext.behaviors.SluggableBehavior.SluggableBehavior',
                'columns' => array('name'),
                'unique' => true,
                'update' => false,
                'useInflector' => true,
            ),
        );
    }

    public function defaultScope() {
        return $this->ml->localizedCriteria();
    }

    static public function create_basic_tag($tag_name) {

        $slug = new Slug;
        $model = new Terms;
        $model_term_taxonomy = new TermTaxonomy;

        foreach (Yii::app()->params['translatedLanguages'] as $l => $lang) {
            if ($l === Yii::app()->params['defaultLanguage']) {
                $model->slug = Slug::create($tag_name);
                $model->name = $tag_name;
                
            } else {
                $l_slug = 'slug_' . $l;
                $l_name = 'name_' . $l;
                $model->{$l_name} = $tag_name;
                $model->{$l_slug} = Slug::create($tag_name)  . '-' . $l;
            }
        }
        
        $model->save();
        
        $model_term_taxonomy->term_id = $model->id;
        $model_term_taxonomy->taxonomy = 'tag';
        
        $model_term_taxonomy->save();
        
        return $model->id;
    }

}
