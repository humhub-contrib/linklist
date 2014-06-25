<?php

/**
 * LinklistSidebarWidget displaying a list of links.
 *
 * It is attached to the sidebar.
 *
 * @package humhub.modules.linklist.widgets
 * @author Sebastian Stumpf
 */
class LinklistSidebarWidget extends HWidget {
	public function run() {
		$this->render ( 'linklistPanel', array () );
	}
}

?>
