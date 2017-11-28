<?php

namespace humhub\modules\linklist;

use Yii;

use humhub\modules\linklist\models\Link;
use humhub\modules\linklist\models\Category;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\components\ContentContainerModule;

class Module extends ContentContainerModule
{

    /**
     * @inheritdoc
     */
    public function getContentContainerTypes()
    {
        return [
            User::className(),
            Space::className(),
        ];
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
            $category->delete();
        }
        
        parent::disable();
    }

    /**
     * @inheritdoc
     */
    public function enableContentContainer(ContentContainerActiveRecord $container)
    {
        $container->setSetting('enableDeadLinkValidation', 0, 'linklist');
        $container->setSetting('enableWidget', 0, 'linklist');
        parent::enableContentContainer($container);
    }

    /**
     * @inheritdoc
     */
    public function disableContentContainer(ContentContainerActiveRecord $container)
    {
        parent::disableContentContainer($container);

        foreach (Category::find()->contentContainer($container)->all() as $content) {
            $content->delete();
        }
        foreach (Link::find()->contentContainer($container)->all() as $content) {
            $content->delete();
        }
    }

    /**
     * Defines what to do if a spaces sidebar is initialzed.
     * 
     * @param type $event        	
     */
    public static function onSpaceSidebarInit($event)
    {

        $space = $event->sender->space;
        if ($space->isModuleEnabled('linklist')) {
            $event->sender->addWidget(widgets\Sidebar::className(), array('contentContainer' => $space), array(
                'sortOrder' => 200,
            ));
        }
    }

    /**
     * On build of a Space Navigation, check if this module is enabled.
     * When enabled add a menu item
     *
     * @param type $event        	
     */
    public static function onSpaceMenuInit($event)
    {

        $space = $event->sender->space;
        if ($space->isModuleEnabled('linklist') && $space->isMember()) {
            $event->sender->addItem(array(
                'label' => Yii::t('LinklistModule.base', 'Linklist'),
                'group' => 'modules',
                'url' => $space->createUrl('/linklist/linklist'),
                'icon' => '<i class="fa fa-link"></i>',
                'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'linklist')
            ));
        }
    }

    /**
     * On build of a Profile Navigation, check if this module is enabled.
     * When enabled add a menu item
     *
     * @param type $event
     */
    public static function onProfileMenuInit($event)
    {
        $user = $event->sender->user;

        // Is Module enabled on this workspace?
        if ($user->isModuleEnabled('linklist') && !Yii::$app->user->isGuest && $user->id == Yii::$app->user->id) {
            $event->sender->addItem(array(
                'label' => Yii::t('LinklistModule.base', 'Linklist'),
                'url' => $user->createUrl('/linklist/linklist'),
                'icon' => '<i class="fa fa-link"></i>',
                'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'linklist'),
            ));
        }
    }

    /**
     * Defines what to do if a spaces sidebar is initialzed.
     *
     * @param type $event
     */
    public static function onProfileSidebarInit($event)
    {
        $user = $event->sender->user;

        if ($user->isModuleEnabled('linklist')) {
            $event->sender->addWidget(widgets\Sidebar::className(), array('contentContainer' => $user), array(
                'sortOrder' => 200,
            ));
        }
    }

}
