<?php

/**
 * View to list all categories and their links.
 *
 * @uses $categories an array of the categories to show.
 * @uses $links an array of arrays of the links to show, indicated by the category id.
 * @uses $accesslevel the access level of the user currently logged in.
 *
 * @author Sebastian Stumpf
 */

use humhub\modules\comment\widgets\CommentLink;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\widgets\ContentObjectLinks;
use humhub\modules\like\widgets\LikeLink;
use humhub\modules\linklist\models\Category;
use humhub\modules\linklist\models\Link;
use humhub\widgets\Button;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var ContentContainerActiveRecord $contentContainer */
/* @var Category[] $categories */
/* @var Link[] $links */
/* @var int $accessLevel */

humhub\modules\linklist\Assets::register($this);
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div id="linklist-empty-txt" <?php if (empty($categories)) {
            echo 'style="visibility:visible; display:block"';
        }
        ?>>
            <?= Yii::t('LinklistModule.base', 'There have been no links or categories added to this space yet.') ?> <i
                    class="fa fa-frown-o"></i>
        </div>

        <div class="linklist-categories">
            <?php foreach ($categories as $category) { ?>
                <div id="linklist-category_<?= $category->id ?>"
                     class="panel panel-default panel-linklist-category" data-id="<?= $category->id ?>">
                    <div class="panel-heading">
                        <div class="heading">
                            <?= Html::encode($category->title); ?>
                            <?php if ($accessLevel != 0) { ?>
                                <div class="linklist-edit-controls linklist-editable">
                                    <?php
                                    if ($accessLevel == 2) {
                                        echo Html::a(
                                            Html::tag('i', '', ['class' => ['fa', 'fa-trash-o']]),
                                            Url::to($contentContainer->createUrl("/linklist/linklist/delete-category", ['category_id' => $category->id])), [
                                                'class' => 'deleteButton btn btn-xs btn-danger',
                                                'title' => Yii::t('LinklistModule.base', 'Delete category'),
                                                'data' => [
                                                    'category_id' => $category->id,
                                                    'action-click' => 'linklist.removeCategory',
                                                    'action-confirm-header' => Yii::t('LinklistModule.base', '<strong>Confirm</strong> category deleting'),
                                                    'action-confirm' => Yii::t('LinklistModule.base', 'Do you really want to delete this category? All connected links will be lost!'),
                                                    'action-confirm-text' => Yii::t('LinklistModule.base', 'Delete'),
                                                    'action-cancel-text' => Yii::t('LinklistModule.base', 'Cancel'),
                                                ],
                                            ]
                                        );
                                        echo Button::primary()->icon('pencil')->xs()
                                            ->title(Yii::t('LinklistModule.base', 'Edit Category'))
                                            ->link($contentContainer->createUrl('/linklist/linklist/edit-category', [
                                                'category_id' => $category->id
                                            ])) . ' ';
                                    }
                                    // all users may add a link to an existing category
                                    echo Html::a('<i class="fa fa-plus" style="font-size: 12px;"></i> ' . Yii::t('LinklistModule.base', 'Add link'), $contentContainer->createUrl('/linklist/linklist/edit-link', ['link_id' => -1, 'category_id' => $category->id]), ['title' => 'Add Link', 'class' => 'btn btn-xs btn-info']);
                                    ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="media">
                            <?php if (!($category->description == NULL || $category->description == "")) { ?>
                                <div class="media-heading"><?= Html::encode($category->description); ?></div>
                            <?php } ?>
                            <div class="media-body">
                                <ul class="linklist-links">
                                    <?php foreach ($links[$category->id] as $link) { ?>
                                        <li class="linklist-link" id="linklist-link_<?= $link->id; ?>"
                                            data-id="<?= $link->id; ?>">
                                            <?= Html::a(
                                                '<span class="title">' . Html::encode($link->title) . '</span>' .
                                                '<div class="link-description">' . Html::encode($link->description) . '</div>',
                                                $link->href,
                                                ['target' => '_blank']
                                            ); ?>
                                            <div class="linklist-interaction-controls">
                                                <?= ContentObjectLinks::widget([
                                                    'object' => $link,
                                                    'widgetParams' => [CommentLink::class => ['mode' => CommentLink::MODE_POPUP]],
                                                    'widgetOptions' => [
                                                        CommentLink::class => ['sortOrder' => 100],
                                                        LikeLink::class => ['sortOrder' => 200],
                                                    ],
                                                    'seperator' => '&middot;',
                                                ]); ?>
                                            </div>
                                            <?php // all admins and users that created the link may edit or delete it  ?>
                                            <?php if ($accessLevel == 2 || $accessLevel == 1 && $link->content->created_by == Yii::$app->user->id) { ?>
                                                <div class="linklist-edit-controls linklist-editable">
                                                    <?= Html::a(
                                                        Html::tag('i', '', ['class' => ['fa', 'fa-trash-o']]),
                                                        Url::to($contentContainer->createUrl("/linklist/linklist/delete-link", array('category_id' => $category->id, 'link_id' => $link->id))), [
                                                            'class' => 'deleteButton btn btn-xs btn-danger',
                                                            'title' => Yii::t('LinklistModule.base', 'Delete link'),
                                                            'data' => [
                                                                'link_id' => $link->id,
                                                                'category_id' => $category->id,
                                                                'action-click' => 'linklist.removeLink',
                                                                'action-confirm-header' => Yii::t('LinklistModule.base', '<strong>Confirm</strong> link deleting'),
                                                                'action-confirm' => Yii::t('LinklistModule.base', 'Do you really want to delete this link?'),
                                                                'action-confirm-text' => Yii::t('LinklistModule.base', 'Delete'),
                                                                'action-cancel-text' => Yii::t('LinklistModule.base', 'Cancel'),
                                                            ],
                                                        ]
                                                    ); ?>
                                                    <?= Button::primary()->icon('pencil')->xs()
                                                        ->title(Yii::t('LinklistModule.base', 'Edit Link'))
                                                        ->link($contentContainer->createUrl('/linklist/linklist/edit-link', [
                                                            'link_id' => $link->id,
                                                            'category_id' => $category->id
                                                    ])); ?>
                                                </div>
                                            <?php } ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php if ($accessLevel != 0) { ?>
            <?php if ($accessLevel == 2) { ?>
                <div class="linklist-add-category linklist-editable">
                    <?= Html::a(Yii::t('LinklistModule.base', 'Add Category'), $contentContainer->createUrl('/linklist/linklist/edit-category', ['category_id' => -1]), ['class' => 'btn btn-primary']); ?>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>
