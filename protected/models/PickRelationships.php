<?php

/**
 * This is the model class for table "pick_relationships".
 *
 * The followings are the available columns in table 'pick_relationships':
 * @property string $object_id
 * @property integer $post_author
 * @property string $post_type
 * @property integer $sorting
 */
class PickRelationships extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'pick_relationships';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('object_id, post_author, post_type, sorting', 'required'),
            array('post_author, sorting', 'numerical', 'integerOnly' => true),
            array('object_id', 'length', 'max' => 20),
            array('post_type', 'length', 'max' => 45),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('object_id, post_author, post_type, sorting', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'post' => array(self::BELONGS_TO, 'Post', 'object_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'object_id' => 'Object',
            'post_author' => 'Post Author',
            'post_type' => 'Post Type',
            'sorting' => 'Sorting',
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
        $criteria->compare('post_author', $this->post_author);
        $criteria->compare('post_type', $this->post_type, true);
        $criteria->compare('sorting', $this->sorting);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PickRelationships the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function get_relate_pick($post_type = '') {
        $this->getDbCriteria()->mergeWith(array(
            'alias' => 'relations',
            'join' => 'LEFT JOIN post ON relations.object_id = post.id',
            'condition' => "relations.post_type = '$post_type'",
            'order' => 'sorting ASC',
        ));
        return $this;
    }
    
    public function get_our_picks($post_type = '', $limit = 24){
        $this->getDbCriteria()->mergeWith(array(
            'alias' => 'relations',
            'join' => 'LEFT JOIN post ON relations.object_id = post.id',
            'condition' => "relations.post_type = '$post_type'",
            'limit' => $limit,
            'order' => 'sorting ASC',
        ));
        return $this;
    }

    public function delete_add_picks($post_type = '', $pick_ids = NULL) {

        $result = 0;
        if ($post_type != '' && $pick_ids != NULL) {
            
            $connection = Yii::app()->db;
            $transaction = $connection->beginTransaction();

            $sql1 = "DELETE FROM pick_relationships WHERE post_type = '$post_type'";
            $sql2 = "INSERT INTO pick_relationships (object_id, post_type, sorting) VALUES ";
            $sort = 0;
            foreach ($pick_ids as $id){
                $sql2 .= "(" . (int) $id . ", '$post_type', " . $sort++ . "),";
            }
            $sql2 = mb_substr($sql2, 0, -1);

            try {

                $connection->createCommand($sql1)->execute();
                $connection->createCommand($sql2)->execute();

                $transaction->commit();
                $result = 1;
            } catch (Exception $e) {
                $transaction->rollback();
                $result = 0;
            }
        }
        return $result;
    }

}
