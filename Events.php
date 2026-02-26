<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\linklist;

use humhub\helpers\ControllerHelper;
use humhub\modules\space\widgets\Menu as SpaceMenu;
use humhub\modules\space\widgets\Sidebar as SpaceSidebar;
use humhub\modules\ui\menu\MenuLink;
use humhub\modules\user\widgets\ProfileMenu;
use humhub\modules\user\widgets\ProfileSidebar;
use Yii;

class Events
{
    public static function onSpaceMenuInit($event)
    {
        /* @var SpaceMenu $menu */
        $menu = $event->sender;

        if ($menu->space->moduleManager->isEnabled('linklist') && $menu->space->isMember()) {
            $menu->addEntry(new MenuLink([
                'label' => Yii::t('LinklistModule.base', 'Linklist'),
                'url' => $menu->space->createUrl('/linklist/linklist'),
                'icon' => 'link',
                'isActive' => ControllerHelper::isActivePath('linklist'),
            ]));
        }
    }

    public static function onSpaceSidebarInit($event)
    {
        /* @var SpaceSidebar $sidebar */
        $sidebar = $event->sender;

        if ($sidebar->space->moduleManager->isEnabled('linklist')) {
            $sidebar->addWidget(
                widgets\Sidebar::class,
                ['contentContainer' => $sidebar->space],
                ['sortOrder' => 200],
            );
        }
    }

    public static function onProfileMenuInit($event)
    {
        /* @var ProfileMenu $menu */
        $menu = $event->sender;

        // Is Module enabled on this workspace?
        if ($menu->user->moduleManager->isEnabled('linklist') && !Yii::$app->user->isGuest && $menu->user->id == Yii::$app->user->id) {
            $menu->addEntry(new MenuLink([
                'label' => Yii::t('LinklistModule.base', 'Linklist'),
                'url' => $menu->user->createUrl('/linklist/linklist'),
                'icon' => 'link',
                'isActive' => ControllerHelper::isActivePath('linklist'),
            ]));
        }
    }

    public static function onProfileSidebarInit($event)
    {
        /* @var ProfileSidebar $sidebar */
        $sidebar = $event->sender;

        if ($sidebar->user->moduleManager->isEnabled('linklist')) {
            $sidebar->addWidget(
                widgets\Sidebar::class,
                ['contentContainer' => $sidebar->user],
                ['sortOrder' => 200],
            );
        }
    }
}
