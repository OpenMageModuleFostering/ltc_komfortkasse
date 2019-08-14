<?php

/** 
 * Komfortkasse
 * Config Class
 * @version 1.2.1.8-Magento */
class Komfortkasse_Config
{
    const activate_export  = 'payment/komfortkasse/activate_export';
    const activate_update  = 'payment/komfortkasse/activate_update';
    const payment_methods  = 'payment/komfortkasse/payment_methods';
    const status_open      = 'payment/komfortkasse/status_open';
    const status_paid      = 'payment/komfortkasse/status_paid';
    const status_cancelled = 'payment/komfortkasse/status_cancelled';
    const encryption       = 'payment/komfortkasse/encryption';
    const accesscode       = 'payment/komfortkasse/accesscode';
    const apikey           = 'payment/komfortkasse/apikey';
    const publickey        = 'payment/komfortkasse/publickey';
    const privatekey       = 'payment/komfortkasse/privatekey';


    /**
     * Set Config. 
     * 
     * @param string $constantKey Constant Key
     * @param string $value       Value
     * 
     * @return void
     */
    public static function setConfig($constantKey, $value)
    {
        Mage::getConfig()->saveConfig($constantKey, $value);
        Mage::getConfig()->reinit();
        Mage::app()->reinitStores();

    }//end setConfig()


    /**
     * Get Config. 
     * 
     * @param string $constantKey Constant Key
     * 
     * @return mixed
     */
    public static function getConfig($constantKey)
    {
        $value = Mage::getStoreConfig($constantKey);

        return $value;

    }//end getConfig()


    /**
     * Get Request Parameter. 
     * 
     * @param string $key Key
     * 
     * @return string
     */
    public static function getRequestParameter($key)
    {
        return urldecode(Mage::app()->getRequest()->getParam($key));

    }//end getRequestParameter()


    /**
     * Get Magento Version. 
     * 
     * @return string
     */
    public static function getVersion()
    {
        return Mage::getVersion();

    }//end getVersion()


}//end class