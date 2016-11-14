<?php
include('classes/RealForms.php');
include('classes/RealValidation.php');
$tos = <<<EOD
'Veer West, LLC (sometimes referred to as "Veer West", "we" or "us") has developed Internet-based systems that run on proprietary software, and may in some instances use, third party software.  Veer West\'s systems are hosted and served on this Veer West site on the World Wide Web (the "Site"). This Site contains the proprietary information and materials of Veer West, as well as functionality and content that may be licensed from third parties. This Site licenses subscribers to create forms, for free and for charge, strictly in furtherance of such subscribers\'s business purpose (sometimes referred to as "Subscriber" or "you").  Subscriber may create a form from this Site for its internal business purposes (each a "Form") and solicit persons to input data into the Form ("User"), persons may access the Form and voluntarily input data into the Form, and Veer West may temporarily host such data and deliver the data to Subscriber, pursuant to the terms of the following Terms of Service. All User data is the property of the Subscriber.

1.  Acceptance of Agreement. You agree to the terms and conditions outlined in this Terms of Service Agreement ("Agreement") with respect to our Site. This Agreement constitutes the entire and only agreement between us and you, and supersedes all prior or contemporaneous agreements, representations, and understandings with respect to the Site, the content, products or services provided by or through the Site, and the subject matter of this Agreement. This Agreement may be amended at any time from time to time by us without specific notice to you. The latest Agreement will be posted on the Site, and you should review this Agreement prior to using the Site.

2.  Copyright. The content, organization, graphics, design, compilation, magnetic translation, digital conversion and other matters related to the Site are the property of Veer West and are protected pursuant to applicable copyrights, trademarks and other proprietary (including but not limited to intellectual property) rights laws. You are licensed to use our content only as specifically set forth herein. You do not acquire ownership rights to any content, document or other materials viewed, created or downloaded through the Site, with the exception of User information or data.   Our posting of information or materials on the Site does not constitute a waiver of any right in such information and materials.

3.  Trademarks.  Veer West marks include, but are not limited to Form Assembly, formassembly.com, Form Builder and Veer West.  The Site may also contain marks and trade names of third parties.

4.  Grant to Subscriber.  Subject to each term of this Agreement, we grant you a non-exclusive, non-transferable, right and license (i) to create a Form; (ii) to access the Site content solely for your internal business purposes; and (iii) to access, query input, upload, download and otherwise use the data inputted by your Users into the Form.  Unless you are using only our free Forms service, you acknowledge that you will be billed periodically for your use of the Site as set forth in Section 14.  Upon completion of all registration information and acceptance of this Agreement, Subscribers will receive a password and an account identifier.  Your right to use the Site is not transferable. Any password, account number or right given to a Subscriber to obtain information or documents is not transferable.  Subscribers are fully responsible for maintaining the confidentiality of their passwords and account identifier.  Subscribers shall all times be responsible and liable for any transactions or activities that occur on their accounts, whether or not authorized by Subscriber or us.  Each Subscriber shall immediately notify us of any unauthorized use of its account or of any other breach of security.

5.  Editing, Deleting and Modification. We reserve the right in our sole discretion to edit or delete any Form, document, information, or content appearing on, or created through, the Site or hosted by us.

6.  Privacy and Confidentiality of Information.. We will not, without the Subscribe\'s prior written consent (the "disclosing party"), disclose, and shall keep confidential, any data inputted by the disclosing party\'s Users (the "Information"), except for disclosure as required by law or legal service, and to persons who need to know such Information in connection with this Agreement and who have been informed of the terms and conditions of this Agreement as to the confidential nature and treatment of the Information and have agreed to comply herewith.

7.  Indemnification. You agree to release, indemnify, defend and hold us and our partners, attorneys, employees, agents, and affiliates (collectively, "Affiliated Parties") harmless from and against any liability, loss, claim, damage and expense, including reasonable attorneys\' fees, arising directly or indirectly from your use of the Site, violation of this Agreement, creation or use of a Form, collection, possession, or use of data derived from a Form, or any service provided or performed or agreed to be performed, or any product sold by, you, your agents, employees or assigns.

8.  Disclaimer and Limits. THE INFORMATION AND SERVICES FROM OR THROUGH THE SITE, INCLUDING OUR HOSTING AND TRANSMITTING DATA, IS PROVIDED "AS IS" AND "AS AVAILABLE," AND ALL WARRANTIES, EXPRESS OR IMPLIED, ARE DISCLAIMED (INCLUDING, BUT NOT LIMITED TO, THE DISCLAIMER OF ANY IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE). THE INFORMATION AND SERVICES, INCLUDING OUR HOSTING AND TRANSMITTING DATA, MAY CONTAIN BUGS, ERRORS, PROBLEMS OR OTHER LIMITATIONS.  WE DO NOT WARRANT OR GUARANTEE THE SECURITY OF THE DATA WE RECEIVE, HOST AND TRANSMIT TO SUBSCRIBER.  WE AND OUR AFFILIATED PARTIES HAVE NO LIABILITY WHATSOEVER FOR YOUR USE OF ANY INFORMATION OR SERVICE. IN PARTICULAR, BUT NOT AS A LIMITATION THEREOF, WE AND OUR AFFILIATED PARTIES ARE NOT LIABLE FOR ANY INDIRECT, SPECIAL, INCIDENTAL OR CONSEQUENTIAL DAMAGES (INCLUDING DAMAGES FOR LOSS OF BUSINESS, LOSS OF PROFITS, LITIGATION, OR THE LIKE), WHETHER BASED ON BREACH OF CONTRACT, BREACH OF WARRANTY, TORT (INCLUDING NEGLIGENCE), PRODUCT LIABILITY OR OTHERWISE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGES. THE NEGATION OF DAMAGES SET FORTH ABOVE IS A FUNDAMENTAL ELEMENT OF THE BASIS OF THE BARGAIN BETWEEN US AND YOU. THIS SITE AND THE INFORMATION WOULD NOT BE PROVIDED WITHOUT SUCH LIMITATIONS. NO ADVICE OR INFORMATION, WHETHER ORAL OR WRITTEN, OBTAINED BY YOU FROM US THROUGH THE SITE SHALL CREATE ANY WARRANTY, REPRESENTATION OR GUARANTEE NOT EXPRESSLY STATED IN THIS AGREEMENT.

ALL RESPONSIBILITY OR LIABILITY FOR ANY DAMAGES CAUSED BY VIRUSES CONTAINED WITHIN THE ELECTRONIC FILE CONTAINING THE FORM OR DOCUMENT IS DISCLAIMED. WE WILL NOT BE LIABLE TO YOU FOR ANY INCIDENTAL, SPECIAL OR CONSEQUENTIAL DAMAGES OF ANY KIND THAT MAY RESULT FROM USE OF OR INABILITY TO USE OUR SITE. OUR MAXIMUM LIABILITY TO YOU UNDER ALL CIRCUMSTANCES WILL BE EQUAL TO THE FEES YOU PAY TO US FOR ANY GOODS, SERVICES OR INFORMATION WITHIN THE TWELVE (12) MONTHS PRECEDING YOUR LOSS.

9.  Use of Information. We reserve the right, and you authorize us, to use and assign all information regarding Site uses by you and all information provided by you in any manner consistent with our Privacy Policy.  Our Privacy Policy, as it may change from time to time, is a part of this Agreement.

10.  Third-Party Services. The Site contains links to other Web sites. We are not responsible for the content, accuracy or opinions express in such Web sites, and such Web sites are not investigated, monitored or checked for accuracy or completeness by us. Inclusion of any linked Web site on our Site does not imply approval or endorsement of the linked Web site by us. If you decide to leave our Site and access these third-party sites, you do so at your own risk AND WITHOUT WARRANTIES OF ANY KIND BY US, EXPRESSED OR IMPLIED, OR OTHERWISE INCLUDING WARRANTIES OF TITLE, FITNESS FOR A PARTICULAR PURPOSE, MERCHANTABILITY, OR NONINFRINGEMENT. UNDER NO CIRCUMSTANCES ARE WE LIABLE FOR ANY DAMAGES ARISING FROM THE TRANSACTIONS BETWEEN YOU AND THIRD PARTIES OR FOR ANY INFORMATION APPEARING ON ANY SITES LINKED TO OUR SITE.

11.  Conduct.  Subscribers shall abide by all applicable local, state, national and international laws and regulations and be solely responsible for all acts or omissions that occur with respect to your Form(s) and or under your account or password, including the content of your transmissions through or related to this Site. 

We may have no control over the content of transmissions to the Site or relating to the Site and will not be liable for content over which we have no control.  You shall not use the Site or a Form to distribute any images, sounds, messages or other materials that are obscene, harassing, racist, malicious, fraudulent or libelous, and will not use the Site or a Form for any activity that may be considered unethical, immoral, or illegal.  You may not use unsolicited email, unsolicited bulk email ("UBE", "spam") or other unethical means to directly or indirectly solicit persons to input data into a Form. You will abide by all rules, regulations, procedures and policies of Veer West and any policies of the networks connected to the Site.

By way of example, and not as a limitation, in connection with the Site, services, or Forms provided by or through us, you will not, directly or indirectly:

    Transmit chain letters, junk email, junk voicemail, junk faxes, spamming or any duplicative or unsolicited messages;
    Harvest or otherwise collect information about others, including email addresses, without their consent;
    Use a false identity or forged email address or header, or otherwise attempt to mislead others as to your identity or the origin of your messages;
    Transmit unlawful, harassing, libelous, abusive, threatening, harmful, vulgar, obscene or otherwise objectionable material of any kind or nature;
    Transmit any material that may infringe the intellectual property rights or other rights of third parties, including trademark, copyright or right of publicity;
    Transmit any material that contains viruses, trojan horses, worms, time bombs, cancelbots, or any other harmful or deleterious programs;
    Interfere with or disrupt networks or websites connected to the Site or violate the regulations, policies or procedures of such networks;
    Attempt to gain unauthorized access to the Site, Site servers, other accounts, computer systems or networks connected to the Site, through password mining or any other means;
    Interfere with another person\'s use and enjoyment of the Site or use and enjoyment of similar services; or
    Collect credit card information or other form of online payments.  

12.  Link From Subscriber Site. Subject to each term of this Agreement, we grant Subscriber a non-exclusive, non-transferable, right and license to provide a hypertext link from Subscriber\'s site or sites on the World Wide Web to the Site in order to provide users with access to Form(s).  The foregoing license may be terminated at the discretion of Veer West at any time and for any reason or no reason, with or without notice. We will transmit to the Subscriber, exclusively, the data provided on the Form(s) created by Subscriber. 

13.  Subscriber Responsibility.  You will be solely responsible for Forms, including without limitation, the accuracy and appropriateness of content appearing therein, and the final tabulations and application of information provided on the Form(s).  You are also responsible for the security of any personal information derived from the Form(s) and delivered to you from Veer West.  You will keep all such information confidential and will use the same degree of care and security as you use with your confidential information.  You shall use reasonable efforts to ensure that neither the Site content nor any Form is displayed outside the Site or distributed in any way to any third party.  Except as expressly authorized in this Agreement, you shall not rent, lease, sublicense, distribute, transfer, copy, reproduce, display, modify or timeshare a Form, the Site, or the Site content or any portion thereof, or use such as a component of or a base for products or services prepared for commercial sale, sublicense, lease, access or distribution, or prepare any derivative work based on the Form(s), the Site, or the Site content. 

14.  Cancellation and Termination.  Veer West reserves the right to suspend or terminate your account for any reason at any time WITHOUT WARNING OR PRIOR NOTICE. No refunds of fees paid will be made if account termination is due to a violation of the terms contained herein.  Veer West reserves the right to refuse service to anyone for any reason at any time.

15.  Payment Policies.  All accounts are set up on a pre-pay basis. Veer West reserves the right to change prices at any time. You are responsible for all money owed on your account from the time it is established until you notify us to cancel your account.  All fees are in U.S. dollars.  You will be billed an additional $50.00 per returned check, per wire transfer received, and per credit card chargeback received.  There is a thirty (30) day money back guarantee if you are unable to use our forms. There is no partial refund.

16.  Miscellaneous. This Agreement shall be treated as though it were executed and performed in Indianapolis, Indiana, and shall be governed by and construed in accordance with the laws of the State of Indiana (without regard to conflict of law principles). Any cause of action by you with respect to the Site (and/or any information, products or services related thereto) must be instituted within one (1) year after the cause of action arose or be forever waived and barred. All actions shall be subject to the limitations set forth in Section 8 (Disclaimer and Limits) and Section 10 (Third-Party Services). The language in this Agreement shall be interpreted in accordance with its fair meaning and not strictly for or against either party. All legal proceedings arising out of or in connection with this Agreement shall be brought solely in Indianapolis, Indiana.  You expressly submit to the exclusive jurisdiction of said courts and consent to extraterritorial service of process. Should any part of this Agreement be held invalid or unenforceable, that portion shall be construed consistent with applicable law and the remaining portions shall remain in full force and effect. To the extent that anything in or associated with the Site is in conflict or inconsistent with this Agreement, this Agreement shall take precedence. Our failure to enforce any provision of this Agreement shall not be deemed a waiver of such provision nor of the right to enforce such provision.' 
EOD;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Test Wizard</title>
<link href="css/exec_wizard.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery-1.9.0.min.js"></script>
<script type="text/javascript" src="js/jquery.execWizard-2.0.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
    	// Smart Wizard 	
  		$('#wizard').smartWizard();
      
      function onFinishCallback(){
        $('#wizard').smartWizard('showMessage','Finish Clicked');
      }     
		});
</script>
<SCRIPT language=JavaScript>
<!--

//Accept terms & conditions script (by InsightEye www.insighteye.com)
//Visit JavaScript Kit (http://javascriptkit.com) for this script & more.

function checkCheckBox(f){
if (f.agree.checked == false )
{
alert('Please check the box to continue.');
return false;
}else
return true;
}
//-->
</SCRIPT>
</head>

<body>
<?php
$mrf = new RealForms;
$mrfv= new Validation;
?>
<div align="center">
	<h1>Cadastrese</h1>
<p>A modalidade TRIAL PLUS permite testar o DOD por 15 dias, conhecer o histórico de nossos Robôs e operar em sua conta de forma 100% automática.</p>
</div>
<table align="center" border="0" cellpadding="0" cellspacing="0">
<tr><td> 
<!-- Smart Wizard -->
  		<div id="wizard" class="swMain">
  			<ul>
  				<li><a href="#step-1">
                <label class="stepNumber">1</label>
                <span class="stepDesc">
                   Paso 1<br />
                   <small>Dados de Acesso</small>
                </span>
            </a></li>
  				<li><a href="#step-2">
                <label class="stepNumber">2</label>
                <span class="stepDesc">
                   Paso 2<br />
                   <small>Dados de Endereço</small>
                </span>
            </a></li>
  				<li><a href="#step-3">
                <label class="stepNumber">3</label>
                <span class="stepDesc">
                   Paso 3<br />
                   <small>Dados de Contato</small>
                </span>                   
             </a></li>
  				<li><a href="#step-4">
                <label class="stepNumber">4</label>
                <span class="stepDesc">
                   Paso 4<br />
                   <small>Dados de Conta</small>
                </span>                   
            </a></li>
                <li><a href="#step-5">
                <label class="stepNumber">5</label>
                <span class="stepDesc">
                   Paso 5<br />
                   <small>Dados de Conta</small>
                </span>                   
            </a></li>
  			</ul>
  		<div id="step-1">
<?php $mrf->rform_open( array('action'=>"demo_form.asp", 'method'=>"post")); ?>
	
            <h2 class="StepTitle">Paso 1 Acesso</h2>
            <table class="step-contents" width="700" border="0" cellspacing="5" cellpadding="5">
                <tr><td>Nome</td><td><?php $mrf->rform_input( array('id' => 'test', 'name' => 'nome')); ?></td><td><?php $mrfv->is_Valid(array('name' => 'nome')); ?></td></tr>
                <tr><td>Email</td><td><?php $mrf->rform_input( array('id' => 'test')); ?></td><td>Valid</td></tr>
                <tr><td>Confirmar Email</td><td><?php $mrf->rform_input( array('id' => 'test')); ?></td><td>Valid</td></tr>
                <tr><td>Senha:</td><td><?php $mrf->rform_input( array('id' => 'test')); ?></td><td>Valid</td></tr>
                <tr><td>Confirmar Senha:</td><td><?php $mrf->rform_input( array('id' => 'test')); ?></td><td>Valid</td></tr>
                <tr><td></td>
                	<td>
	                	<ul>Para ter uma senha mais segura:
							<li>Use letras e números;</li>
							<li>Adicione caracteres especiais. Ex.: @, %, &amp;.</li>
							<li>Combine letras maiúsculas e minúsculas</li>
					   </ul>
					</td><td>Valid</td>
			    </tr>
                <tr><td></td><td><?php $mrf->rform_input( array('id' => 'test')); ?></td><td>Valid</td></tr>
            </table>         			
        </div>
  		<div id="step-2">
            <h2 class="StepTitle">Paso 2 Endereço</h2>	
            <table class="step-contents" width="700" border="0" cellspacing="5" cellpadding="5">
                <tr><td>Endereço</td><td><?php $mrf->rform_input( array('id' => 'test')); ?></td><td>Valid</td></tr>
                <tr><td>Complemento</td><td><?php $mrf->rform_input( array('id' => 'test')); ?></td><td>Valid</td></tr>
                <tr><td>Cidade</td><td><?php $mrf->rform_input( array('id' => 'test')); ?></td><td>Valid</td></tr>
                <tr><td>Estado:</td><td><?php $mrf->rform_input( array('id' => 'test')); ?></td><td>Valid</td></tr>
                <tr><td>CEP:</td><td><?php $mrf->rform_input( array('id' => 'test')); ?></td><td>Valid</td></tr>
                <tr><td>Pais:</td><td><?php $mrf->rform_input( array('id' => 'test')); ?></td><td>Valid</td>
			    </tr>
            </table>       
        </div>                      
  		<div id="step-3">
            <h2 class="StepTitle">Paso 3 Contato</h2>	
            <table class="step-contents" width="700" border="0" cellspacing="5" cellpadding="5">
                <tr><td>Telefone residencial:</td><td><?php $mrf->rform_input( array('id' => 'test')); ?></td><td>Valid</td></tr>
                <tr><td>Telefone comercial:</td><td><?php $mrf->rform_input( array('id' => 'test')); ?></td><td>Valid</td></tr>
                <tr><td>Celular:</td><td><?php $mrf->rform_input( array('id' => 'test')); ?></td><td>Valid</td></tr>
            </table>               				          
        </div>
  			<div id="step-4">
            <h2 class="StepTitle">Step 4 Sua Conta</h2>	
            <table class="step-contents" width="700" border="0" cellspacing="5" cellpadding="5">
                <tr><td>Corretora:</td><td><?php $mrf->rform_input( array('id' => 'test')); ?></td><td>Valid</td></tr>
                <tr><td>Escritório:</td><td><?php $mrf->rform_input( array('id' => 'test')); ?></td><td>Valid</td></tr>
                <tr><td>Assessor:</td><td><?php $mrf->rform_input( array('id' => 'test')); ?></td><td>Valid</td></tr>
                <tr><td>Conta:</td><td><?php $mrf->rform_input( array('id' => 'test')); ?></td><td>Valid</td></tr>
                <tr><td>CPF:</td><td><?php $mrf->rform_input( array('id' => 'test')); ?></td><td>Valid</td></tr>
            </table>                			
            </div>
            <div id="step-5">
            <h2 class="StepTitle">Step 5 Termo de Uso</h2>
            <table class="step-contents" width="700" border="0" cellspacing="5" cellpadding="5">
  <tr> 
    <td><?php $mrf->rform_textarea( array('id' => 'test', 'rows'=>'8', 'cols' => '69'), $tos); ?></td>
  </tr>
  <tr>
    <td><?php $mrf->rform_checkbox('agree','agree','Declaro que li e entendi o Termo de Aquisição'); ?></td>
  </tr>
</table>  
<?php $mrf->rform_close(); ?> 		
<!-- End SmartWizard Content -->  		
 		
</td></tr>
</table>
</body>
</html>