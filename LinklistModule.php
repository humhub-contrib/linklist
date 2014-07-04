<?php
class LinklistModule extends HWebModule {
	
	public function init() {
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
	}
	
	public function behaviors() {
		return array (
			'SpaceModuleBehavior' => array (
					'class' => 'application.modules_core.space.behaviors.SpaceModuleBehavior' 
			), 
		);
	}

	/**
	 * Defines what to do if a spaces sidebar is initialzed.
	 * 
	 * @param type $event        	
	 */
	public static function onSpaceSidebarInit($event) {
		
		$space = Yii::app()->getController()->getSpace();
		if ($space->isModuleEnabled('linklist')) {
			$event->sender->addWidget ( 'application.modules.linklist.widgets.LinklistSidebarWidget', array (), array (
					'sortOrder' => 200, 
			) );
		}
	}
	
	/**
	 * On build of a Space Navigation, check if this module is enabled.
	 * When enabled add a menu item
	 *
	 * @param type $event        	
	 */
	public static function onSpaceMenuInit($event) {
		
		$space = Yii::app()->getController()->getSpace();
		if ($space->isModuleEnabled('linklist')) {
			$event->sender->addItem ( array (
					'label' => Yii::t ( 'LinklistModule.base', 'Linklist' ),
					'url' => Yii::app ()->createUrl ( '/linklist/spacelinklist/showLinklist', array (
							'sguid' => $space->guid,
					) ),
					'icon' => '<i class="fa fa-external-link-square"></i>',
					'isActive' => (Yii::app ()->controller->module && Yii::app ()->controller->module->id == 'linklist') 
			) );
		}
	}
}
