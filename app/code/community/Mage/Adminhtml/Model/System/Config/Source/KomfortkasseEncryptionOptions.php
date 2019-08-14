<?php
class Mage_Adminhtml_Model_System_Config_Source_KomfortkasseEncryptionOptions
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => "openssl", 'label'=>Mage::helper('adminhtml')->__('OpenSSL Encryption (asynchronous)')),
            array('value' => "mcrypt", 'label'=>Mage::helper('adminhtml')->__('MCrypt Encryption (synchronous)')),
            array('value' => "base64", 'label'=>Mage::helper('adminhtml')->__('Base64 Encoding')),
        );
    }

}
