<?php

/**
 * This is the model class for table "linklist_category".
 *
 * The followings are the available columns in table 'linklist_category':
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $sort_order
 */
class Category extends HActiveRecordContent
{
	public $autoAddToWall = false;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return LinklistCategory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'linklist_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sort_order', 'numerical', 'integerOnly'=>true),
			array('title, description', 'safe'),
			array('title', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, description, sort_order', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'links' => array(self::HAS_MANY, 'Link', 'category_id'),
		);
	}


	public function afterDelete()
	{
		foreach($this->links as $link) {
			$link->delete();
		}
		parent::afterDelete();
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'description' => 'Description',
			'sort_order' => 'Sort Order',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('sort_order',$this->sort_order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}