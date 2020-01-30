<?php

namespace AppBundle\Controller\RoomPanel;

use AppBundle\Entity\AdminUsers; 

use AppBundle\Entity\AdminEnquiries; 

use AppBundle\Entity\AdminProperty; 

use AppBundle\Entity\AdminServices; 

use AppBundle\Entity\AdminUsersSuggestions; 

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response; 

use Symfony\Component\Form\Extension\Core\Type\PasswordType;

use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\Form\Extension\Core\Type\SubmitType; 

use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

use Symfony\Component\HttpFoundation\Session\Session;



/**

 *

 * @Route("/roompanel")

 */

class DashboardController extends Controller

{ 

    /**

    * @Route("/dashboard", name="admin_dashboard")

    */

  public function indexAction(Request $request)

    {

  	    $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
	

	    $total_users     = $this->getDoctrine()->getRepository(AdminUsers::class)->CountUsers();	

		$total_enquiries = $this->getDoctrine()->getRepository(AdminEnquiries::class)->CountEnquiries();				   	  

        $total_property  = $this->getDoctrine()->getRepository(AdminProperty::class)->CountProperty();	

		$total_services  = $this->getDoctrine()->getRepository(AdminServices::class)->CountServices();

		$all_enquiries   = $this->getDoctrine()->getRepository(AdminEnquiries::class)->AllEnquiries(1,10);

		$property_suggestions   = $this->getDoctrine()->getRepository(AdminUsersSuggestions::class)->AllPropertySuggestions(10);

		$all_sggestions = array();

		foreach($property_suggestions as $SuggestionsUsers_key => $SuggestionsUsers_val){

				$user_info=$this->getDoctrine()->getRepository(AdminUsers::class)->UserInfo($SuggestionsUsers_val->getUserid());

				if($user_info)							   

				$SuggestionsUsers_val->customer_name  = $user_info->getCustomerName();	

				

				else

				$SuggestionsUsers_val->customer_name  = 'N/A';	

				

				

				

				$property_info=$this->getDoctrine()->getRepository(AdminProperty::class)->PropertyInfo($SuggestionsUsers_val->getPropertyid());

				if($property_info)							   

				$SuggestionsUsers_val->property_name  = $property_info->getPropertyTitle();	

				

				else

				$SuggestionsUsers_val->property_name  = 'N/A';

				

					

				$all_sggestions[]=$SuggestionsUsers_val;									   

		     }

	   return $this->render('admin/dashboard.html.twig',['total_users'=>$total_users,'total_enquiries'=>$total_enquiries,

	   						'total_property'=>$total_property,'all_enquiries'=>$all_enquiries,

							'property_suggestions'=>$all_sggestions,'total_services'=>$total_services

						  	]);



    }



/**

     * Matches /dashboard/*

     *

     * @Route("/logout", name="admin_logout")

    */	

	

  public function logoutAction(Request $request)

    {

  	    $session = new Session();

	    if(!$session->get('user_id'))

		  return $this->redirectToRoute('admin_login');

        else{

		$session->remove('user_name');	

		$session->remove('user_id');	

		return $this->redirectToRoute('admin_login');			

		}  



    }



/**

     * Matches /dashboard/*

     *

     * @Route("/change_password", name="admin_change_password")

    */	

	

  public function ChangePasswordAction(Request $request,UserPasswordEncoderInterface $encoder)

    {

  	    $session = new Session();

	    if(!$session->get('user_id'))

		  return $this->redirectToRoute('admin_login');

        else{

		 $form  = $this->createFormBuilder()

	   		->add('old_password', PasswordType::class, array('label'=>false,'attr' => array('class' => 'form-control')))

			 ->add('new_password', RepeatedType::class, [

						'type' => PasswordType::class,

						'invalid_message' => 'The new password and old password fields must match.',

						'label'=>false,						

						'required' => true,

						'first_options'  => array('label'=>false,'attr' => array('class' => 'form-control')),

						'second_options' =>array('label'=>false,'attr' => array('class' => 'form-control')),

					])



			->add('Save', SubmitType::class, array('label'=> 'Submit', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))

			->getForm();        	   

         $form->handleRequest($request);

		 

		 	 if($form->isSubmitted()){

				 $users   = new AdminUsers();   

				 $session = new Session();

	    		 $user_id =$session->get('user_id');

				 

				 $entityManager      = $this->getDoctrine()->getManager();

				 $updatepassworduser = $entityManager->getRepository(AdminUsers::class)->find(array('account_id'=>$user_id));	

				 

			     $old_password      = $form['old_password']->getData();

				 $new_password      = $form['new_password']['first']->getData();

				 $confirm_password  = $form['new_password']['second']->getData();

				 

			     $password_info = $this->getDoctrine()->getRepository('AppBundle:AdminUsers')->OldPasswordCheck($old_password,$user_id);       $error=0;

				 if($password_info==0){

				  $this->addFlash('error', 'Old password does not match');

						return $this->redirectToRoute('admin_change_password'); 

						$error=$error+1;	 

				 }

				if($new_password !=$confirm_password){

				  $this->addFlash('error', 'Password and confirm paswword does not match');

						return $this->redirectToRoute('admin_change_password'); 	 

						$error=$error+1;

				 } 

				 

				 if($error==0){

					  $updatepassworduser->setCustomerAccountKey(md5($new_password));

					  $entityManager->flush();	

					  $this->addFlash('success', 'Password has been changed successfully');

					 

					 return $this->redirectToRoute('admin_change_password'); 

				 }

		   } 

		    return $this->render('admin/change_password.html.twig',array('form' => $form->createView() ));		 

							

		}  

    }	

}

