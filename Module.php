<?php

namespace humhub\modules\linklist;

use Yii;
use humhub\modules\linklist\models\Category;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use humhub\modules\content\components\ContentContainerActiveRecord;

class Module extends \humhub\components\Module
{

    public function behaviors()
    {
        return [
            \humhub\modules\user\behaviors\UserModule::className(),
            \humhub\modules\space\behaviors\SpaceModule::className(),
        ];
    }

    /**
     * Returns space module config url.
     *
     * @return String
     */
    public function getSpaceModuleConfigUrl(Space $space)
    {
        return $space->createUrl('/linklist/linklist/config');
    }

    /**
     * Returns the user module config url.
     *
     * @return String
     */
    public function getUserModuleConfigUrl(User $user)
    {
        return $user->createUrl('/linklist/linklist/config');
    }

    /**
     * On global module disable, delete all created content
     */
    public function disable()
    {
        if (parent::disable()) {
            foreach (Category::find()->all() as $category) {
                $category->delete();
            }
            return true;
        }

        return false;
    }

    /**
     * Enables this module for a Space.
     */
    public function enableSpaceModule(Space $space)
    {
        // set default config values
        $this->setDefaultValues($space->container);
        parent::enableSpaceModule($space);
    }

    /**
     * Enables this module for a Space.
     */
    public function enableUserModule(User $user)
    {
        // set default config values
        $this->setDefaultValues($user->container);
        parent::enableUserModule($user);
    }

    /**
     * Initialize Default Settings for a Container.
     * @param HActiveRecordContentContainer $container
     */
    private function setDefaultValues(ContentContainerActiveRecord $container)
    {
        $container->setSetting('enableDeadLinkValidation', 0, 'linklist');
        $container->setSetting('enableWidget', 0, 'linklist');
    }

    /**
     * On disabling this module on a space, deleted all module -> space related content/data.
     * Method stub is provided by "SpaceModuleBehavior"
     *
     * @param Space $space
     */
    public function disableSpaceModule(Space $space)
    {
        foreach (Category::model()->contentContainer($space)->findAll() as $content) {
            $content->delete();
        }
        foreach (Link::model()->contentContainer($space)->findAll() as $content) {
            $content->delete();
        }
    }

    /**
     * On disabling this module on a space, deleted all module -> user related content/data.
     * Method stub is provided by "UserModuleBehavior"
     *
     * @param User $user
     */
    public function disableUserModule(User $user)
    {
        foreach (Category::model()->contentContainer($user)->findAll() as $content) {
            $content->delete();
        }
        foreach (Link::model()->contentContainer($user)->findAll() as $content) {
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
