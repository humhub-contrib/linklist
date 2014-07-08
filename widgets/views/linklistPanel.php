<?php
/**
 * View File for the SpacelinksSidebarWidget
 *
 *
 * @package humhub.modules.spacelinks.widgets.views
 * @author Sebastian Stumpf
 */
?>
<div class="panel panel-default panel-linklist-widget">
    <div class="panel-heading"><strong><?php echo Yii::t('SpacelinksModule.base', 'Link'); ?></strong> <?php echo Yii::t('SpacelinksModule.base', 'list'); ?></div>
    <div id="spacelinksContent" class="panel-body">
    	<?php foreach($categories as $category) { ?>
    	<div id="category-widget-entry_<?php echo $category->id;?>" class="media">
    		<div class="media-heading"><?php echo $category->title; ?></div>
			<div class="media-body">
				<?php foreach($links[$category->id] as $link) { ?>
					<a id="link-widget-entry_<?php echo $link->id;?>" href="<?php echo $link->href; ?>" title="<?php echo $link->description; ?>"><?php echo $link->title; ?></a><br />
				<?php } ?>
			</div>
		</div>
		<?php } ?>
    </div>
</div>
