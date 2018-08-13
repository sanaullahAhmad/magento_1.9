<?php
class MasteringMagento_Example_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        //echo "Wa alaikum salam, I am in the index";
        $this->loadLayout();
        return $this->renderLayout();
    }
}