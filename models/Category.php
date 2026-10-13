<?php

namespace humhub\modules\linklist\models;

use humhub\modules\content\components\ContentActiveRecord;
use Yii;

/**
 * This is the model class for table "linklist_category".
 *
 * @package humhub.modules.linklist.models
 * The followings are the available columns in table 'linklist_category':
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $sort_order
 *
 * @property-read Link[] $links
 */
class Category extends ContentActiveRecord
{
    /**
     * @inheritdoc
     */
    public $streamChannel = null;

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
        return [
            ['sort_order', 'integer'],
            [['title', 'description'], 'safe'],
            ['title', 'required'],
        ];
    }

    public function getLinks()
    {
        $query = $this->hasMany(Link::class, ['category_id' => 'id']);
        return $query;
    }

    /**
     * @inheritdoc
     */
    public function afterSoftDelete()
    {
        // Since HumHub 1.19 delete() is a soft delete and afterDelete() is no longer triggered
        foreach ($this->links as $link) {
            if (!$link->content->getStateService()->isDeleted()) {
                $link->delete();
            }
        }
        parent::afterSoftDelete();
    }

    public function afterDelete()
    {
        foreach ($this->links as $link) {
            $link->hardDelete();
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
        return [
            'id' => 'ID',
            'title' => Yii::t('LinklistModule.base', 'Title'),
            'description' => Yii::t('LinklistModule.base', 'Description'),
            'sort_order' => Yii::t('LinklistModule.base', 'Sort Order'),
        ];
    }

}
