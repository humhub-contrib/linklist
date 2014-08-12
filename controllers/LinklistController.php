<?php
/**
 * Description of LinklistController.
 *
 * @package humhub.modules.linklist.controllers
 * @author Sebastian Stumpf
 */
class LinklistController extends ContentContainerController
{
	/** access level of the user currently logged in. 0 -> no write access / 1 -> create links and edit own links / 2 -> full write access. **/	
	public $accessLevel = 0;
	/** url parameter name for the guid. space -> sguid / user -> uguid. **/
	public $guidParamName = '';
	/** the url back to the modules, used in the config view. **/
	public $modulesUrl = '';
	
	public function behaviors() {
		return array(
				'HReorderContentBehavior' => array(
						'class' => 'application.behaviors.HReorderContentBehavior',
				)
		);
	}
	
	/**
	 * @return array action filters
	 */
	public function filters() {
		return array(
				'accessControl', // perform access control for CRUD operations -> redirect to login if access denied
		);
	}
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() {
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
	 * Automatically loads the underlying contentContainer (User/Space) by using
	 * the uguid/sguid request parameter
	 *
	 * @return boolean
	 */
	public function init() {
		$retVal = parent::init(); 
		$this->accessLevel = $this->getAccessLevel(); 
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
	
	/**
	 * Get the url back to the modules, used in the config view.
	 * @return string
	 */
	private function getModulesUrl() {
		if($this->contentContainer instanceof User) {
			return $this->createContainerUrl('//user/account/editModules');
		}
		else if($this->contentContainer instanceof Space) {
			return $this->createContainerUrl('//space/admin/modules');
		}
	}

	/**
	 * Get the sublayout for the config view.
	 * @return string the url.
	 */
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
	
	/**
	 * Action that renders the list view.
	 * @see views/linklist/showLinklist.php
	 */
	public function actionShowLinklist() {
		
		$this->checkContainerAccess();
		
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
	
	/**
	 * Action that renders the view to add or edit a category.<br />
	 * The request has to provide the id of the category to edit in the url parameter 'category_id'.
	 * @see views/linklist/editCategory.php
	 * @throws CHttpException 404, if the logged in User misses the rights to access this view.
	 */
	public function actionEditCategory() {
		
		$this->checkContainerAccess();
		
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
	
	/**
	 * Action that deletes a given category.<br />
	 * The request has to provide the id of the category to delete in the url parameter 'category_id'. 
	 * @throws CHttpException 404, if the logged in User misses the rights to access this view.
	 */
	public function actionDeleteCategory() {
		
		$this->checkContainerAccess();
		
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
	
	/**
	 * Action that renders the view to add or edit a category.<br />
	 * The request has to provide the id of the category the link should be created in, in the url parameter 'category_id'.<br />
	 * If an existing ling should be edited, the link's id has to be given in 'link_id'.<br />
	 * @see views/linklist/editCategory.php
	 * @throws CHttpException 404, if the logged in User misses the rights to access this view.
	 */
	public function actionEditLink() {
		
		$this->checkContainerAccess();
		
		$link_id = (int) Yii::app()->request->getQuery('link_id');
		$category_id = (int) Yii::app()->request->getQuery('category_id');
		$link = Link::model()->findByAttributes(array('id' => $link_id));
		$isCreated = false;
		
		// access level 0 may neither create nor edit
		if($this->accessLevel == 0) {
			throw new CHttpException(404, Yii::t('LinklistModule.base', 'You miss the rights to add/edit links!'));
		}
		// access level 1 + 2 may create
		else if ($link == null) {
			$link = new Link();
			if(Category::model()->findByAttributes(array('id' => $category_id)) == null) {
				throw new CHttpException(404, Yii::t('LinklistModule.base', 'The category you want to create your link in could not be found!'));
			}
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
	
	/**
	 * Action that deletes a given category.<br />
	 * The request has to provide the id of the link to delete in the url parameter 'link_id'.
	 * @throws CHttpException 404, if the logged in User misses the rights to access this view.
	 */
	public function actionDeleteLink() {
		
		$this->checkContainerAccess();
		
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
				$this->contentContainer->setSetting('enableWidget', $form->enableWidget, 'linklist');
				$this->redirect(Yii::app()->createUrl('linklist/linklist/config', array ($this->guidParamName => $this->contentContainer->guid)));
			}
		} else {
			$form->enableDeadLinkValidation = $this->contentContainer->getSetting('enableDeadLinkValidation', 'linklist');
			$form->enableWidget = $this->contentContainer->getSetting('enableWidget', 'linklist');
		}
	
		$this->render('config', array('model' => $form, $this->guidParamName => $this->contentContainer->guid));
	}
	
	/**
	 * Reorder Links action.
	 * @uses behaviors.ReorderContentBehavior
	 */
	public function actionReorderLinks() {
		// validation
		try {
			$this->checkContainerAccess();
			if($this->accessLevel != 2) {
				throw new CHttpException(403, Yii::t('LinklistModule.base', 'You miss the rights to reorder categories.!'));
			}
		} catch (CHttpException $e) {
			echo json_encode($this->reorderContent('Link', $e->statusCode, $e->getMessage()));
			return;
		}
		// generate json response
		echo json_encode($this->reorderContent('Link', 200, 'The item order was successfully changed.'));
	}
	
	/**
	 * Reorder Categories action.
	 * @uses behaviors.ReorderContentBehavior
	 */
	public function actionReorderCategories() {
		// validation
		try {
			$this->checkContainerAccess();
			if($this->accessLevel != 2) {
				throw new CHttpException(403, Yii::t('LinklistModule.base', 'You miss the rights to reorder categories.!'));
			}
		} catch (CHttpException $e) {
			echo json_encode($this->reorderContent('Category', $e->statusCode, $e->getMessage()));
			return;
		}
		// generate json response
		echo json_encode($this->reorderContent('Category', 200, Yii::t('LinklistModule.base', 'The item order was successfully changed.')));
	}
}

?>
