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
    <div class="linklist-body">
    	<div class="scrollable-content-container">
	    	<?php foreach($categories as $category) { ?>
	    	<div id="linklist-widget-category_<?php echo $category->id;?>" class="media">
	    		<div class="media-heading"><?php echo $category->title; ?></div>
				<ul class="media-list">
					<?php foreach($links[$category->id] as $link) { ?>
						<li><a id="linklist-widget-link_<?php echo $link->id;?>" href="<?php echo $link->href; ?>" title="<?php echo $link->description; ?>"><?php echo $link->title; ?></a></li>
					<?php } ?>
				</ul>
			</div>
			<?php } ?>
		</div>
    </div>
</div>
