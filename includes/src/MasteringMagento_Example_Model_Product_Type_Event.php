<?php
/**
 * app/code/local/MasteringMagento/Model/Project/Type/Event.php
 *
 * This example code is provided for use with the Mastering Magento video
 * series, by Packt Publishing.
 *
 * @author    Franklin P. Strube <franklin.strube@gmail.com>
 * @category  MasteringMagento
 * @package   Example
 * @copyright Copyright (c) 2012 Packt Publishing (http://packtpub.com)
 */
class MasteringMagento_Example_Model_Product_Type_Event extends Mage_Catalog_Model_Product_Type_Abstract
{
    /**
     * Prepare additional options/information for order item which will be
     * created from this product
     *
     * @param Mage_Catalog_Model_Product $product
     * @return array
     */
    public function getOrderOptions($product = null)
    {
        $optionArr = parent::getOrderOptions($product);
        $buyRequest = $optionArr['info_buyRequest'];

        // Preserve additional options from the quote
        if ( ! isset($optionArr['additional_options']) ) {
            $additionalOptions = $this->getProduct($product)->getCustomOption('additional_options');

            $optionArr['additional_options'] = $additionalOptions ? unserialize($additionalOptions->getValue()) : array();
        }

        return $optionArr;
    }

    /**
     * Prepare product and its configuration to be added to some products list.
     * Perform standard preparation process and add logic specific to Event product type.
     *
     * @param Varien_Object $buyRequest
     * @param Mage_Catalog_Model_Product $product
     * @param string $processMode
     * @return array
     */
    public function _prepareOptions(Varien_Object $buyRequest, $product, $processMode)
    {
        $product = $this->getProduct($product);
        $isStrictProcessMode = $this->_isStrictProcessMode($processMode);

        // Run the parent method to start
        $_options = parent::_prepareOptions($buyRequest, $product, $processMode);

        // Add ticket information to additional options for Magento to display
        $additionalOptions = array();
        if ( $tickets = $buyRequest->getTicket() ) {
            foreach ( $tickets as $ticketId => $data ) {
                $_ticket = Mage::getModel('example/event_ticket')->load($ticketId);
                if ( !$_ticket->getId() ) {
                    $message = Mage::helper('example')->__('Ticket does not exist!');
                    Mage::throwException($message);
                }

                // Add the ticket information to the additional options array
                $additionalOptions[] = array(
                    'label' => $_ticket->getTitle(),
                    'value' => Mage::helper('example')->__('Qty: %s', $data['qty'])
                );
            }
        }
        $product->addCustomOption('additional_options', serialize($additionalOptions));

        // Return the options
        return $_options;
    }

    /**
     * Prepare selected qty for event product's options. Used when updating or editing
     * an item that is in your cart.
     *
     * @param  Mage_Catalog_Model_Product $product
     * @param  Varien_Object $buyRequest
     * @return array
     */
    public function processBuyRequest($product, $buyRequest)
    {
        $options = array();

        if ( $tickets = $buyRequest->getTicket() ) {
            $options['ticket'] = $tickets;
        }

        return $options;
    }

    public function getTickets($product = null)
    {
        $product = $this->getProduct($product);
        //echo"<pre>".$product->getId();print_r($product);
        $collection = Mage::getModel("example/event_ticket")->getCollection()
            ->addFieldToFilter('event_id', $product->getEventId())
            ->addFieldToFilter('product_id', $product->getId())
            ->setOrder('sort_order', 'asc');
          //->addAttributeToFilter('event_id', $product->getEventId());

          //echo $collection->getSelect()->__toString();
            //there should be a Ticket.php file in Model/Resource/Event/ folder, in order to save Ticket data. Otherwise it will not save.

            //there should be a collection.php file in Model/Resource/Event/Ticket/ folder, in order to use "->addFieldToFilter()" function. Otherwise it will not work.
        

        return $collection;
    }

    /**
     * Save Product event information
     *
     * @param Mage_Catalog_Model_Product $product
     * @return MasteringMagento_Example_Model_Product_Type_Event
     */
    public function save($product = null)
    {
        parent::save($product);

        $product = $this->getProduct($product);
        /* @var Mage_Catalog_Model_Product $product */

        if ($eventData = $product->getEventData()) {
            if ( $eventData['ticket'] ) {
                foreach ( $eventData['ticket'] as $ticket ) {
                    // Load the model
                    $ticketModel = Mage::getModel('example/event_ticket')->load($ticket['ticket_id']);
                    unset($ticket['ticket_id']);

                    if ( $ticket['is_delete'] == 1 ) {
                        $ticketModel->delete();
                    } else {
                        unset($ticket['is_delete']);

                        // Set the ticket's event id
                        $ticket['event_id'] = $product->getEventId();
                        $ticket['product_id'] = $product->getId();

                        // Save new data to the ticket
                        $ticketModel->addData($ticket);
                        $ticketModel->save();
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Determines if options wrapper will load on frontend.
     */
    public function hasOptions($product = null)
    {
        return true;
    }
}
