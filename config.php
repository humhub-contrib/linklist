<?php

use humhub\modules\space\widgets\Menu;
use humhub\modules\user\widgets\ProfileMenu;
use humhub\modules\user\widgets\ProfileSidebar;
use humhub\modules\space\widgets\Sidebar;

return [
    'id' => 'linklist',
    'class' => 'humhub\modules\linklist\Module',
    'namespace' => 'humhub\modules\linklist',
    'events' => [
        array('class' => Menu::className(), 'event' => Menu::EVENT_INIT, 'callback' => array('humhub\modules\linklist\Module', 'onSpaceMenuInit')),
        array('class' => ProfileMenu::className(), 'event' => ProfileMenu::EVENT_INIT, 'callback' => array('humhub\modules\linklist\Module', 'onProfileMenuInit')),
        array('class' => Sidebar::className(), 'event' => Sidebar::EVENT_INIT, 'callback' => array('humhub\modules\linklist\Module', 'onSpaceSidebarInit')),
        array('class' => ProfileSidebar::className(), 'event' => ProfileSidebar::EVENT_INIT, 'callback' => array('humhub\modules\linklist\Module', 'onProfileSidebarInit')),
    ],
];
?>
