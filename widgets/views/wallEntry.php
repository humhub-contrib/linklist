<?php 
/**
 * View for the wall entry widget. Displays the content shown on the wall if a link is added.
 * 
 * @uses $link the link added to the wall.
 * 
 * @author Sebastian Stumpf
 */
?>

<div class="panel panel-default">
    <div class="panel-body">

        <?php $this->beginContent('application.modules_core.wall.views.wallLayout', array('object' => $link)); ?>

        <div class="media">
            <div class="pull-left">

            </div>
            <a class="pull-left" href="<?php echo $link->href; ?>" target="_blank" style="font-size: 26px; color: #555 !important;">
                <i class="fa fa-link"></i>
                </a>

            <div class="media-body">
                <h4 class="media-heading"><?php echo Yii::t('LinkListModule.base', 'Added a new link %link% to category "%category%".', array('%link%' => HHtml::link(CHtml::encode($link->title), $link->href, array('target' => '_blank')), '%category%' => CHtml::encode($link->category->title))); ?></h4>
                <?php
                if ($link->description == null || $link->description == "") {
                    echo "<em>(". Yii::t('LinkListModule.base', 'No description available.') .")</em>";
                } else {
                    echo CHtml::encode($link->description);
                }
                ?>
            </div>
        </div>



        <?php $this->endContent(); ?>


    </div>
</div>