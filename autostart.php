<?php

Yii::app()->moduleManager->register(array(
    'id' => 'linklist',
    'title' => Yii::t('SpacelinksModule.base', 'Linklist Module'),
    'description' => Yii::t('LinklistModule.base', 'Module displaying links and offering options to edit them.'),
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
    'userModules' => array(
    		'linklist' => array(
    				'title' => Yii::t('LinklistModule.base', 'Links'),
    				'description' => Yii::t('LinklistModule.base', 'Adds linklist features to your profile.'),
    		),
    ),
    'spaceModules' => array(
    		'linklist' => array(
    				'title' => Yii::t('LinklistModule.base', 'Links'),
    				'description' => Yii::t('LinklistModule.base', 'Adds linklist features to your space.'),
    		),
    ),
));
?>
