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
class Link extends \humhub\modules\content\components\ContentActiveRecord
{

    public $autoAddToWall = true;

    /**
     * Returns the Wall Output
     */
    public function getWallOut()
    {
        return \humhub\modules\linklist\widgets\WallEntry::widget(['link' => $this]);
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
            'category_id' => 'Category',
            'href' => 'URL',
            'title' => 'Title',
            'description' => 'Description',
            'sort_order' => 'Sort Order',
        );
    }

}
