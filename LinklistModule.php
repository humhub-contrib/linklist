<?php
class LinklistModule extends HWebModule {
	
	public function init() {
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		// register script and css files
		$assetPrefix = Yii::app()->assetManager->publish(dirname(__FILE__) . '/resources', true, 0, defined('YII_DEBUG'));
		Yii::app()->clientScript->registerScriptFile($assetPrefix . '/linklist.js');
		Yii::app()->clientScript->registerCssFile($assetPrefix . '/linklist.css');
	}
	
	public function behaviors() {
		return array (
			'SpaceModuleBehavior' => array (
					'class' => 'application.modules_core.space.behaviors.SpaceModuleBehavior' 
			), 
		);
	}
	
	public function getConfigUrl()
	{
		return Yii::app()->createUrl('//linklist/config/config');
	}
	
	/**
	 * Returns module config url for spaces of your module.
	 * You may want to overwrite it in your module.
	 *
	 * @return String
	 */
	public function getSpaceModuleConfigUrl(Space $space)
	{
		return Yii::app()->createUrl('//linklist/spacelinklist/config', array(
			'sguid' => $space->guid,
		));
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
