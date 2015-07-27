<?php

namespace humhub\modules\linklist\widgets;

/**
 * WallEntryWidget displaying a links content on the wall.
 *
 * @package humhub.modules.linklist.widgets
 * @author Sebastian Stumpf
 */
class WallEntry extends \humhub\components\Widget
{

    public $link;

    public function run()
    {
        return $this->render('wallEntry', array(
            'link' => $this->link
        ));
    }

}

?>