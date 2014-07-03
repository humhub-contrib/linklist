<?php

class SpaceLinklistController extends Controller
{
	public $subLayout = "application.modules_core.space.views.space._layout";
	
	/**
	 * Add mix-ins to this model
	 *
	 * @return type
	 */
	public function behaviors() {
		return array(
			'SpaceControllerBehavior' => array(
					'class' => 'application.modules_core.space.SpaceControllerBehavior',
			),
		);
	}
	
	private function isEditable($space) {
		return Yii::app()->user->isAdmin() || $space->owner->guid == Yii::app()->user->guid;
	}
	
	public function actionShowLinklist() {
		$container = $this->getSpace();
		$categories = Category::model()->contentContainer($container)->findAll();
 		$links = array();
 		
		foreach($categories as $category) {
			$links[$category->id] = array();
			foreach(Link::model()->findAllByAttributes(array('category_id'=>$category->id)) as $link) {
				$links[$category->id][] = $link;
			} 
		}
		
		$this->render('showLinklist', array(
			'sguid' => $container->guid,
			'categories' => $categories,
			'links' => $links,
			'editable' => $this->isEditable($container),
		));
	}
	
	public function actionEditCategory() {
		
		$container = $this->getSpace();
		if(!$this->isEditable($container)) {
			throw new CHttpException(404, Yii::t('LinklistModule.base', 'Linklist is not editable!'));
		}
	
		$category_id = (int) Yii::app()->request->getQuery('category_id');
		$category = Category::model()->findByAttributes(array('id' => $category_id));
		$isCreated = false;
	
		if ($category == null) {
			$category = new Category;
			$isCreated = true;
		}	
	
		if (isset($_POST['Category'])) {
			$_POST = Yii::app()->input->stripClean($_POST);
	
			$category->attributes = $_POST['Category'];
			$category->content->container = $container;
			if ($category->validate()) {
				$category->save();
				$this->redirect(Yii::app()->createUrl('linklist/spacelinklist/showlinklist', array (
					'sguid' => $container->guid,
					)
				));
			}
		}
	
		$this->render('editCategory', array(
			'sguid' => $container->guid,
			'category' => $category,
			'isCreated' => $isCreated,
		));
	}
	
	public function actionDeleteCategory() {
	
		$container = $this->getSpace();
		if(!$this->isEditable($container)) {
			throw new CHttpException(404, Yii::t('LinklistModule.base', 'Linklist is not editable!'));
		}
	
		$category_id = (int) Yii::app()->request->getQuery('category_id');
		$category = Category::model()->findByAttributes(array('id' => $category_id));
	
		if ($category == null) {
			throw new CHttpException(404, Yii::t('LinklistModule.base', 'Requested category could not be found.'));
		}
	
		$category->delete();
	
		$this->redirect(Yii::app()->createUrl('linklist/spacelinklist/showlinklist', array (
			'sguid' => $container->guid,
			)
		));
	}
	
	public function actionEditLink() {
		
		$container = $this->getSpace();
		if(!$this->isEditable($container)) {
			throw new CHttpException(404, Yii::t('LinklistModule.base', 'Linklist is not editable!'));
		}
		
		$link_id = (int) Yii::app()->request->getQuery('link_id');
		$category_id = (int) Yii::app()->request->getQuery('category_id');
		$link = Link::model()->findByAttributes(array('id' => $link_id));
		$isCreated = false;
		
		if ($link == null) {
			$link = new Link();
			$link->category_id = $category_id;
			$isCreated = true;
		}
		
		if (isset($_POST['Link'])) {
			$_POST = Yii::app()->input->stripClean($_POST);
		
			$link->attributes = $_POST['Link'];
			$link->content->container = $container;
			if ($link->validate()) {
				$link->save();
				$this->redirect(Yii::app()->createUrl('linklist/spacelinklist/showlinklist', array (
					'sguid' => $container->guid,
					)
				));
			}
		}
		
		$this->render('editLink', array(
			'sguid' => $container->guid,
			'link' => $link,
			'isCreated' => $isCreated,
		));
	}
	
	public function actionDeleteLink() {
	
		$container = $this->getSpace();
		if(!$this->isEditable($container)) {
			throw new CHttpException(404, Yii::t('LinklistModule.base', 'Linklist is not editable!'));
		}
	
		$link_id = (int) Yii::app()->request->getQuery('link_id');
		$link = Link::model()->findByAttributes(array('id' => $link_id));
	
		if ($link == null) {
			throw new CHttpException(404, Yii::t('LinklistModule.base', 'Requested link could not be found.'));
		}
	
		$link->delete();
	
		$this->redirect(Yii::app()->createUrl('linklist/spacelinklist/showlinklist', array (
			'sguid' => $container->guid,
			)
		));
	}
	
	/**
	 * Clean an array by deleting null values and empty subarrays.
	 * @param Array $source
	 * @param Array $result
	 */
	public static function cleanArray($source = array(), $result = array()) {
		if(!is_array($source) || !is_array($result)) {
			return;
		}
		foreach($source as $key => $object) {
			if($object = null) {
				continue;
			}
			else if(!is_array($object)) {
				$result[$key] = $object;
			}
			else {
				$temp = array();
				SpaceLinklistController::cleanArray($object, $temp);
				if(!empty($temp)) {
					$result[$key] = $object;
				}
			}
		}
	}
}

?>
