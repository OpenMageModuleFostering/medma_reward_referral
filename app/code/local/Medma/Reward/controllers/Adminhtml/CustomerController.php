<?php
require_once 'Mage/Adminhtml/controllers/CustomerController.php';
class Medma_Reward_Adminhtml_CustomerController extends Mage_Adminhtml_CustomerController
{
	public function sendemailAction()
    	{
		$customerId	= $this->getRequest()->getParam('customerId');
		$cust		= Mage::getModel('customer/customer')->load($customerId, 'entity_id');//print_r($cust);
		$custAttr	= Mage::getModel('customer/customer')->load($customerId)->getData();//print_r($custAttr);exit;
		$custUniqueCode	= $custAttr['customeridentifier1'];
		$discountCode	= $custAttr['discountcode1'];
		$email		= $custAttr['email'];
		if($custUniqueCode!='' && $discountCode!='')
		{
			// MAIL WOULD BE SEND TO CUSTOMER
			$message = "<table align='center' cellpadding='0' cellspacing='0' border='0' style='border:1px solid #bababa;width:800px;background-color:#eeeeee'>
					<tr>
        					<td align='center'  width='100%'>
							<table align='center' cellpadding='0' cellspacing='0' border='0'  width='100%'>
                						<tr>
                    							<td colspan='3' style='padding-left: 30px;'>
                        							<table align='left' cellpadding='0' cellspacing='0' border='0' width='100%'>
											<tr><td height='15px;'></td></tr>
                          								<tr>
                               									<td align='left' style='font-size:14px;font-family:Arial;'> Hello <b>".$nickName.",</b></td>
                          								</tr>
                          								<tr><td height='10px;'></td></tr>
                          								<tr>
                               									<td align='left' style='font-size:14px;font-family:Arial;'>Rewards/Referral Code is:</td>
                          								</tr>
                          								<tr><td height='10px;'></td></tr>
											<tr>
                               									<td align='left' style='font-size:14px;font-family:Arial;'>
                          								</tr>
                          								<tr><td height='5px;'></td></tr>
                          								<tr>
                               									<td align='left' style='font-size:14px;font-family:Arial;'><strong>Customer Unique Code:</strong> ".$custUniqueCode."</td>
                          								</tr>
                          								<tr><td height='5px;'></td></tr>
                          								<tr>
                               									<td align='left' style='font-size:14px;font-family:Arial;'><strong>Discount Code:</strong> ".$discountCode."</td>
               										</tr>
                          								<tr><td height='30px;'></td></tr>
                          								<tr>
                               									<td align='left' style='font-size:14px;font-family:Arial;font-weight:bold'>Best Regards,</td>
                          								</tr>
                          								<tr><td height='5px;'></td></tr>
                          								<tr>
                               									<td align='left' style='font-size:14px;font-family:Arial;'>Rewards Team</td>
                          								</tr>
                      								</table>
                      							</td>
                						</tr>
                						<tr><td height='50px;'></td></tr>
            						</table>
        					</td>
    					</tr>
				</table>";
			//echo $message;exit;
		
			$subject	= 'Customer Unique Code & Discount Code';
			$to		= $email;
			$from_email	= Mage::getStoreConfig('trans_email/ident_general/email');
			
			$header ="MIME-Version: 1.0"."\r\n";
			$header.="Content-type: text/html; charset=iso-8859-1"."\r\n";
			$header.="From: Rewards <".$from_email.">"."\r\n";
			
			mail($to,$subject,$message,$header);

			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reward')->__('Customer Unique Code and Discount Code sended successfully'));
			Mage::getSingleton('adminhtml/session')->setFormData(false);
			
			$this->_redirectReferer('*/*/');
		}
		else
		{
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reward')->__('Customer Unique Code and Discount Code could not be sended'));
			Mage::getSingleton('adminhtml/session')->setFormData(false);
			
			$this->_redirectReferer('*/*/');
		}
		
	}
	
}

