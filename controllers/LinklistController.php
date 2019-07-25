<?php

namespace humhub\modules\linklist\controllers;

use Yii;
use yii\web\HttpException;
use humhub\modules\user\models\User;
use humhub\modules\space\models\Space;
use humhub\modules\post\permissions\CreatePost;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\linklist\models\Category;
use humhub\modules\linklist\models\Link;
use humhub\modules\linklist\models\ConfigureForm;

/**
 * Description of LinklistController.
 *
 * @package humhub.modules.linklist.controllers
 * @author Sebastian Stumpf
 */
class LinklistController extends ContentContainerController
{

    /** access level of the user currently logged in. 0 -> no write access / 1 -> create links and edit own links / 2 -> full write access. * */
    public $accessLevel = 0;

    /*
      public function behaviors()
      {
      return array(
      'HReorderContentBehavior' => array(
      'class' => 'application.behaviors.HReorderContentBehavior',
      )
      );
      }
     */

    /**
     * Automatically loads the underlying contentContainer (User/Space) by using
     * the uguid/sguid request parameter
     *
     * @return boolean
     */
    public function init()
    {
        $retVal = parent::init();
        $this->accessLevel = $this->getAccessLevel();
        return $retVal;
    }

    /**
     * Get the acces level to the linklist of the currently logged in user.
     * @return number 0 -> no write access / 1 -> create links and edit own links / 2 -> full write access
     */
    private function getAccessLevel()
    {
        if ($this->contentContainer instanceof \humhub\modules\user\models\User) {
            return $this->contentContainer->id == Yii::$app->user->id ? 2 : 0;
        } else if ($this->contentContainer instanceof \humhub\modules\space\models\Space) {
            return $this->contentContainer->can(new \humhub\modules\post\permissions\CreatePost()) ? 2 : 1;
        }
    }

    /**
     * Action that renders the list view.
     * @see views/linklist/showLinklist.php
     */
    public function actionIndex()
    {
        $categoryBuffer = Category::find()->contentContainer($this->contentContainer)->orderBy(['sort_order' => SORT_ASC])->all();

        $categories = array();
        $links = array();

        foreach ($categoryBuffer as $category) {
            $categories[] = $category;
            $links[$category->id] = Link::find()->where(array('category_id' => $category->id))->orderBy(['sort_order' => SORT_ASC])->all();
        }

        return $this->render('index', array(
                    'contentContainer' => $this->contentContainer,
                    'categories' => $categories,
                    'links' => $links,
                    'accessLevel' => $this->accessLevel,
        ));
    }

    /**
     * Action that renders the view to add or edit a category.<br />
     * The request has to provide the id of the category to edit in the url parameter 'category_id'.
     * @see views/linklist/editCategory.php
     * @throws HttpException 404, if the logged in User misses the rights to access this view.
     */
    public function actionEditCategory()
    {

        if ($this->accessLevel == 0 || $this->accessLevel == 1) {
            throw new HttpException(404, Yii::t('LinklistModule.base', 'You miss the rights to edit this category!'));
        }

        $category_id = (int) Yii::$app->request->get('category_id');
        $category = Category::find()->contentContainer($this->contentContainer)->where(array('linklist_category.id' => $category_id))->one();

        if ($category == null) {
            $category = new Category;
            $category->content->container = $this->contentContainer;
        }

        if ($category->load(Yii::$app->request->post()) && $category->validate() && $category->save()) {
            $this->redirect($this->contentContainer->createUrl('/linklist/linklist/index'));
        }
        return $this->render('editCategory', array(
                    'category' => $category,
        ));
    }

    /**
     * Action that deletes a given category.<br />
     * The request has to provide the id of the category to delete in the url parameter 'category_id'. 
     * @throws HttpException 404, if the logged in User misses the rights to access this view.
     */
    public function actionDeleteCategory()
    {
        if ($this->accessLevel == 0 || $this->accessLevel == 1) {
            throw new HttpException(404, Yii::t('LinklistModule.base', 'You miss the rights to delete this category!'));
        }

        $category_id = (int) Yii::$app->request->get('category_id');
        $category = Category::find()->contentContainer($this->contentContainer)->where(array('linklist_category.id' => $category_id))->one();

        if ($category == null) {
            throw new HttpException(404, Yii::t('LinklistModule.base', 'Requested category could not be found.'));
        }

        $category->delete();

        $this->redirect($this->contentContainer->createUrl('/linklist/linklist/index'));
    }

    /**
     * Action that renders the view to add or edit a category.<br />
     * The request has to provide the id of the category the link should be created in, in the url parameter 'category_id'.<br />
     * If an existing ling should be edited, the link's id has to be given in 'link_id'.<br />
     * @see views/linklist/editCategory.php
     * @throws HttpException 404, if the logged in User misses the rights to access this view.
     */
    public function actionEditLink()
    {

        $link_id = (int) Yii::$app->request->get('link_id');
        $category_id = (int) Yii::$app->request->get('category_id');

        $link = Link::find()->where(array('linklist_link.id' => $link_id))->contentContainer($this->contentContainer)->one();

        // access level 0 may neither create nor edit
        if ($this->accessLevel == 0) {
            throw new HttpException(404, Yii::t('LinklistModule.base', 'You miss the rights to add/edit links!'));
        } else if ($link == null) {
            // access level 1 + 2 may create
            $link = new Link();
            if (Category::find()->contentContainer($this->contentContainer)->where(['linklist_category.id' => $category_id])->one() == null) {
                throw new HttpException(404, Yii::t('LinklistModule.base', 'The category you want to create your link in could not be found!'));
            }
            $link->category_id = $category_id;
            $link->content->container = $this->contentContainer;
        } else if ($this->accessLevel == 1 && $link->content->created_by != Yii::$app->user->id) {
            // access level 1 may edit own links, 2 all links
            throw new HttpException(404, Yii::t('LinklistModule.base', 'You miss the rights to edit this link!'));
        }

        if ($link->load(Yii::$app->request->post()) && $link->validate() && $link->save()) {
            return $this->redirect($this->contentContainer->createUrl('/linklist/linklist/index'));
        }

        return $this->render('editLink', array(
                    'link' => $link,
        ));
    }

    /**
     * Action that deletes a given category.<br />
     * The request has to provide the id of the link to delete in the url parameter 'link_id'.
     * @throws HttpException 404, if the logged in User misses the rights to access this view.
     */
    public function actionDeleteLink()
    {
        $link_id = (int) Yii::$app->request->get('link_id');
        $link = Link::find()->where(array('linklist_link.id' => $link_id))->contentContainer($this->contentContainer)->one();

        if ($link == null) {
            throw new HttpException(404, Yii::t('LinklistModule.base', 'Requested link could not be found.'));
        }
        // access level 1 may delete own links, 2 all links
        else if ($this->accessLevel == 0 || $this->accessLevel == 1 && $link->content->created_by != Yii::$app->user->id) {
            throw new HttpException(404, Yii::t('LinklistModule.base', 'You miss the rights to delete this link!'));
        }

        $link->delete();

        return $this->redirect($this->contentContainer->createUrl('/linklist/linklist/index'));
    }

    /**
     * Space Configuration Action for Admins
     */
    public function actionConfig()
    {
        $form = new ConfigureForm();
        $form->enableDeadLinkValidation = $this->contentContainer->getSetting('enableDeadLinkValidation', 'linklist');
        $form->enableWidget = $this->contentContainer->getSetting('enableWidget', 'linklist');


        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $this->contentContainer->setSetting('enableDeadLinkValidation', $form->enableDeadLinkValidation, 'linklist');
            $this->contentContainer->setSetting('enableWidget', $form->enableWidget, 'linklist');
            return $this->redirect($this->contentContainer->createUrl('/linklist/linklist/config'));
        }

        return $this->render('config', array('model' => $form));
    }

}

?>
