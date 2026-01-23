<?php

namespace humhub\modules\linklist\models;

use Yii;

/**
 * This is the model class for table "linklist_link".
 *
 * @package humhub.modules.linklist.models
 * The followings are the available columns in table 'linklist_link':
 * @property int $id
 * @property int $category_id
 * @property string $href
 * @property string $title
 * @property string $description
 * @property int $sort_order
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
        return [
            ['category_id', 'required'],
            [['category_id', 'sort_order'], 'integer'],
            [['href', 'title', 'description'], 'safe'],
            [['href', 'title'], 'required'],
            ['href', 'url'],
        ];
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
        return [
            'id' => 'ID',
            'category_id' => Yii::t('LinklistModule.base', 'Category'),
            'href' => 'URL',
            'title' => Yii::t('LinklistModule.base', 'Title'),
            'description' => Yii::t('LinklistModule.base', 'Description'),
            'sort_order' => Yii::t('LinklistModule.base', 'Sort Order'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSearchAttributes()
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'href' => $this->href,
        ];
    }

}
