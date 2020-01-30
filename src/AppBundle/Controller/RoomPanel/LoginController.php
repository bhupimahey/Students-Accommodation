<?php
namespace AppBundle\Controller\RoomPanel;
use AppBundle\Entity\AdminLogin; 
use AppBundle\Form\FormValidationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 *
 * @Route("/roompanel")
 */

class LoginController extends Controller
{
    /**
    * @Route("/", name="admin_login")
    */
    public function indexAction(Request $request)
    {
		
		   $login = new AdminLogin(); 
	       $form  = $this->createFormBuilder($login)
	   				->add('account_username', TextType::class, array('attr' => array('class' => 'form-control','placeholder'=>'Username')))
					->add('account_key', PasswordType::class, array('attr' => array('class' => 'form-control','placeholder'=>'Password')))
				    ->add('Save', SubmitType::class, array('label'=> 'Sign in', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))
			    ->getForm();        	   
        $form->handleRequest($request);
		
		if($form->isSubmitted() &&  $form->isValid()){
			
		    $username  = $form['account_username']->getData();
			$password  = $form['account_key']->getData();
		
		    $valid_login = $this->getDoctrine()
            ->getRepository(AdminLogin::class)
            ->valid_login($username,$password);
			if(count($valid_login)==0){
			  $this->addFlash('error', 'Invalid Username & Password ');
		      return $this->redirectToRoute('admin_login');	
			}			
			else{
			 $session = new Session();		 
			 $session->set('user_name', 'Administrator');
		     $session->set('user_id', '1');
		     return $this->redirectToRoute('admin_dashboard');
		   }
		}
		
		return $this->render('admin/login.html.twig',array('form' => $form->createView() ));
	}

    
}
