<?php

namespace humhub\modules\linklist;

use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\components\ContentContainerModule;
use humhub\modules\linklist\models\Category;
use humhub\modules\linklist\models\Link;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use Yii;

class Module extends ContentContainerModule
{
    /**
     * @inheritdoc
     */
    public function getContentContainerTypes()
    {
        return [
            User::class,
            Space::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getContentClasses(): array
    {
        return [Link::class];
    }

    /**
     * @inheritdoc
     */
    public function getContentContainerConfigUrl(ContentContainerActiveRecord $container)
    {
        return $container->createUrl('/linklist/linklist/config');
    }

    /**
     * @inheritdoc
     */
    public function disable()
    {
        foreach (Category::find()->all() as $category) {
            $category->hardDelete();
        }
        foreach (Link::find()->all() as $content) {
            $content->hardDelete();
        }
        parent::disable();
    }

    /**
     * @inheritdoc
     */
    public function enableContentContainer(ContentContainerActiveRecord $container)
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('linklist');

        $module->settings->contentContainer($container)->set('enableDeadLinkValidation', 0);
        $module->settings->contentContainer($container)->set('enableWidget', 0);

        parent::enableContentContainer($container);
    }

    /**
     * @inheritdoc
     */
    public function disableContentContainer(ContentContainerActiveRecord $container)
    {
        parent::disableContentContainer($container);

        foreach (Category::find()->contentContainer($container)->all() as $content) {
            $content->hardDelete();
        }
        foreach (Link::find()->contentContainer($container)->all() as $content) {
            $content->hardDelete();
        }
    }

    /**
     * @inheritdoc
     */
    public function getContentContainerName(ContentContainerActiveRecord $container)
    {
        return Yii::t('LinklistModule.base', 'Linklist');
    }
}
