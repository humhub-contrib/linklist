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
        return array(
            'SpaceModuleBehavior' => array(
                'class' => 'application.modules_core.space.behaviors.SpaceModuleBehavior',
            ),
            'UserModuleBehavior' => array(
                'class' => 'application.modules_core.user.behaviors.UserModuleBehavior',
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
		return Yii::app()->createUrl('//linklist/linklist/config', array(
				'sguid' => $space->guid,
		));
	}
	
	/**
	 * Returns module config url for spaces of your module.
	 * You may want to overwrite it in your module.
	 *
	 * @return String
	 */
	public function getUserModuleConfigUrl(User $user)
	{
		return Yii::app()->createUrl('//linklist/linklist/config', array(
				'uguid' => $user->guid,
		));
	}
	
	/**
	 * On global module disable, delete all created content
	 */
	public function disable()
	{
		if (parent::disable()) {
			throw new CHttpException(404);
			foreach (Content::model()->findAll(array(
					'condition' => 'object_model=:cat OR object_model=:link',
					'params' => array(':cat' => 'Category', ':link' => 'Link'))) as $content) {
				$content->delete();
			}
			return true;
		}
	
		return false;
	}
	
	/**
	 * On disabling this module on a space, deleted all module -> space related content/data.
	 * Method stub is provided by "SpaceModuleBehavior"
	 *
	 * @param Space $space
	 */
	public function disableSpaceModule(Space $space) {
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
	public function disableUserModule(User $user) {
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
					'url' => Yii::app ()->createUrl ( '/linklist/linklist/showLinklist', array ('sguid' => $space->guid) ),
					'icon' => '<i class="fa fa-external-link-square"></i>',
					'isActive' => (Yii::app ()->controller->module && Yii::app ()->controller->module->id == 'linklist') 
			) );
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
	
		$user = Yii::app()->getController()->getUser();
	
		// Is Module enabled on this workspace?
		if ($user->isModuleEnabled('linklist')) {
			$event->sender->addItem(array(
					'label' => Yii::t('LinklistModule.base', 'Linklist'),
					'url' => Yii::app()->createUrl('/linklist/linklist/showLinklist', array('uguid' => $user->guid)),
					'isActive' => (Yii::app()->controller->module && Yii::app()->controller->module->id == 'linklist'),
			));
		}
	}
	
	/**
	 * Defines what to do if a spaces sidebar is initialzed.
	 *
	 * @param type $event
	 */
	public static function onProfileSidebarInit($event) {
	
		$user = Yii::app()->getController()->getUser();
		if ($user->isModuleEnabled('linklist')) {
			$event->sender->addWidget ( 'application.modules.linklist.widgets.LinklistSidebarWidget', array (), array (
					'sortOrder' => 200,
			) );
		}
	}
}
