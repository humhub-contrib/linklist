<?php

Yii::app()->moduleManager->register(array(
    'id' => 'linklist',
    'class' => 'application.modules.linklist.LinklistModule',
    'import' => array(
        'application.modules.linklist.*',
    ),
    // Events to Catch 
    'events' => array(
    	array('class' => 'SpaceMenuWidget', 'event' => 'onInit', 'callback' => array('LinklistModule', 'onSpaceMenuInit')),
    	array('class' => 'ProfileMenuWidget', 'event' => 'onInit', 'callback' => array('LinklistModule', 'onUserMenuInit')),
        array('class' => 'SpaceSidebarWidget', 'event' => 'onInit', 'callback' => array('LinklistModule', 'onSpaceSidebarInit')),
        array('class' => 'ProfileSidebarWidget', 'event' => 'onInit', 'callback' => array('LinklistModule', 'onUserSidebarInit')),
    ),
));
?>
