<?php

namespace humhub\modules\linklist\models;

/**
 * This is the model class for table "linklist_category".
 * 
 * @package humhub.modules.linklist.models
 * The followings are the available columns in table 'linklist_category':
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $sort_order
 */
class Category extends \humhub\modules\content\components\ContentActiveRecord
{

    public $autoAddToWall = false;

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'linklist_category';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('sort_order', 'integer'),
            array(['title', 'description'], 'safe'),
            array('title', 'required'),
        );
    }

    public function getLinks()
    {
        $query = $this->hasMany(Link::className(), ['category_id' => 'id']);
        return $query;
    }

    public function afterDelete()
    {
        foreach ($this->links as $link) {
            $link->delete();
        }
        parent::afterDelete();
    }

    public function getUrl()
    {
        return $this->content->container->createUrl('linklist/linklist');
    }

    public function getContentName()
    {
        return 'Link Category';
    }
    
    public function getContentDescription()
    {
        return $this->title;
    }    

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => Yii::t('LinklistModule.base', 'Title'),
            'description' => Yii::t('LinklistModule.base', 'Description'),
            'sort_order' => Yii::t('LinklistModule.base', 'Sort Order'),
        );
    }

}
