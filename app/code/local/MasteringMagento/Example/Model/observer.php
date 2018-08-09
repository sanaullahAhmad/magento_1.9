<?php
class MasteringMagento_Example_Model_Observer
{
    public function controllerActionPredispatch($observer)
    {
        //die('running observer');
        $controllerAction = $observer->getEvent()->getControllerAction();
        Mage::log($controllerAction->getRequest()->getParams());
    }
}