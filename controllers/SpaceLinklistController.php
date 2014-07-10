<?php

class SpaceLinklistController extends Controller
{
	public $subLayout = "application.modules_core.space.views.space._layout";
	
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
				'accessControl', // perform access control for CRUD operations
		);
	}
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
				array('allow', // allow authenticated user to perform 'create' and 'update' actions
						'users' => array('@'),
				),
				array('deny', // deny all users
						'users' => array('*'),
				),
		);
	}
	
	/**
	 * Add mix-ins to this model
	 *
	 * @return type
	 */
	public function behaviors() {
		return array(
			'SpaceControllerBehavior' => array(
					'class' => 'application.modules_core.space.behaviors.SpaceControllerBehavior',
			),
		);
	}
	
	private function isAdmin($space) {
		return $space->isAdmin(Yii::app()->user->id);
	}
	
	public function actionShowLinklist() {
		
		$container = Yii::app()->getController()->getSpace();
		$categoryBuffer = Category::model()->contentContainer($container)->findAll(array('order' => 'sort_order ASC'));
		
		$categories = array();
		$links = array();
			
		foreach($categoryBuffer as $category) {
			$categories[] = $category;
			$links[$category->id] = Link::model()->findAllByAttributes(array('category_id'=>$category->id), array('order' => 'sort_order ASC'));;
		}
		
		$this->render('showLinklist', array(
			'sguid' => $container->guid,
			'categories' => $categories,
			'links' => $links,
			'isAdmin' => $this->isAdmin($container),
		));
	}
	
	public function actionEditCategory() {
		
		$container = $this->getSpace();
		if(!$this->isAdmin($container)) {
			throw new CHttpException(404, Yii::t('LinklistModule.base', 'You miss the rights to edit this category!'));
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
		if(!$this->isAdmin($container)) {
			throw new CHttpException(404, Yii::t('LinklistModule.base', 'You miss the rights to delete this category!'));
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
		$link_id = (int) Yii::app()->request->getQuery('link_id');
		$category_id = (int) Yii::app()->request->getQuery('category_id');
		$link = Link::model()->findByAttributes(array('id' => $link_id));
		$isCreated = false;
		
		if ($link == null) {
			$link = new Link();
			$link->category_id = $category_id;
			$isCreated = true;
		}
		else if(!($this->isAdmin($container) || $link->content->created_by == Yii::app()->user->id)) {
			throw new CHttpException(404, Yii::t('LinklistModule.base', 'You miss the rights to edit this link!'));
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
		$link_id = (int) Yii::app()->request->getQuery('link_id');
		$link = Link::model()->findByAttributes(array('id' => $link_id));
		
		if ($link == null) {
			throw new CHttpException(404, Yii::t('LinklistModule.base', 'Requested link could not be found.'));
		}
		if(!($this->isAdmin($container) || $link->content->created_by == Yii::app()->user->id)) {
			throw new CHttpException(404, Yii::t('LinklistModule.base', 'You miss the rights to delete this link!'));
		}	
	
		$link->delete();
	
		$this->redirect(Yii::app()->createUrl('linklist/spacelinklist/showlinklist', array (
			'sguid' => $container->guid,
			)
		));
	}
	
	/**
	 * Space Configuration Action for Admins
	 */
	public function actionConfig() {
		 
		Yii::import('linklist.forms.*');
	
		$form = new LinklistConfigureForm();
		$space = Yii::app()->getController()->getSpace();
	
// 		uncomment the following code to enable ajax-based validation
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'linklist-configure-form') {
            echo CActiveForm::validate($form);
            Yii::app()->end();
        }
	
		if (isset($_POST['LinklistConfigureForm'])) {
			$_POST['LinklistConfigureForm'] = Yii::app()->input->stripClean($_POST['LinklistConfigureForm']);
			$form->attributes = $_POST['LinklistConfigureForm'];
	
			if ($form->validate()) {
				$space->setSetting('enableDeadLinkValidation', $form->enableDeadLinkValidation, 'linklist');
				$this->redirect(Yii::app()->createUrl('linklist/spacelinklist/config', array (
					'sguid' => $space->guid,
				)));
			}
		} else {
			$form->enableDeadLinkValidation = $space->getSetting('enableDeadLinkValidation', 'linklist');
			// check global settings if space setting empty
			if($form->enableDeadLinkValidation == '' || $form->enableDeadLinkValidation == null) {
				$form->enableDeadLinkValidation = HSetting::Get('enableDeadLinkValidation', 'linklist');
			}
			// set default if global setting empty
			if($form->enableDeadLinkValidation == '') {
				$form->enableDeadLinkValidation = 0;
			}
		}
	
		$this->render('config', array('model' => $form));
	}
}

?>
