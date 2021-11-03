<?php

/**
 * Linklist configuration view.
 * @var $model ConfigureForm
 * @author Sebastian Stumpf
 *
 */

use humhub\modules\linklist\models\ConfigureForm;
use humhub\modules\ui\form\widgets\ActiveForm;
use yii\helpers\Html;

humhub\modules\linklist\Assets::register($this);

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
        <?= Html::submitButton(Yii::t('LinklistModule.base', 'Save'), ['class' => 'btn btn-primary']); ?>
        <?php $form::end(); ?>
    </div>
</div>
