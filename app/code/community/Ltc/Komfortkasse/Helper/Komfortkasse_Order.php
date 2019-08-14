<?php


/**
 * Komfortkasse Order Class
 * in KK, an Order is an Array providing the following members:
 * number, date, email, customer_number, payment_method, amount, currency_code, exchange_rate, language_code, invoice_number, store_id
 * status: data type according to the shop system
 * delivery_ and billing_: _firstname, _lastname, _company, _street, _postcode, _city, _countrycode
 * products: an Array of item numbers
 * @version 1.4.0.1-Magento
 */
$path = Mage::getModuleDir('', 'Ltc_Komfortkasse');
$order_extension = false;
if (file_exists("{$path}/Helper/Komfortkasse_Order_Extension.php") === true) {
    $order_extension = true;
    include_once "{$path}/Helper/Komfortkasse_Order_Extension.php";
}
class Komfortkasse_Order
{

    /**
     * Get open order IDs.
     *
     * @return string all order IDs that are "open" and relevant for transfer to kk
     */
    public static function getOpenIDs()
    {
        $ret = array ();
        
        foreach (Mage::app()->getWebsites() as $website) {
            foreach ($website->getGroups() as $group) {
                $stores = $group->getStores();
                foreach ($stores as $store) {
                    
                    $store_id = $store->getId();
                    $store_id_order = array ();
                    $store_id_order ['store_id'] = $store_id;
                    
                    if (!Komfortkasse_Config::getConfig(Komfortkasse_Config::activate_export, $store_id_order)) {
                        continue;
                    }
                    
                    // PREPAYMENT
                    
                    $openOrders = Komfortkasse_Config::getConfig(Komfortkasse_Config::status_open, $store_id_order);
                    $paymentMethods = Komfortkasse_Config::getConfig(Komfortkasse_Config::payment_methods, $store_id_order);
                    
                    if (!empty($openOrders) && !empty($paymentMethods)) {
                        $openOrders = explode(',', $openOrders);
                        $paymentMethods = explode(',', $paymentMethods);
                        
                        $salesModel = Mage::getModel('sales/order');
                        $salesCollection = $salesModel->getCollection()->addAttributeToFilter('status', array ('in' => $openOrders 
                        ))->addFieldToFilter('store_id', $store_id);
                        
                        foreach ($salesCollection as $order) {
                            try {
                                $method = $order->getPayment()->getMethodInstance()->getCode();
                                if (in_array($method, $paymentMethods, true) === true) {
                                    $orderId = $order->getIncrementId();
                                    $ret [] = $orderId;
                                }
                            } catch ( Exception $e ) {
                            }
                        }
                        
                        // Add all orders with unpaid invoices (in case the invoice is created before shipping).
                        $invoiceModel = Mage::getModel('sales/order_invoice');
                        $invoiceCollection = $invoiceModel->getCollection()->addAttributeToFilter('state', Mage_Sales_Model_Order_Invoice::STATE_OPEN)->addFieldToFilter('store_id', $store_id);
                        foreach ($invoiceCollection as $invoice) {
                            try {
                                $order = $invoice->getOrder();
                                $method = $order->getPayment()->getMethodInstance()->getCode();
                                if (in_array($method, $paymentMethods, true) === true) {
                                    $orderId = $order->getIncrementId();
                                    if (in_array($orderId, $ret) === false) {
                                        $ret [] = $orderId;
                                    }
                                }
                            } catch ( Exception $e ) {
                            }
                        }
                    }
                    
                    // INVOICE
                    
                    $openOrders = Komfortkasse_Config::getConfig(Komfortkasse_Config::status_open_invoice, $store_id_order);
                    $paymentMethods = Komfortkasse_Config::getConfig(Komfortkasse_Config::payment_methods_invoice, $store_id_order);
                    
                    if (!empty($openOrders) && !empty($paymentMethods)) {
                        $openOrders = explode(',', $openOrders);
                        $paymentMethods = explode(',', $paymentMethods);
                        
                        $salesModel = Mage::getModel('sales/order');
                        $salesCollection = $salesModel->getCollection()->addAttributeToFilter('status', array ('in' => $openOrders 
                        ))->addFieldToFilter('store_id', $store_id);
                        
                        foreach ($salesCollection as $order) {
                            try {
                                $method = $order->getPayment()->getMethodInstance()->getCode();
                                if (in_array($method, $paymentMethods, true) === true) {
                                    $orderId = $order->getIncrementId();
                                    $ret [] = $orderId;
                                }
                            } catch ( Exception $e ) {
                            }
                        }
                    }
                    
                    // COD
                    
                    $openOrders = Komfortkasse_Config::getConfig(Komfortkasse_Config::status_open_cod, $store_id_order);
                    $paymentMethods = Komfortkasse_Config::getConfig(Komfortkasse_Config::payment_methods_cod, $store_id_order);
                    
                    if (!empty($openOrders) && !empty($paymentMethods)) {
                        $openOrders = explode(',', $openOrders);
                        $paymentMethods = explode(',', $paymentMethods);

                        $salesModel = Mage::getModel('sales/order');
                        $salesCollection = $salesModel->getCollection()->addAttributeToFilter('status', array ('in' => $openOrders 
                        ))->addFieldToFilter('store_id', $store_id);
                        
                        foreach ($salesCollection as $order) {
                            try {
                                $method = $order->getPayment()->getMethodInstance()->getCode();
                                if (in_array($method, $paymentMethods, true) === true) {
                                    $orderId = $order->getIncrementId();
                                    $ret [] = $orderId;
                                }
                            } catch ( Exception $e ) {
                            }
                        }
                    }
                }
            }
        }
        

        return $ret;
    
    }
    
    // end getOpenIDs()
    

    /**
     * Get refund IDS.
     *
     * @return string all refund IDs that are "open" and relevant for transfer to kk
     */
    public static function getRefundIDs()
    {
        $ret = array ();
        
        foreach (Mage::app()->getWebsites() as $website) {
            foreach ($website->getGroups() as $group) {
                $stores = $group->getStores();
                foreach ($stores as $store) {
                    
                    $store_id = $store->getId();
                    $store_id_order = array ();
                    $store_id_order ['store_id'] = $store_id;
                    
                    if (!Komfortkasse_Config::getConfig(Komfortkasse_Config::activate_export, $store_id_order)) {
                        continue;
                    }
                    
                    $paymentMethods = explode(',', Komfortkasse_Config::getConfig(Komfortkasse_Config::payment_methods, $store_id_order));
                    
                    $cmModel = Mage::getModel("sales/order_creditmemo");
                    $cmCollection = $cmModel->getCollection()->addFieldToFilter('store_id', $store_id);
                    
                    foreach ($cmCollection as $creditMemo) {
                        if ($creditMemo->getTransactionId() == null) {
                            $order = $creditMemo->getOrder();
                            $method = $order->getPayment()->getMethodInstance()->getCode();
                            if (in_array($method, $paymentMethods, true) === true) {
                                $cmId = $creditMemo->getIncrementId();
                                $ret [] = $cmId;
                            }
                        }
                    }
                }
            }
        }
        
        return $ret;
    
    }
    
    // end getRefundIDs()
    

    /**
     * Get order.
     *
     * @param string $number order number
     *       
     * @return array order
     */
    public static function getOrder($number)
    {
        $order = Mage::getModel('sales/order')->loadByIncrementId($number);
        if (empty($number) === true || empty($order) === true || $number != $order->getIncrementId()) {
            return null;
        }
        
        $conf_general = Mage::getStoreConfig('general', $order->getStoreId());
        
        $ret = array ();
        $ret ['number'] = $order->getIncrementId();
        $ret ['status'] = $order->getStatus();
        $ret ['date'] = date('d.m.Y', strtotime($order->getCreatedAtStoreDate()->get(Zend_Date::DATE_MEDIUM)));
        $ret ['email'] = $order->getCustomerEmail();
        $ret ['customer_number'] = $order->getCustomerId();
        $ret ['payment_method'] = $order->getPayment()->getMethodInstance()->getCode();
        $ret ['amount'] = $order->getGrandTotal();
        $ret ['currency_code'] = $order->getOrderCurrencyCode();
        $ret ['exchange_rate'] = $order->getBaseToOrderRate();
        
        // Rechnungsnummer
        $invoiceColl = $order->getInvoiceCollection();
        if ($invoiceColl->getSize() > 0) {
            foreach ($order->getInvoiceCollection() as $invoice) {
                $ret ['invoice_number'] [] = $invoice->getIncrementId();
            }
        }
        
        $shippingAddress = $order->getShippingAddress();
        if ($shippingAddress) {
            $ret ['delivery_firstname'] = utf8_encode($shippingAddress->getFirstname());
            $ret ['delivery_lastname'] = utf8_encode($shippingAddress->getLastname());
            $ret ['delivery_company'] = utf8_encode($shippingAddress->getCompany());
            $ret ['delivery_street'] = utf8_encode($shippingAddress->getStreetFull());
            $ret ['delivery_postcode'] = utf8_encode($shippingAddress->getPostcode());
            $ret ['delivery_city'] = utf8_encode($shippingAddress->getCity());
            $ret ['delivery_countrycode'] = utf8_encode($shippingAddress->getCountryId());
        }
        
        $billingAddress = $order->getBillingAddress();
        if ($billingAddress) {
            $ret ['language_code'] = substr($conf_general ['locale'] ['code'], 0, 2) . '-' . $billingAddress->getCountryId();
            $ret ['billing_firstname'] = utf8_encode($billingAddress->getFirstname());
            $ret ['billing_lastname'] = utf8_encode($billingAddress->getLastname());
            $ret ['billing_company'] = utf8_encode($billingAddress->getCompany());
            $ret ['billing_street'] = utf8_encode($billingAddress->getStreetFull());
            $ret ['billing_postcode'] = utf8_encode($billingAddress->getPostcode());
            $ret ['billing_city'] = utf8_encode($billingAddress->getCity());
            $ret ['billing_countrycode'] = utf8_encode($billingAddress->getCountryId());
        } else {
            $ret ['language_code'] = substr($conf_general ['locale'] ['code'], 0, 2);
        }
        
        foreach ($order->getAllItems() as $itemId => $item) {
            $sku = $item->getSku();
            if ($sku) {
                $ret ['products'] [] = $sku;
            } else {
                $ret ['products'] [] = $item->getName();
            }
        }
        
        $ret ['store_id'] = $order->getStoreId();
        
        if ($order_extension && method_exists('Komfortkasse_Order_Extension', 'extendOrder') === true) {
            $ret = Komfortkasse_Order_Extension::extendOrder($order, $ret);
        }
        
        return $ret;
    
    }
    
    // end getOrder()
    

    /**
     * Get refund.
     *
     * @param string $number refund number
     *       
     * @return array refund
     */
    public static function getRefund($number)
    {
        $resource = Mage::getSingleton('core/resource');
        $id = $resource->getConnection('core_read')->fetchOne('SELECT `entity_id` FROM `' . $resource->getTableName('sales/creditmemo') . "` WHERE `increment_id` = '" . $number . "'");
        
        $creditMemo = Mage::getModel('sales/order_creditmemo')->load($id);
        if (empty($number) === true || empty($creditMemo) === true || $number != $creditMemo->getIncrementId()) {
            return null;
        }
        
        $ret = array ();
        $ret ['number'] = $creditMemo->getOrder()->getIncrementId();
        // Number of the Creditmemo.
        $ret ['customer_number'] = $creditMemo->getIncrementId();
        $ret ['date'] = date('d.m.Y', strtotime($creditMemo->getCreatedAt()));
        $ret ['amount'] = $creditMemo->getGrandTotal();
        
        return $ret;
    
    }
    
    // end getRefund()
    

    /**
     * Update order.
     *
     * @param array $order order
     * @param string $status status
     * @param string $callbackid callback ID
     *       
     * @return void
     */
    public static function updateOrder($order, $status, $callbackid)
    {
        if (!Komfortkasse_Config::getConfig(Komfortkasse_Config::activate_update, $order)) {
            return;
        }
        
        // Hint: PAID and CANCELLED are supported as of now.
        $order = Mage::getModel('sales/order')->loadByIncrementId($order ['number']);
        
        Mage::dispatchEvent('komfortkasse_change_order_status_before', array ('order' => $order,'status' => $status,'callbackid' => $callbackid 
        ));
        
        $stateCollection = Mage::getModel('sales/order_status')->getCollection()->joinStates();
        $stateCollection->addFieldToFilter('main_table.status', array ('like' => $status 
        ));
        $state = $stateCollection->getFirstItem()->getState();
        
        if ($state == 'processing' || $state == 'closed' || $state == 'complete') {
            
            // If there is already an invoice, update the invoice, not the order.
            $invoiceColl = $order->getInvoiceCollection();
            if ($invoiceColl->getSize() > 0) {
                foreach ($order->getInvoiceCollection() as $invoice) {
                    $invoice->pay();
                    $invoice->addComment($callbackid, false, false);
                    self::mysave($invoice);
                }
            } else {
                $payment = $order->getPayment();
                $payment->capture(null);
                
                if ($callbackid) {
                    $payment->setTransactionId($callbackid);
                    $transaction = $payment->addTransaction(Mage_Sales_Model_Order_Payment_Transaction::TYPE_CAPTURE);
                }
            }
            
            $history = $order->addStatusHistoryComment('' . $callbackid, $status);
            $order->save();
        } else if ($state == 'canceled') {
            
            if ($callbackid) {
                $history = $order->addStatusHistoryComment('' . $callbackid, $status);
            }
            if ($order->canCancel()) {
                $order->cancel();
            }
            $order->setStatus($status);
            $order->save();
        } else {
            
            $history = $order->addStatusHistoryComment('' . $callbackid, $status);
            $order->save();
        }
        
        Mage::dispatchEvent('komfortkasse_change_order_status_after', array ('order' => $order,'status' => $status,'callbackid' => $callbackid 
        ));
    
    }
    
    // end updateOrder()
    

    /**
     * Update order.
     *
     * @param string $refundIncrementId Increment ID of refund
     * @param string $status status
     * @param string $callbackid callback ID
     *       
     * @return void
     */
    public static function updateRefund($refundIncrementId, $status, $callbackid)
    {
        $resource = Mage::getSingleton('core/resource');
        $id = $resource->getConnection('core_read')->fetchOne('SELECT `entity_id` FROM `' . $resource->getTableName('sales/creditmemo') . "` WHERE `increment_id` = '" . $refundIncrementId . "'");
        
        $creditMemo = Mage::getModel('sales/order_creditmemo')->load($id);
        
        $store_id = $creditMemo->getStoreId();
        $store_id_order = array ();
        $store_id_order ['store_id'] = $store_id;
        
        if (!Komfortkasse_Config::getConfig(Komfortkasse_Config::activate_update, $store_id_order)) {
            return;
        }
        
        if ($creditMemo->getTransactionId() == null) {
            $creditMemo->setTransactionId($callbackid);
        }
        
        $history = $creditMemo->addComment($status . ' [' . $callbackid . ']', false, false);
        
        $creditMemo->save();
    
    }
    
    // end updateRefund()
    

    /**
     * Call an object's save method
     *
     * @param unknown $object
     *
     * @return void
     */
    private static function mysave($object)
    {
        $object->save();
    
    }


    public static function getInvoicePdfPrepare()
    {
    
    }


    public static function getInvoicePdf($invoiceNumber)
    {
        if ($invoiceNumber && $invoice = Mage::getModel('sales/order_invoice')->loadByIncrementId($invoiceNumber)) {
            $fileName = $invoiceNumber . '.pdf';
            
            $pdfGenerated = false;
            
            // try easy pdf (www.easypdfinvoice.com)
            if (!$pdfGenerated) {
                $pdfProModel = Mage::getModel('pdfpro/order_invoice');
                if ($pdfProModel !== false) {
                    $invoiceData = $pdfProModel->initInvoiceData($invoice);
                    $result = Mage::helper('pdfpro')->initPdf(array ($invoiceData 
                    ));
                    if ($result ['success']) {
                        $content = $result ['content'];
                        $pdfGenerated = true;
                    }
                }
            }
            
            // try Magento Standard
            if (!$pdfGenerated) {
                $pdf = Mage::getModel('sales/order_pdf_invoice')->getPdf(array ($invoice 
                ));
                $content = $pdf->render();
            }
            
            return $content;
        }
    
    }
}//end class
