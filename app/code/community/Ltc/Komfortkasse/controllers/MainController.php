<?php
/** 
 * Komfortkasse
 * Magento Plugin - MainController
 * 
 * @version 1.2.1.9-Magento */
class Ltc_Komfortkasse_MainController extends Mage_Core_Controller_Front_Action
{


    /**
     * Init.
     * 
     * @return void
     */
    public function initAction()
    {
        self::getHelper()->init();

    }//end initAction()


    /**
     * Test.
     * 
     * @return void
     */
    public function testAction()
    {
        self::getHelper()->test();

    }//end testAction()


    /**
     * Read orders.
     * 
     * @return void
     */
    public function readordersAction()
    {
        self::getHelper()->readorders();

    }//end readordersAction()


    /**
     * Read refunds.
     * 
     * @return void
     */
    public function readrefundsAction()
    {
        self::getHelper()->readrefunds();

    }//end readrefundsAction()


    /**
     * Update orders.
     * 
     * @return void
     */
    public function updateordersAction()
    {
        self::getHelper()->updateorders();

    }//end updateordersAction()


    /**
     * Update refunds.
     * 
     * @return void
     */
    public function updaterefundsAction()
    {
        self::getHelper()->updaterefunds();

    }//end updaterefundsAction()


    /**
     * Info.
     * 
     * @return void
     */
    public function infoAction()
    {
        self::getHelper()->info();

    }//end infoAction()


    /**
     * Get Helper.
     * 
     * @return void
     */
    protected function getHelper()
    {
        return Mage::helper('Ltc_Komfortkasse');

    }//end getHelper()


}//end class

