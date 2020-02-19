<?php
/**
 * Sidebar widget view to list all categories and their links.
 *
 * @uses $categories an array of the categories to show.
 * @uses $links an array of arrays of the links to show, indicated by the category id.
 *
 * @author Sebastian Stumpf
 */

use \humhub\compat\CHtml;
use humhub\libs\Html;

humhub\modules\linklist\Assets::register($this);
?>
<div class="panel panel-default panel-linklist-widget">
    <div class="panel-heading">
        <strong><?php echo Yii::t('LinklistModule.base', 'Link'); ?></strong> <?php echo Yii::t('LinklistModule.base', 'list'); ?>
    </div>
    <div class="linklist-body">
        <div class="scrollable-content-container">
            <?php foreach ($categories as $category) { ?>
                <div id="linklist-widget-category_<?php echo $category->id; ?>" class="media">
                    <div class="media-heading"><?= Html::encode($category->title); ?></div>
                    <ul class="media-list">
                        <?php foreach ($links[$category->id] as $link): ?>
                            <li id="linklist-widget-link_<?= $link->id; ?>">
                                <?= Html::a(Html::encode($link->title), $link->href, ['target' => '_blank', 'title' => Html::encode($link->description)]); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
