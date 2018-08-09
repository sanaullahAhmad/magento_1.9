<?php
class MasteringMagento_Example_Block_Welcome extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('example/welcome.phtml');
    }
}


