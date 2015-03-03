<?php

/**
 * This is the model class for table "term_taxonomy".
 *
 * The followings are the available columns in table 'term_taxonomy':
 * @property string $term_taxonomy_id
 * @property string $term_id
 * @property string $taxonomy
 * @property string $show_in
 * @property string $description
 * @property string $parent
 * @property string $count
 */
class TermTaxonomy extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'term_taxonomy';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('term_id, taxonomy', 'required'),
            array('term_taxonomy_id, term_id, parent, count', 'length', 'max' => 20),
            array('taxonomy', 'length', 'max' => 45),
            array('description, show_in', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('term_taxonomy_id, term_id, taxonomy, show_in, description, parent, count', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'terms' => array(self::BELONGS_TO, 'Terms', 'term_id'),
            'parent' => array(self::BELONGS_TO, 'TermTaxonomy', 'term_id'),
            'children' => array(self::HAS_MANY, 'TermTaxonomy', 'parent', 'order' => 'term_id ASC'),
            'termrelationships' => array(self::HAS_MANY, 'TermRelationships', 'term_taxonomy_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'term_taxonomy_id' => 'Term Taxonomy ID',
            'term_id' => 'Term',
            'taxonomy' => 'Taxonomy',
            'description' => 'Description',
            'parent' => 'Parent',
            'count' => 'Count',
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

        $criteria->compare('term_taxonomy_id', $this->term_taxonomy_id, true);
        $criteria->compare('term_id', $this->term_id, true);
        $criteria->compare('taxonomy', $this->taxonomy, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('parent', $this->parent, true);
        $criteria->compare('count', $this->count, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TermTaxonomy the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function scopes() {
        return array(
            'tags' => array(
                'condition' => 'taxonomy = "tag"'
            )
        );
    }

    public function get_all_terms_by($show_in){
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'taxonomy = "category" AND show_in = :show_in',
            'order' => 'term_id ASC',
            'params' => array(':show_in' => $show_in),
        ));
        return $this;
    }


    public function roots($taxonomy = 'category', $show_in) {
        switch ($taxonomy) {
            case 'category':
                $this->getDbCriteria()->mergeWith(array(
                    'condition' => 'taxonomy = :taxonomy AND show_in = :show_in AND parent = 0',
                    'order' => 'term_id ASC',
                    'params' => array(':taxonomy' => $taxonomy, ':show_in' => $show_in),
                ));
                break;
            case 'tag':
                $this->getDbCriteria()->mergeWith(array(
                    'condition' => 'taxonomy = :taxonomy',
                    'order' => 'term_id ASC',
                    'params' => array(':taxonomy' => $taxonomy),
                ));
                break;
        }
        return $this;
    }

    public function by_parent($taxonomy = 'category', $show_in, $p_id) {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'taxonomy = :taxonomy AND show_in = :show_in AND parent = :parent ',
            'order' => 'term_id ASC',
            'params' => array(':taxonomy' => $taxonomy, ':show_in' => $show_in, ':parent' => (int) $p_id),
        ));
        return $this;
    }

    public function has_child($taxonomy = 'category', $p_id) {

        $criteria = new CDbCriteria(array(
            'condition' => "taxonomy = :taxonomy AND parent = 0 AND term_taxonomy_id = :id",
            'params' => array(':taxonomy' => $taxonomy, ':id' => (int) $p_id),
        ));

        $model = TermTaxonomy::model()->find($criteria);
        if ($model != NULL) {
            // check has child or not
            $id = $model->term_taxonomy_id;
            $criteria = new CDbCriteria(array(
                'condition' => "taxonomy = :taxonomy AND parent = :id",
                'params' => array(':taxonomy' => $taxonomy, ':id' => (int) $id),
            ));
            $model = TermTaxonomy::model()->find($criteria);
            if ($model != NULL) {
                // has child
                return TRUE;
            } else {
                // no child
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function delete_tree($p_id) {
        
        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();
        //
        $model = TermTaxonomy::model()->findByPk((int) $p_id);  // get current parent id
        $sql1 = 'DELETE FROM term_relationships WHERE term_taxonomy_id = ' . (int) $p_id;
        $sql2 = 'DELETE FROM term_taxonomy WHERE term_taxonomy_id = ' . (int) $p_id;
        $sql3 = 'UPDATE term_taxonomy SET parent = ' . $model->parent . ' WHERE parent = ' . (int) $p_id;
        $sql4 = "DELETE t, l FROM terms t LEFT JOIN terms_lang l ON t.id = l.term_id WHERE t.id = " . (int) $p_id;
        //
        try{
            // delete term_relationships
            $connection->createCommand($sql1)->execute();
            
            // delete term_taxonomy & update new parent_id
            $connection->createCommand($sql2)->execute();
            $connection->createCommand($sql3)->execute();
            
            // delete terms & terms_lang
            $connection->createCommand($sql4)->execute();
            
            //
            $transaction->commit();
            
        } catch (Exception $e){
            $transaction->rollback();
        }
        
    }

}
