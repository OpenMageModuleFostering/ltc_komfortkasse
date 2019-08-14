<?php

/**
 * Komfortkasse
 * Magento2 Plugin - Observer Class
 *
 * @version 1.4.0.1-Magento2 */
class Ltc_Komfortkasse_Model_Observer
{

    private function getRegName(\Magento\Framework\Event\Observer $observer)
    {
        $id = $observer->getOrder()->getIncrementId();
        if ($id) {
            $regName = 'komfortkasse_order_status_'.$id;
            return $regName;
        }

    }//end getRegName()


    public function noteNewOrder(\Magento\Framework\Event\Observer $observer)
    {
        $regName = self::getRegName($observer);
        if ($regName) {
            Mage::register($regName, '_new');
        }

    }//end noteNewOrder()



    public function noteOrderStatus(\Magento\Framework\Event\Observer $observer)
    {
        $regName = self::getRegName($observer);
        if ($regName && !Mage::registry($regName)) {
            Mage::register($regName, $observer->getOrder()->getStatus());
        }

    }//end noteOrderStatus()


    public function checkOrderStatus(\Magento\Framework\Event\Observer $observer)
    {
        $regName     = self::getRegName($observer);
        $orderStatus = Mage::registry($regName);
        if ($regName && $orderStatus) {
            if ($orderStatus != $observer->getOrder()->getStatus()) {
                $helper = Mage::helper('Ltc_Komfortkasse');
                $helper->notifyorder($observer->getOrder()->getIncrementId());
            }

            Mage::unregister($regName);
        }

    }//end checkOrderStatus()


}//end class