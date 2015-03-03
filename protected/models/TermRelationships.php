<?php

/**
 * This is the model class for table "term_relationships".
 *
 * The followings are the available columns in table 'term_relationships':
 * @property string $object_id
 * @property string $term_taxonomy_id
 * @property integer $term_order
 */
class TermRelationships extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'term_relationships';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('term_order', 'numerical', 'integerOnly' => true),
            array('object_id, term_taxonomy_id', 'length', 'max' => 20),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('object_id, term_taxonomy_id, term_order', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'termtaxonomy' => array(self::BELONGS_TO, 'TermTaxonomy', 'term_taxonomy_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'object_id' => 'Object',
            'term_taxonomy_id' => 'Term Taxonomy',
            'term_order' => 'Term Order',
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
        $criteria->compare('term_taxonomy_id', $this->term_taxonomy_id, true);
        $criteria->compare('term_order', $this->term_order);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TermRelationships the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function get_relate_terms($post_id, $taxonomy = 'category', $showin = '') {
        
        if ($showin != ''){
            $this->getDbCriteria()->mergeWith(array(
                'alias' => 'relations',
                'join' => 'LEFT JOIN term_taxonomy ON relations.term_taxonomy_id = term_taxonomy.term_taxonomy_id',
                'condition' => "taxonomy = '$taxonomy' AND show_in = '$showin' AND object_id = " . (int) $post_id,
                'order' => 'term_order ASC',
            ));
        } else {
            $this->getDbCriteria()->mergeWith(array(
                'alias' => 'relations',
                'join' => 'LEFT JOIN term_taxonomy ON relations.term_taxonomy_id = term_taxonomy.term_taxonomy_id',
                'condition' => "taxonomy = '$taxonomy' AND object_id = " . (int) $post_id,
                'order' => 'term_order ASC',
            ));
        }
        
        return $this;
    }
    
}
