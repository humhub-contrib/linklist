<?php
/**
 * Sidebar widget view to list all categories and their links.
 *
 * @uses $categories an array of the categories to show.
 * @uses $links an array of arrays of the links to show, indicated by the category id.
 *
 * @author Sebastian Stumpf
 */

use humhub\helpers\Html;
use humhub\modules\linklist\assets\Assets;
use humhub\modules\linklist\models\Category;
use humhub\modules\linklist\models\Link as LinkModel;
use humhub\widgets\bootstrap\Link;

/* @var Category[] $categories */
/* @var LinkModel[] $links */

Assets::register($this);
?>
<div class="panel panel-default panel-linklist-widget">
    <div class="panel-heading">
        <?= Yii::t('LinklistModule.base', '<strong>Link</strong> list') ?>
    </div>
    <div class="linklist-body">
        <div class="scrollable-content-container">
            <?php foreach ($categories as $category) : ?>
                <div id="linklist-widget-category_<?= $category->id; ?>">
                    <h5 class="mt-3 px-3"><?= Html::encode($category->title) ?></h5>
                    <div class="hh-list">
                        <?php foreach ($links[$category->id] as $link): ?>
                            <div id="linklist-widget-link_<?= $link->id ?>">
                                <?= Link::to(Html::encode($link->title), $link->href)
                                        ->tooltip(Html::encode($link->description))
                                        ->blank() ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
