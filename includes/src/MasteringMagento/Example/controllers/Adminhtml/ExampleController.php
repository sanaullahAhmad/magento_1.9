<?php
class MasteringMagento_Example_Adminhtml_ExampleController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        //echo "Wa alaikum salam, I am in the index";
        $event = Mage::getModel('example/event');
        $event->setName('Test Event')->save();

        Mage::getSingleton('adminhtml/session')->addSuccess(
            'Event saved. ID = ' . $event->getId()
        );
        $this->loadLayout();
       return $this->renderLayout();
    }
}