<?php
namespace AppBundle\Controller;
use AppBundle\Form\FormValidationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
class ContactController extends Controller
{ 
/**
 * @Route("/contact", name="contact_us")
 */ 
  public function indexAction(Request $request, \Swift_Mailer $mailer)
    {			
	   $form  = $this->createFormBuilder()
	   		->add('customer_email', EmailType::class, array('attr' => array('class' => 'form-control')))
			->add('customer_name', TextType::class, array('attr' => array('class' => 'form-control')))
			->add('customer_message', TextareaType::class, array('attr' => array('class' => 'form-control')))
			->add('Save', SubmitType::class, array('label'=> 'Send Message', 'attr' => array('value'=>'submit','class' => 'md-btn btn-custom')))
			->getForm();        	   
        $form->handleRequest($request);
		
		if($form->isSubmitted() &&  $form->isValid()){
			
		    $customer_email  = $form['customer_email']->getData();
			$customer_name  = $form['customer_name']->getData();
			$customer_message  = $form['customer_message']->getData();
		    $this->addFlash('success', 'Message has been send successfully. ');
			$message = (new \Swift_Message())
			           ->setSubject('Contact Us Response')
						->setFrom([$customer_email => $customer_name])
						->setTo('bhupimahey@gmail.com')
						->setBody(
							$this->renderView(
								'emails/contact_us.html.twig',
								['customer_email' => $customer_email,'customer_name' => $customer_name,'customer_message' => $customer_message]
							),
							'text/html'
						);										
				//->attach(Swift_Attachment::fromPath('my-document.pdf'))	
			   $mailer->send($message);		  			  
		      return $this->redirectToRoute('contact_us');	
		}
	    return $this->render('default/contact.html.twig',array('form' => $form->createView() ));
    }
}
