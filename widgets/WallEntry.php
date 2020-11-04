<?php

namespace humhub\modules\linklist\widgets;

use humhub\modules\content\widgets\stream\WallStreamModuleEntryWidget;
use humhub\modules\linklist\models\Link;

/**
 * WallEntryWidget displaying a links content on the wall.
 *
 * @package humhub.modules.linklist.widgets
 * @author Sebastian Stumpf
 */
class WallEntry extends WallStreamModuleEntryWidget
{
    /**
     * @var Link
     */
    public $model;

    public function renderContent()
    {
        return $this->render('wallEntry', [
            'link' => $this->model
        ]);
    }

    /**
     * @return string
     */
    protected function getIcon()
    {
        return 'link';
    }

    /**
     * @return string a non encoded plain text title (no html allowed) used in the header of the widget
     */
    protected function getTitle()
    {
        return $this->model->title;
    }

}
