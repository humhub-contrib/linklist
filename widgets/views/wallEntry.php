<?php

/**
 * View for the wall entry widget. Displays the content shown on the wall if a link is added.
 *
 * @uses $link the link added to the wall.
 *
 * @author Sebastian Stumpf
 */

use humhub\modules\linklist\models\Link;
use yii\helpers\Html;

/* @var $link Link */
?>
<div class="d-flex">
    <div class="flex-grow-1">
        <h4 class="mt-0"><?= Yii::t('LinklistModule.base', 'Added a new link %link% to category "%category%".', [
                '%link%' => Html::a(Html::encode($link->title), $link->href, ['target' => '_blank']),
                '%category%' => Html::encode($link->category->title),
        ]) ?></h4>
        <?php
        if ($link->description == null || $link->description == '') {
            echo '<em>(' . Yii::t('LinklistModule.base', 'No description available.') . ')</em>';
        } else {
            echo Html::encode($link->description);
        }
        ?>
    </div>
</div>
