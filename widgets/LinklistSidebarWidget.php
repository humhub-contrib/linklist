<?php

/**
 * LinklistSidebarWidget displaying a list of links.
 *
 * It is attached to the sidebar.
 *
 * @package humhub.modules.linklist.widgets
 * @author Sebastian Stumpf
 */
class LinklistSidebarWidget extends HWidget {
	
	public function run() {
		$container = Yii::app()->getController()->getSpace();
		$categories = Category::model()->contentContainer($container)->findAll();
		$links = array();
		$empty = true;
			
		foreach($categories as $category) {
			$links[$category->id] = array();
			foreach($category->links as $link) {
				$links[$category->id][] = $link;
				// check if links are available
				if($empty) {
					$empty = false;
				}
			}
		}
		
		if(!$empty) {
			$this->render ( 'linklistPanel', array ('container' => $container, 'categories' => $categories, 'links' => $links));
		}
	}
}



?>
