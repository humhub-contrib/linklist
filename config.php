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
        ['class' => Menu::className(), 'event' => Menu::EVENT_INIT, 'callback' => ['humhub\modules\linklist\Module', 'onSpaceMenuInit']],
        ['class' => ProfileMenu::className(), 'event' => ProfileMenu::EVENT_INIT, 'callback' => ['humhub\modules\linklist\Module', 'onProfileMenuInit']],
        ['class' => Sidebar::className(), 'event' => Sidebar::EVENT_INIT, 'callback' => ['humhub\modules\linklist\Module', 'onSpaceSidebarInit']],
        ['class' => ProfileSidebar::className(), 'event' => ProfileSidebar::EVENT_INIT, 'callback' => ['humhub\modules\linklist\Module', 'onProfileSidebarInit']],
    ],
];
