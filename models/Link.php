<?php

/**
 * This is the model class for table "linklist_link".
 *
 * The followings are the available columns in table 'linklist_link':
 * @property integer $id
 * @property integer $category_id
 * @property string $href
 * @property string $title
 * @property string $description
 * @property integer $sort_order
 */
class Link extends HActiveRecordContent
{
	public $autoAddToWall = true;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return LinklistLink the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * Returns the Wall Output
	 */
	public function getWallOut()
	{
		return Yii::app()->getController()->widget('application.modules.linklist.widgets.LinkWallEntryWidget', array('link' => $this), true);
	}
	
	/**
	 * Returns a title/text which identifies this IContent.
	 *
	 * e.g. Post: foo bar 123...
	 *
	 * @return String
	 */
	public function getContentTitle()
	{
		return Yii::t('LinklistModule.base', "Link") . " \"" . Helpers::truncateText($this->title, 25) . "\"";
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'linklist_link';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('category_id', 'required'),
			array('category_id, sort_order', 'numerical', 'integerOnly'=>true),
			array('href, title, description', 'safe'),
			array('href, title', 'required'),
			array('href', 'url'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, category_id, href, title, description, sort_order', 'safe', 'on'=>'search'),
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
				'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'category_id' => 'Category',
			'href' => 'Href',
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
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('href',$this->href,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('sort_order',$this->sort_order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}