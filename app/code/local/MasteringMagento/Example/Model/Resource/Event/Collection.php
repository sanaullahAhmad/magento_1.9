<?php
class MasteringMagento_Example_Model_Resource_Event_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        // Indicates which table will use this collection
        //die('collection');
        $this->_init('example/event'); 
    }
}