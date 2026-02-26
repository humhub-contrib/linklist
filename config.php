<?php

use humhub\modules\linklist\Events;
use humhub\modules\space\widgets\Menu;
use humhub\modules\user\widgets\ProfileMenu;
use humhub\modules\user\widgets\ProfileSidebar;
use humhub\modules\space\widgets\Sidebar;

return [
    'id' => 'linklist',
    'class' => 'humhub\modules\linklist\Module',
    'namespace' => 'humhub\modules\linklist',
    'events' => [
        ['class' => Menu::class, 'event' => Menu::EVENT_INIT, 'callback' => [Events::class, 'onSpaceMenuInit']],
        ['class' => ProfileMenu::class, 'event' => ProfileMenu::EVENT_INIT, 'callback' => [Events::class, 'onProfileMenuInit']],
        ['class' => Sidebar::class, 'event' => Sidebar::EVENT_INIT, 'callback' => [Events::class, 'onSpaceSidebarInit']],
        ['class' => ProfileSidebar::class, 'event' => ProfileSidebar::EVENT_INIT, 'callback' => [Events::class, 'onProfileSidebarInit']],
    ],
];
