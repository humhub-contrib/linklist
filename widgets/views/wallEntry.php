<div class="panel panel-default">
    <div class="panel-body">

        <?php $this->beginContent('application.modules_core.wall.views.wallLayout', array('object' => $link)); ?>

            <?php echo Yii::t('LinkListModule.base', 'Added a new link '. HHtml::link($link->title, $link->href). ' to category "'. $link->category->title.'".'); ?> <br />
            <small><?php echo $link->description; ?></small>

        <?php $this->endContent(); ?>


    </div>
</div>