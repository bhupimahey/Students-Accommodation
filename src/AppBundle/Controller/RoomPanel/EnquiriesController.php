<?php
namespace AppBundle\Controller\RoomPanel;
use AppBundle\Entity\AdminEnquiries; 
use AppBundle\Entity\AdminUsers;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 *
 * @Route("/roompanel")
 */
class EnquiriesController extends Controller
{ 
    /**
    * @Route("/enquiries", name="admin_view_enquiries")
    */
  public function indexAction(Request $request)
    {
        $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		  
	 if(isset($_GET['page']))		 
		$page = $_GET['page'];	
	 else
	    $page ='1'; 	
		
	$count_events = $this->getDoctrine()
           			   ->getRepository(AdminEnquiries::class)
           			   ->CountEnquiries();	
					 
	$limit = 10;
    $maxPages = ceil($count_events / $limit);
    $thisPage = $page;
				 		
  	$enquiries = new AdminEnquiries();   
	$all_enquiries = $this->getDoctrine()
            ->getRepository(AdminEnquiries::class)
            ->AllEnquiries($thisPage,$limit);
		
    $pagination = $this->render('pagination.html.twig',[
            'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'admin_view_events',
           ]);
					
	return $this->render('admin/enquiries/view.html.twig',[
            'total_enquiries'=>$count_events,'all_enquiries' => $all_enquiries,'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'admin_view_events',
        ]);

    }
	
	/**
     * Matches /enquiries/*
	 /**
     * @Route("/enquiries/convert/user/{slug}", name="convert_enquiry")
     */
  public function converEnquiryAction(Request $request,$slug)
    {  
	  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
	   $enquiry_info = $this->getDoctrine()->getRepository('AppBundle:AdminEnquiries')->findBy(array("enquiry_id"=>$slug));
	   
	   if(!$enquiry_info)
	    return  $this->redirectToRoute('admin_view_enquiries');	
	   
       if(!$enquiry_info[0]->getCustomerName())
	      $customer_name ='';
	   else	   	  
	       $customer_name = $enquiry_info[0]->getCustomerName();

      if(!$enquiry_info[0]->getCustomerPhone())
	      $customer_phone ='';
	   else	   	  
	       $customer_phone = $enquiry_info[0]->getCustomerPhone();
	
	
     if(!$enquiry_info[0]->getCustomerEmail())
	      $customer_email ='';
	   else	   	  
	       $customer_email = $enquiry_info[0]->getCustomerEmail();

	 $form  = $this->createFormBuilder()
	   		->add('customer_name', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$customer_name)))
			->add('customer_phone', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$customer_phone)))
			->add('customer_email', EmailType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$customer_email)))
			->add('customer_password', PasswordType::class, array('label'=>false,'attr' => array('class' => 'form-control')))
			 ->add('Save', SubmitType::class, array('label'=> 'Save', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))
			->getForm();        	   
        $form->handleRequest($request);

		if($form->isSubmitted() ){	
	        $entityManager      = $this->getDoctrine()->getManager();
            	  
			$customer_name      = $form['customer_name']->getData();
			$customer_phone   	= $form['customer_phone']->getData();
			$customer_email 	= $form['customer_email']->getData();
			$customer_password  = $form['customer_password']->getData();				
		   
		    $users     = new AdminUsers();   
		    $user_info = $this->getDoctrine()->getRepository('AppBundle:AdminUsers')->findBy(array("account_username"=>$customer_email));
		    if($user_info){
			$this->addFlash('error', 'User has already registered with this email.');
			return $this->redirectToRoute('admin_view_enquiries');				
			}
			else{
		    $time  = new \DateTime(date("Y-m-d H:i:s"));
			$users->setCustomerName($customer_name);
			$users->setCustomerUsername($customer_email);			
			$users->setCustomerPhone($customer_phone);
			$users->setCustomerAccountKey(md5($customer_password));
			$users->setCustomerAccountType(2);	
			$users->setCustomerAccountStatus(0);
			$users->setAssignedPropertyId('0');
			$users->setCustomerIsDeleted(0);
			$users->setIpAddress($request->getClientIp());	
			$users->setEntryTime($time);	
			
			$sn = $this->getDoctrine()->getManager();      
			$sn -> persist($users);
			$sn -> flush();					
			$this->addFlash('success', 'User has been added successfully');
			
			// mar enquiry as convert to user
			  
			$entityEnquiry = $this->getDoctrine()->getManager();
            $enquiry = $entityEnquiry->getRepository(AdminEnquiries::class)->find(array("enquiry_id"=>$slug));			
			$enquiry->setIsConverted('1');		
			$entityEnquiry->flush();
			
			return $this->redirectToRoute('admin_view_enquiries');				
			}					 	
	   }
	   
	return $this->render('admin/enquiries/convert.html.twig',[
            'form' => $form->createView(),
			'id'=>	$slug	
            ]);
     
    }
	
 /**
     * @Route("/enquiries/delete/{slug}", name="delete_enquiry")
     */
    public function deleventAction($slug)
    {
		  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
	    $entityManager = $this->getDoctrine()->getManager();
		$repository    = $this->getDoctrine()->getRepository(AdminEnquiries::class);
		$event         = $repository->find($slug);
		$entityManager->remove($event);
		$entityManager->flush();	
		
		$this->addFlash('success', 'Event has been deleted successfully');
		return $this->redirectToRoute('admin_view_events');		
	
    }			
	
}
