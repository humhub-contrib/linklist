<?php

class LinklistModule extends HWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
	}
	
	/**
	 * Defines what to do if a spaces sidebar is initialzed.
	 * @param type $event
	 */
	public static function onSpaceSidebarInit($event) {
		// Is Module enabled on this workspace?
		$space = Yii::app()->getController()->getSpace();
		if($space->isModuleEnabled('linklist')) {
			$event->sender->addWidget('application.modules.linklist.widgets.LinklistSidebarWidget', array(), array('sortOrder' => 200));
		}
	}
	
	/**
	 * Defines what to do if a spaces sidebar is initialzed.
	 * @param type $event
	 */
	public static function onUserSidebarInit($event) {
		// Is Module enabled for this user?
		$user = Yii::app()->getController()->getUser();
		if($user->isModuleEnabled('linklist')) {
			$event->sender->addWidget('application.modules.linklist.widgets.LinklistSidebarWidget', array(), array('sortOrder' => 200));
		}
	}
	
	
	/**
	 * On build of a Space Navigation, check if this module is enabled.
	 * When enabled add a menu item
	 *
	 * @param type $event
	 */
	public static function onSpaceMenuInit($event) {
		// Is Module enabled on this workspace?
		$space = Yii::app()->getController()->getSpace();
		if($space->isModuleEnabled('linklist')) {
			$event->sender->addItem(array(
					'label' => Yii::t('LinklistModule.base', 'Linklist'),
					'url' => Yii::app()->createUrl('/linklist/linklist/showSpaceLinks', array('sguid' => $space->guid)),
					'icon' => '<i class="fa fa-external-link-square"></i>',
					'isActive' => (Yii::app()->controller->module && Yii::app()->controller->module->id == 'linklist'),
			));
		}
	}
	
	/**
	 * On build of the User Navigation, check if this module is enabled.
	 * When enabled add a menu item
	 *
	 * @param type $event
	 */
	public static function onUserMenuInit($event) {
		// Is Module enabled for this user?
		$user = Yii::app()->getController()->getUser();
		if($user->isModuleEnabled('linklist')) {
			$event->sender->addItem(array(
					'label' => Yii::t('LinklistModule.base', 'Linklist'),
					'url' => Yii::app()->createUrl('/linklist/linklist/showUserLinks', array('uguid' => $user->guid)),
					'icon' => '',
					'isActive' => (Yii::app()->controller->module && Yii::app()->controller->module->id == 'linklist'),
			));
		}
	}
}
