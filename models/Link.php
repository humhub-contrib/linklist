<?php

namespace humhub\modules\linklist\models;

use Yii;

/**
 * This is the model class for table "linklist_link".
 * 
 * @package humhub.modules.linklist.models
 * The followings are the available columns in table 'linklist_link':
 * @property integer $id
 * @property integer $category_id
 * @property string $href
 * @property string $title
 * @property string $description
 * @property integer $sort_order
 */
class Link extends \humhub\modules\content\components\ContentActiveRecord implements \humhub\modules\search\interfaces\Searchable
{

    public $autoAddToWall = true;
    public $wallEntryClass = "humhub\modules\linklist\widgets\WallEntry";

    
    public function beforeSave($insert)
    {
        
        if ($this->sort_order == "") {
            $this->sort_order = 0;
        }
        
        return parent::beforeSave($insert);
    }
    
    public function getContentName()
    {
        return Yii::t('LinklistModule.base', "Link");
    }

    public function getContentDescription()
    {
        return $this->title;
    }

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'linklist_link';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('category_id', 'required'),
            array(['category_id', 'sort_order'], 'integer'),
            array(['href', 'title', 'description'], 'safe'),
            array(['href', 'title'], 'required'),
            array('href', 'url'),
                #array('href', 'DeadLinkValidator', 'type' => 'GET', 'timeout' => 5),
        );
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'category_id' => Yii::t('LinklistModule.models_Link', 'Category'),
            'href' => 'URL',
            'title' => Yii::t('LinklistModule.models_Link', 'Title'),
            'description' => Yii::t('LinklistModule.models_Link', 'Description'),
            'sort_order' => Yii::t('LinklistModule.models_Link', 'Sort Order'),
        );
    }

    /**
     * @inheritdoc
     */
    public function getSearchAttributes()
    {
        return array(
            'title' => $this->title,
            'description' => $this->description,
            'href' => $this->href,
        );
    }

}
