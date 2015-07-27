<?php

namespace humhub\modules\linklist\widgets;

use Yii;
use humhub\modules\linklist\models\Link;
use humhub\modules\linklist\models\Category;

/**
 * LinklistSidebarWidget displaying a list of links.
 *
 * It is attached to the sidebar of the space/user, if the module is enabled in the settings.
 *
 * @package humhub.modules.linklist.widgets
 * @author Sebastian Stumpf
 */
class Sidebar extends \humhub\components\Widget
{

    public $contentContainer;

    public function run()
    {

        $container = $this->contentContainer;
        if (!$container->getSetting('enableWidget', 'linklist')) {
            return;
        }
        $categoryBuffer = Category::find()->contentContainer($this->contentContainer)->orderBy(['sort_order' => SORT_ASC])->all();
        $categories = array();
        $links = array();
        $render = false;

        foreach ($categoryBuffer as $category) {
            $linkBuffer = Link::find()->where(array('category_id' => $category->id))->orderBy(['sort_order' => SORT_ASC])->all();
            // categories are only displayed in the widget if they contain at least one link
            if (!empty($linkBuffer)) {
                $categories[] = $category;
                $links[$category->id] = $linkBuffer;
                $render = true;
            }
        }

        // if none of the categories contains a link, the linklist widget is not rendered.
        if ($render) {
            return $this->render('linklistPanel', array('container' => $container, 'categories' => $categories, 'links' => $links));
        }
    }

}

?>
