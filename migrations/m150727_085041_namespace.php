<?php

use yii\db\Schema;
use humhub\components\Migration;
use humhub\modules\linklist\models\Link;
use humhub\modules\linklist\models\Category;

class m150727_085041_namespace extends Migration
{

    public function up()
    {
        $this->renameClass('Link', Link::className());
        $this->renameClass('Category', Category::className());
    }

    public function down()
    {
        echo "m150727_085041_namespace cannot be reverted.\n";

        return false;
    }

    /*
      // Use safeUp/safeDown to run migration code within a transaction
      public function safeUp()
      {
      }

      public function safeDown()
      {
      }
     */
}
