<?php

class LinklistController extends ContentContainerController
{
		
	public $accessLevel = 0;
	public $guidParamName = '';
	public $modulesUrl = '';
	
	/**
	 * Automatically loads the underlying contentContainer (User/Space) by using
	 * the uguid/sguid request parameter
	 *
	 * @return boolean
	 */
	public function init() {
		$retVal = parent::init(); 
		$this->accessLevel = $this->getAccessLevel(); 
		//echo '<script>alert("access level : '.$this->getAccessLevel().'")</script>';
		$this->guidParamName = $this->getGuidParamName();
		$this->modulesUrl = $this->getModulesUrl();
		return $retVal;
	}
	
	/**
	 * Get the acces level to the linklist of the currently logged in user.
	 * @return number 0 -> no write access / 1 -> create links and edit own links / 2 -> full write access
	 */
	private function getAccessLevel() {
		if($this->contentContainer instanceof User) {
			return $this->contentContainer->id == Yii::app()->user->id ? 2 : 0;
		}
		else if($this->contentContainer instanceof Space) {
			return $this->contentContainer->isAdmin(Yii::app()->user->id) ? 2 : 1;
		}
	}
	
	private function getModulesUrl() {
		if($this->contentContainer instanceof User) {
			return $this->createContainerUrl('//user/account/editModules');
		}
		else if($this->contentContainer instanceof Space) {
			return $this->createContainerUrl('//space/admin/modules');
		}
	}
	
	private function getConfigSubLayout() {
		if($this->contentContainer instanceof User) {
			return "application.modules_core.user.views.account._layout";
		}
		else if($this->contentContainer instanceof Space) {
			return "application.modules_core.space.views.space._layout";
		}
	}
	
	/**
	 * Get the url parameter name for the guid.
	 * @return string space -> sguid / user -> uguid
	 */
	private function getGuidParamName() {
		if($this->contentContainer instanceof User) {
			return 'uguid';
		}
		else if($this->contentContainer instanceof Space) {
			return 'sguid';
		}
	}
	
	public function actionShowLinklist() {
		
		$categoryBuffer = Category::model()->contentContainer($this->contentContainer)->findAll(array('order' => 'sort_order ASC'));
		
		$categories = array();
		$links = array();
			
		foreach($categoryBuffer as $category) {
			$categories[] = $category;
			$links[$category->id] = Link::model()->findAllByAttributes(array('category_id'=>$category->id), array('order' => 'sort_order ASC'));;
		}

		$this->render('showLinklist', array(
			$this->guidParamName => $this->contentContainer->guid,
			'categories' => $categories,
			'links' => $links,
			'accessLevel' => $this->accessLevel,
		));
	}
	
	public function actionEditCategory() {
		
		if($this->accessLevel == 0 || $this->accessLevel == 1) {
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
			$category->content->container = $this->contentContainer;
			if ($category->validate()) {
				$category->save();
				$this->redirect(Yii::app()->createUrl('linklist/linklist/showlinklist', array($this->guidParamName => $this->contentContainer->guid)));
			}
		}
	
		$this->render('editCategory', array(
			$this->guidParamName => $this->contentContainer->guid,
			'category' => $category,
			'isCreated' => $isCreated,
		));
	}
	
	public function actionDeleteCategory() {
	
		if($this->accessLevel == 0 || $this->accessLevel == 1) {
			throw new CHttpException(404, Yii::t('LinklistModule.base', 'You miss the rights to delete this category!'));
		}
	
		$category_id = (int) Yii::app()->request->getQuery('category_id');
		$category = Category::model()->findByAttributes(array('id' => $category_id));
	
		if ($category == null) {
			throw new CHttpException(404, Yii::t('LinklistModule.base', 'Requested category could not be found.'));
		}
	
		$category->delete();
	
		$this->redirect(Yii::app()->createUrl('linklist/linklist/showlinklist', array (
			$this->guidParamName => $this->contentContainer->guid,
			)
		));
	}
	
	public function actionEditLink() {
		
		$link_id = (int) Yii::app()->request->getQuery('link_id');
		$category_id = (int) Yii::app()->request->getQuery('category_id');
		$link = Link::model()->findByAttributes(array('id' => $link_id));
		$isCreated = false;
		
		// access level 0 may neither create nor edit
		if($this->accessLevel == 0) {
			throw new CHttpException(404, Yii::t('LinklistModule.base', 'You miss the rights to edit this link!'));
		}
		// access level 1 + 2 may create
		else if ($link == null) {
			$link = new Link();
			$link->category_id = $category_id;
			$isCreated = true;
		}
		// access level 1 may edit own links, 2 all links
		else if($this->accessLevel == 1 && $link->content->created_by != Yii::app()->user->id) {
			throw new CHttpException(404, Yii::t('LinklistModule.base', 'You miss the rights to edit this link!'));
		}
		
		if (isset($_POST['Link'])) {
			$_POST = Yii::app()->input->stripClean($_POST);
		
			$link->attributes = $_POST['Link'];
			$link->content->container = $this->contentContainer;
			if ($link->validate()) {
				$link->save();
				$this->redirect(Yii::app()->createUrl('linklist/linklist/showlinklist', array ($this->guidParamName => $this->contentContainer->guid)));
			}
		}
		
		$this->render('editLink', array(
			$this->guidParamName => $this->contentContainer->guid,
			'link' => $link,
			'isCreated' => $isCreated,
		));
	}
	
	public function actionDeleteLink() {
	
		$link_id = (int) Yii::app()->request->getQuery('link_id');
		$link = Link::model()->findByAttributes(array('id' => $link_id));
		
		if ($link == null) {
			throw new CHttpException(404, Yii::t('LinklistModule.base', 'Requested link could not be found.'));
		}
		// access level 1 may delete own links, 2 all links
		else if($this->accessLevel == 0 || $this->accessLevel == 1 && $link->content->created_by != Yii::app()->user->id) {
			throw new CHttpException(404, Yii::t('LinklistModule.base', 'You miss the rights to delete this link!'));
		}	
	
		$link->delete();
	
		$this->redirect(Yii::app()->createUrl('linklist/linklist/showlinklist', array ($this->guidParamName => $this->contentContainer->guid)));
	}
	
	/**
	 * Space Configuration Action for Admins
	 */
	public function actionConfig() {
		 
		Yii::import('linklist.forms.*');
		$this->subLayout = $this->getConfigSubLayout();
		
		$form = new LinklistConfigureForm();
	
// 		uncomment the following code to enable ajax-based validation
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'linklist-configure-form') {
            echo CActiveForm::validate($form);
            Yii::app()->end();
        }
	
		if (isset($_POST['LinklistConfigureForm'])) {
			$_POST['LinklistConfigureForm'] = Yii::app()->input->stripClean($_POST['LinklistConfigureForm']);
			$form->attributes = $_POST['LinklistConfigureForm'];
	
			if ($form->validate()) {
				$this->contentContainer->setSetting('enableDeadLinkValidation', $form->enableDeadLinkValidation, 'linklist');
				$this->redirect(Yii::app()->createUrl('linklist/linklist/config', array ($this->guidParamName => $this->contentContainer->guid)));
			}
		} else {
			$form->enableDeadLinkValidation = $this->contentContainer->getSetting('enableDeadLinkValidation', 'linklist');
			// set default if global setting empty
			if($form->enableDeadLinkValidation == '') {
				$form->enableDeadLinkValidation = 0;
			}
		}
	
		$this->render('config', array('model' => $form, $this->guidParamName => $this->contentContainer->guid));
	}
}

?>
