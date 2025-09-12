<?php

namespace humhub\modules\linklist\controllers;

use humhub\components\access\ControllerAccess;
use humhub\modules\admin\permissions\ManageSpaces;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\content\components\ContentContainerControllerAccess;
use humhub\modules\linklist\models\Category;
use humhub\modules\linklist\models\Link;
use humhub\modules\linklist\models\ConfigureForm;
use humhub\modules\linklist\Module;
use humhub\modules\user\models\User;
use humhub\modules\space\models\Space;
use Yii;
use yii\web\HttpException;

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

    /**
     * @inheritdoc
     */
    protected function getAccessRules()
    {
        return [
            [ContentContainerControllerAccess::RULE_USER_GROUP_ONLY => [Space::USERGROUP_MEMBER, User::USERGROUP_SELF]],
            [ControllerAccess::RULE_PERMISSION => [ManageSpaces::class], 'actions' => ['config']],
        ];
    }

    /**
     * Automatically loads the underlying contentContainer (User/Space) by using
     * the uguid/sguid request parameter
     */
    public function init()
    {
        parent::init();
        $this->accessLevel = $this->getAccessLevel();
    }

    /**
     * Get the acces level to the linklist of the currently logged in user.
     * @return number 0 -> no write access / 1 -> create links and edit own links / 2 -> full write access
     */
    private function getAccessLevel()
    {
        if ($this->contentContainer instanceof \humhub\modules\user\models\User) {
            return $this->contentContainer->id == Yii::$app->user->id ? 2 : 0;
        } elseif ($this->contentContainer instanceof \humhub\modules\space\models\Space) {
            return $this->contentContainer->can(new \humhub\modules\post\permissions\CreatePost()) ? 2 : 1;
        }
    }

    /**
     * Action that renders the list view.
     * @see views/linklist/showLinklist.php
     */
    public function actionIndex()
    {
        $categories = Category::find()
            ->contentContainer($this->contentContainer)
            ->readable()
            ->orderBy(['sort_order' => SORT_ASC])
            ->all();

        $links = [];

        foreach ($categories as $category) {
            $links[$category->id] = Link::find()
                ->where(['category_id' => $category->id])
                ->readable()
                ->orderBy(['sort_order' => SORT_ASC])
                ->all();
        }

        return $this->render('index', [
            'contentContainer' => $this->contentContainer,
            'categories' => $categories,
            'links' => $links,
            'accessLevel' => $this->accessLevel,
        ]);
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
        $category = Category::find()
            ->where(['linklist_category.id' => $category_id])
            ->contentContainer($this->contentContainer)
            ->readable()
            ->one();

        if ($category == null) {
            $category = new Category();
            $category->content->container = $this->contentContainer;
        }

        if ($category->load(Yii::$app->request->post()) && $category->validate() && $category->save()) {
            $this->redirect($this->contentContainer->createUrl('/linklist/linklist/index'));
        }
        return $this->render('editCategory', ['category' => $category]);
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
        $category = Category::find()
            ->where(['linklist_category.id' => $category_id])
            ->contentContainer($this->contentContainer)
            ->readable()
            ->one();

        if ($category == null) {
            throw new HttpException(404, Yii::t('LinklistModule.base', 'Requested category could not be found.'));
        }

        if ($category->delete()) {
            $this->view->success(Yii::t('LinklistModule.base', 'Deleted'));
        }

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

        $link = Link::find()
            ->where(['linklist_link.id' => $link_id])
            ->contentContainer($this->contentContainer)
            ->readable()
            ->one();

        // access level 0 may neither create nor edit
        if ($this->accessLevel == 0) {
            throw new HttpException(404, Yii::t('LinklistModule.base', 'You miss the rights to add/edit links!'));
        } elseif ($link == null) {
            // access level 1 + 2 may create
            $link = new Link();
            $categoryExists = Category::find()
                ->where(['linklist_category.id' => $category_id])
                ->contentContainer($this->contentContainer)
                ->readable()
                ->exists();
            if (!$categoryExists) {
                throw new HttpException(404, Yii::t('LinklistModule.base', 'The category you want to create your link in could not be found!'));
            }
            $link->category_id = $category_id;
            $link->content->container = $this->contentContainer;
        } elseif ($this->accessLevel == 1 && $link->content->created_by != Yii::$app->user->id) {
            // access level 1 may edit own links, 2 all links
            throw new HttpException(404, Yii::t('LinklistModule.base', 'You miss the rights to edit this link!'));
        }

        if ($link->load(Yii::$app->request->post()) && $link->validate() && $link->save()) {
            return $this->redirect($this->contentContainer->createUrl('/linklist/linklist/index'));
        }

        return $this->render('editLink', ['link' => $link]);
    }

    /**
     * Action that deletes a given category.<br />
     * The request has to provide the id of the link to delete in the url parameter 'link_id'.
     * @throws HttpException 404, if the logged in User misses the rights to access this view.
     */
    public function actionDeleteLink()
    {
        $link_id = (int) Yii::$app->request->get('link_id');
        $link = Link::find()
            ->where(['linklist_link.id' => $link_id])
            ->contentContainer($this->contentContainer)
            ->readable()
            ->one();

        if ($link == null) {
            throw new HttpException(404, Yii::t('LinklistModule.base', 'Requested link could not be found.'));
        }
        // access level 1 may delete own links, 2 all links
        elseif ($this->accessLevel == 0 || $this->accessLevel == 1 && $link->content->created_by != Yii::$app->user->id) {
            throw new HttpException(404, Yii::t('LinklistModule.base', 'You miss the rights to delete this link!'));
        }

        if ($link->delete()) {
            $this->view->success(Yii::t('LinklistModule.base', 'Deleted'));
        }

        return $this->redirect($this->contentContainer->createUrl('/linklist/linklist/index'));
    }

    /**
     * Space Configuration Action for Admins
     */
    public function actionConfig()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('linklist');

        $settings = $module->settings->contentContainer($this->contentContainer);

        $form = new ConfigureForm();
        $form->enableDeadLinkValidation = $settings->get('enableDeadLinkValidation');
        $form->enableWidget = $settings->get('enableWidget');


        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $settings->set('enableDeadLinkValidation', $form->enableDeadLinkValidation);
            $settings->set('enableWidget', $form->enableWidget);
            $this->view->saved();

            return $this->redirect($this->contentContainer->createUrl('/linklist/linklist/config'));
        }

        return $this->render('config', ['model' => $form]);
    }

}
