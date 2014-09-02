<?php

class m140708_155237_initial extends EDbMigration
{

    public function up()
    {
        
        // Because uninstall was not available
        try {
            $this->createTable('linklist_category', array(
                'id' => 'pk',
                'title' => 'text DEFAULT NULL',
                'description' => 'text DEFAULT NULL',
                'sort_order' => 'int(11) DEFAULT NULL',
                    ), '');

            $this->createTable('linklist_link', array(
                'id' => 'pk',
                'category_id' => 'int(11) NOT NULL',
                'href' => 'text DEFAULT NULL',
                'title' => 'text DEFAULT NULL',
                'description' => 'text DEFAULT NULL',
                'sort_order' => 'int(11) NOT NULL',
                    ), '');
        } catch (Exception $ex) {
            
        }
    }

    public function down()
    {
        echo "m140708_155237_initial does not support migration down.\n";
        return false;
    }

    /*
      // Use safeUp/safeDown to do migration with transaction
      public function safeUp()
      {
      }

      public function safeDown()
      {
      }
     */
}
