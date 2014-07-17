<?php
/**
 * WallEntryWidget displaying a links content on the wall.
 *
 * @package humhub.modules.linklist.widgets
 * @author Sebastian Stumpf
 */
class LinkWallEntryWidget extends HWidget {

    public $link;

    public function run() {
        $this->render('wallEntry', array(
            'link' => $this->link
        ));
    }

}

?>