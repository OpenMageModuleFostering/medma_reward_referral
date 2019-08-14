<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Customer edit block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
include_once "app/code/core/Mage/Adminhtml/Block/Customer/Edit.php";
class Medma_Reward_Block_Adminhtml_Customer_Edit extends Mage_Adminhtml_Block_Customer_Edit
{
    public function __construct()
    {//print_r($_POST);exit;
        $this->_objectId = 'id';
        $this->_controller = 'customer';

        if ($this->getCustomerId() &&
            Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/create')) {
            $this->_addButton('order', array(
                'label' => Mage::helper('customer')->__('Create Order'),
                'onclick' => 'setLocation(\'' . $this->getCreateOrderUrl() . '\')',
                'class' => 'add',
            ), 0);
        }

	$this->_addButton('sendcode', array(
                'label' => Mage::helper('customer')->__('Send Code'),
                'onclick' => 'setLocation(\'' . $this->getCreateSendUrl($this->getCustomerId()) . '\')',
                'class' => 'add',
            ), 0);

        parent::__construct();
	
        $this->_updateButton('save', 'label', Mage::helper('customer')->__('Save Customer'));
        $this->_updateButton('delete', 'label', Mage::helper('customer')->__('Delete Customer'));

        if (Mage::registry('current_customer')->isReadonly()) {
            $this->_removeButton('save');
            $this->_removeButton('reset');
        }

        if (!Mage::registry('current_customer')->isDeleteable()) {
            $this->_removeButton('delete');
        }
    }

    public function getCreateOrderUrl()
    {
        return $this->getUrl('*/sales_order_create/start', array('customer_id' => $this->getCustomerId()));
    }

    public function getCreateSendUrl($custId)
    {
	$get_key = Mage::getSingleton('adminhtml/url')->getSecretKey("adminhtml_customer","sendemail");
	
        return Mage::getUrl("reward/adminhtml_customer/sendemail/key/$get_key/customerId/$custId");
    }

    public function getCustomerId()
    {
        return Mage::registry('current_customer')->getId();
    }

    public function getHeaderText()
    {
        if (Mage::registry('current_customer')->getId()) {
            return $this->htmlEscape(Mage::registry('current_customer')->getName());
        }
        else {
            return Mage::helper('customer')->__('New Customer');
        }
    }

    /**
     * Prepare form html. Add block for configurable product modification interface
     *
     * @return string
     */
    public function getFormHtml()
    {
        $html = parent::getFormHtml();
        $html .= $this->getLayout()->createBlock('adminhtml/catalog_product_composite_configure')->toHtml();
        return $html;
    }

    public function getValidationUrl()
    {
        return $this->getUrl('*/*/validate', array('_current'=>true));
    }

    protected function _prepareLayout()
    {
        if (!Mage::registry('current_customer')->isReadonly()) {
            $this->_addButton('save_and_continue', array(
                'label'     => Mage::helper('customer')->__('Save and Continue Edit'),
                'onclick'   => 'saveAndContinueEdit(\''.$this->_getSaveAndContinueUrl().'\')',
                'class'     => 'save'
            ), 10);
        }

        return parent::_prepareLayout();
    }

    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', array(
            '_current'  => true,
            'back'      => 'edit',
            'tab'       => '{{tab_id}}'
        ));
    }
}
