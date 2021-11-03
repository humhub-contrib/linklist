<?php
/**
 * View to edit a link category.
 *
 * @var $link \humhub\modules\linklist\models\Link
 * @var $isCreated bool true if the link is first created, false if an existing link is edited
 *
 * @author Sebastian Stumpf
 *
 */
humhub\modules\linklist\Assets::register($this);

use humhub\modules\ui\form\widgets\ActiveForm;
use yii\helpers\Html;

?>


<div class="panel panel-default">
    <?php if ($link->isNewRecord) : ?>
        <div class="panel-heading"><strong>Create</strong> new link</div>
    <?php else: ?>
        <div class="panel-heading"><strong>Edit</strong> link</div>
    <?php endif; ?>

    <div class="panel-body">
        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group">
            <?= $form->field($link, 'title')->textInput(); ?>
        </div>

        <div class="form-group">
            <?= $form->field($link, 'description')->textarea(); ?>
        </div>

        <div class="form-group">
            <?= $form->field($link, 'href')->textInput(); ?>
        </div>

        <div class="form-group">
            <?= $form->field($link, 'sort_order')->textInput(); ?>
        </div>

        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']); ?>

        <?php $form::end(); ?>
    </div>
</div>
