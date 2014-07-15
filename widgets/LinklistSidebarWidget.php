<?php

/**
 * LinklistSidebarWidget displaying a list of links.
 *
 * It is attached to the sidebar of the space/user, if the module is enabled in the settings.
 *
 * @package humhub.modules.linklist.widgets
 * @author Sebastian Stumpf
 */
class LinklistSidebarWidget extends HWidget {

	public function run() {
		
		$container = $this->getContainer();
		$categoryBuffer = Category::model()->contentContainer($container)->findAll(array('order' => 'sort_order ASC'));
		
		$categories = array();
		$links = array();		
		$render = false;
			
		foreach($categoryBuffer as $category) {
			$linkBuffer = Link::model()->findAllByAttributes(array('category_id'=>$category->id), array('order' => 'sort_order ASC'));
			// categories are only displayed in the widget if they contain at least one link
			if(!empty($linkBuffer)) {
				$categories[] = $category;
				$links[$category->id] = $linkBuffer;
				$render = true;
			}
		}
		
		// if none of the categories contains a link, the linklist widget is not rendered.
		if($render) {
			// register script and css files
			$assetPrefix = Yii::app()->assetManager->publish(dirname(__FILE__) . '/../resources', true, 0, defined('YII_DEBUG'));
			Yii::app()->clientScript->registerScriptFile($assetPrefix . '/linklist.js');
			Yii::app()->clientScript->registerCssFile($assetPrefix . '/linklist.css');			
			$this->render ( 'linklistPanel', array ('container' => $container, 'categories' => $categories, 'links' => $links));
		}
	}	
	
	/**
	 * Get the Container this widget is embedded in.
	 * @throws CHttpException if the container could not be found.
	 * @return Either the User or the Space container.
	 */
	public function getContainer() {
		
		$container = null;
		
		$spaceGuid = Yii::app()->request->getQuery('sguid');
		$userGuid = Yii::app()->request->getQuery('uguid');
		
		if ($spaceGuid != "") {
		
			$container = Space::model()->findByAttributes(array('guid' => $spaceGuid));
		
			if ($container == null) {
				throw new CHttpException(404, Yii::t('SpaceModule.base', 'Space not found!'));
			}
		} elseif ($userGuid != "") {
		
			$container = User::model()->findByAttributes(array('guid' => $userGuid));
		
			if ($container == null) {
				throw new CHttpException(404, Yii::t('UserModule.base', 'Space not found!'));
			}
		} else {
			throw new CHttpException(500, Yii::t('base', 'Could not determine content container!'));
		}
		return $container;
	}
}

?>
