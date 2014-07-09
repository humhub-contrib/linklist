<?php

class LinkWallEntryWidget extends HWidget {

    public $link;

    public function run() {

        $this->render('wallEntry', array(
            'link' => $this->link,
        ));
    }

}

?>