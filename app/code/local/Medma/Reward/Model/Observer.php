<?php

class Medma_Reward_Model_Observer {

	public function sendRedeemedAmount($observer) {
	
		$items        = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
		$totalPrice = 0;
		foreach($items as $item)
		{
			$totalPrice += $item->getRowTotal();
		}
		$creditPercent	= Mage::getStoreConfig('reward/reward/credit');
		$redemptionAmt	= ($totalPrice*$creditPercent)/100;
		$customerId	= Mage::getSingleton('customer/session')->getId();
		$customerCode	= Mage::getSingleton('customer/session')->getCustomer()->getBusinesslicensepath();

		$customerReferral = Mage::getModel('customer/customer');
		$customerReferral = $customerReferral->getCollection()

		->addFieldToFilter('customeridentifier1',array($customerCode))

		->getData();
		$referralCustomer	= $customerReferral[0]['entity_id'];
		$referralCustomerEmail	= $customerReferral[0]['email'];

		$insertData['customer_id']	= $referralCustomer;
		$insertData['redemption_amount']	= $redemptionAmt;
		$model		= Mage::getModel('reward/reward');
		$model->setData($insertData)->setId();
		$model->save();

		$customerDetails= Mage::getSingleton('customer/session')->getCustomer()->getData();
		$customerFirstName	= $customerDetails['firstname'];
		$customerLastName	= $customerDetails['lastname'];
		$customerName		= $customerFirstName.' '.$customerLastName;

		$message = "<table align='center' cellpadding='0' cellspacing='0' border='0' style='border:1px solid #bababa;width:800px;background-color:#eeeeee'>
					<tr>
        					<td align='center'  width='100%'>
							<table align='center' cellpadding='0' cellspacing='0' border='0'  width='100%'>
                						<tr>
                    							<td colspan='3' style='padding-left: 30px;'>
                        							<table align='left' cellpadding='0' cellspacing='0' border='0' width='100%'>
											<tr><td height='15px;'></td></tr>
                          								<tr>
                               									<td align='left' style='font-size:14px;font-family:Arial;'> Hello $customerName<b>,</b></td>
                          								</tr>
                          								<tr><td height='10px;'></td></tr>
                          								<tr>
                               									<td align='left' style='font-size:14px;font-family:Arial;'>Kindly login to your account to get the information about credited amount.</td>
                          								</tr>
                          								<tr><td height='10px;'></td></tr>
											<tr>
                               									<td align='left' style='font-size:14px;font-family:Arial;'>
                          								</tr>
                          								<tr><td height='5px;'></td></tr>
                          								
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
		
			$subject	= 'Amount Credited';
			$to		= $referralCustomerEmail;
			$from_email	= Mage::getStoreConfig('trans_email/ident_general/email');
			
			$header ="MIME-Version: 1.0"."\r\n";
			$header.="Content-type: text/html; charset=iso-8859-1"."\r\n";
			$header.="From: <".$from_email.">"."\r\n";
			
			mail($to,$subject,$message,$header);
	}
}
