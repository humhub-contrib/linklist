<?php

/**
 * View for the wall entry widget. Displays the content shown on the wall if a link is added.
 *
 * @uses $link the link added to the wall.
 *
 * @author Sebastian Stumpf
 */

use yii\helpers\Html;

?>
<div class="media">
    <div class="pull-left">

    </div>

    <?= Html::a('<i class="fa fa-link"></i>', $link->href, ['target' => '_blank', 'class' => 'pull-left', 'style' => 'font-size: 26px; color: #555 !important;']); ?>

    <div class="media-body">
        <h4 class="media-heading"><?php echo Yii::t('LinklistModule.base', 'Added a new link %link% to category "%category%".', array('%link%' => Html::a(Html::encode($link->title), $link->href, array('target' => '_blank')), '%category%' => Html::encode($link->category->title))); ?></h4>
        <?php
        if ($link->description == null || $link->description == "") {
            echo "<em>(" . Yii::t('LinklistModule.base', 'No description available.') . ")</em>";
        } else {
            echo Html::encode($link->description);
        }
        ?>
    </div>
</div>