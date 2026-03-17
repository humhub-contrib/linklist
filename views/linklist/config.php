<?php

/**
 * Linklist configuration view.
 * @var $model ConfigureForm
 * @author Sebastian Stumpf
 *
 */

use humhub\modules\linklist\assets\Assets;
use humhub\modules\linklist\models\ConfigureForm;
use humhub\widgets\bootstrap\Button;
use humhub\widgets\form\ActiveForm;

Assets::register($this);
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('LinklistModule.base', 'Linklist Module Configuration'); ?>
    </div>
    <div class="panel-body">
        <p><?= Yii::t('LinklistModule.base', 'You can enable the extended validation of links for a space or user.'); ?></p>
        <br/>

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'enableDeadLinkValidation')->checkbox(); ?>
        <?= $form->field($model, 'enableWidget')->checkbox(); ?>

        <hr>
        <?= Button::save()->submit() ?>
        <?php $form::end(); ?>
    </div>
</div>
