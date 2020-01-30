<?php
namespace AppBundle\Controller;
use AppBundle\Entity\CommonRegister; 
use AppBundle\Entity\AdminSocialLinks;
use AppBundle\Form\FormValidationType;
use AppBundle\Service\CommonConfig;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;


class DefaultController extends Controller
{ 
/**
 * @Route("/", name="home_page")
 */ 
  public function indexAction()
    {
		$social_links = $this->getDoctrine()->getRepository(AdminSocialLinks::class)->GetSocialLinks();
		return $this->render('default/index.html.twig',['social_links'=>$social_links]);
    }
 
/**
 * @Route("/login", name="view_login")
 */ 
  public function loginAction(Request $request)
   {
		   $login = new CommonRegister(); 
	       $form  = $this->createFormBuilder($login)
	   				->add('account_username', TextType::class, array('attr' => array('class' => 'form-control','placeholder'=>'Username')))
					->add('account_key', PasswordType::class, array('attr' => array('class' => 'form-control','placeholder'=>'Password')))
				    ->add('Login', SubmitType::class, array('label'=> 'Login', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))
			    ->getForm();        	   
        $form->handleRequest($request);
		
		if($form->isSubmitted() &&  $form->isValid()){
			
		    $username  = $form['account_username']->getData();
			$password  = $form['account_key']->getData();
		    $valid_login = $this->getDoctrine()->getRepository(CommonRegister::class)->valid_login($username,$password);
			if(count($valid_login)==0){
			  $this->addFlash('error', 'Invalid Username & Password ');
		      return $this->redirectToRoute('view_login');	
			}			
			else{
			 $user_info = $valid_login[0];
			 if($user_info->getCustomerAccountStatus()==0){
			  $this->addFlash('error', 'your account is not active');
		      return $this->redirectToRoute('view_login');	
			}
			else if($user_info->getAssignedPropertyId()==0){
			  $this->addFlash('error', 'Can not login , property not assigned');
		      return $this->redirectToRoute('view_login');	
			}
		
		
			  $session = new Session();		 
			  $session->set('customer_name', $user_info->getCustomerName());
			  $session->set('customer_email', $user_info->getAccountUsername());
		      $session->set('customer_id', $user_info->getCustomerId());
		      $session->set('kyc_photo_doc', $user_info->getKycPhotoDoc());
		       $session->set('kyc_photo_doc_status', $user_info->getKycStatus());
		      
			 if($user_info->getKycPhotoDoc()=='' || $user_info->getKycStatus()=='0')
			   return $this->redirectToRoute('update_user_profile');
			 
		     return $this->redirectToRoute('user_dashboard');
		   }
		}
		
		return $this->render('default/login.html.twig',array('form' => $form->createView() ));
	}  
	


/**
 * @Route("/forgot_password", name="forgot_password")
 */ 
  public function forgotAction(Request $request,CommonConfig $CommonConfig)
   {
		   $login = new CommonRegister(); 
	       $form  = $this->createFormBuilder($login)
	   				->add('account_username', TextType::class, array('attr' => array('class' => 'form-control','placeholder'=>'Username Or Email')))
					
				    ->add('Submit', SubmitType::class, array('label'=> 'Submit', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))
			    ->getForm();        	   
        $form->handleRequest($request);
		
		if($form->isSubmitted() &&  $form->isValid()){
			
		    $username  = $form['account_username']->getData();
			
		    $valid_login = $this->getDoctrine()->getRepository(CommonRegister::class)->valid_user($username);
			
			if(count($valid_login)==0){
			  $this->addFlash('error', 'No record found!! ');
		      return $this->redirectToRoute('forgot_password');	
			}			
			else{
			 $random_password =  substr(str_shuffle('abcdef^%#ghijklmnopqrstu$#@%vwxyzABCDEFGHIJKLMNOP@#!^%QRSTUVWXYZ0123$#@&456789') , 0 , 10 );
	
			$customer_info=$valid_login[0];
			$customer_name      = $customer_info->getCustomerName();
			$customer_phone   	= $customer_info->getCustomerPhone();
			$customer_email 	= $customer_info->getCustomerEmail();
			$user_id            = $customer_info->getCustomerId (); 
						
			$entityManager = $this->getDoctrine()->getManager();
			$users         = $entityManager->getRepository(CommonRegister::class)->find($user_id);				
			$users->setAccountKey(md5($random_password));			
			$entityManager->flush();
					
			$htmlContent = $this->renderView(
								'emails/user_forgot_email.twig',
								['customer_email' => $customer_email,'customer_name' => $customer_name,'customer_password' => $random_password]
							);	
			if($customer_email)				
		  $mail_data =  $CommonConfig->SendMail($customer_email, 'Reset Password', $htmlContent,'info@prestige.22creative.in','Prestige Team');
		  
		  
		  
		   $this->addFlash('success', 'Password has been reset and mail to you. ');
				
			
		    return $this->redirectToRoute('view_login'); 
			}
			
		
			 
		   }
	
		return $this->render('default/forgot_password.html.twig',array('form' => $form->createView() ));
	}  
	
	
	

/**
 * @Route("/about_us", name="about_us")
 */ 
  public function AboutusindexAction()
    {
			
	    return $this->render('default/about_us.html.twig');
    }

/**
 * @Route("/envision", name="envision")
 */ 
  public function EnvisionindexAction()
    {
			
	    return $this->render('default/envision.html.twig');
    }


/**
 * @Route("/products", name="products")
 */ 
  public function ProductsindexAction()
    {
			
	    return $this->render('default/products.html.twig');
    }

/**
 * @Route("/services", name="services")
 */ 
  public function ServicesindexAction()
    {
			
	    return $this->render('default/services.html.twig');
    }

/**
 * @Route("/faq", name="faq")
 */ 
  public function FaqindexAction()
    {
			
	    return $this->render('default/faq.html.twig');
    }

/**
 * @Route("/enquiry_form", name="enquiry_form")
 */ 
  public function EnquiryfrmindexAction()
    {
			
	    return $this->render('default/enquiry_form.html.twig');
    }
/**
 * @Route("/contact_us", name="contact_us")
 */ 
  public function ContactindexAction()
    {
			
	    return $this->render('default/contact_us.html.twig');
    }

/**
 * @Route("/privacy", name="privacy")
 */ 
  public function PrivacyindexAction()
    {
			
	    return $this->render('default/privacy.html.twig');
    }
/**
 * @Route("/t_c", name="t_c")
 */ 
  public function TcindexAction()
    {
			
	    return $this->render('default/t_c.html.twig');
    }
}
