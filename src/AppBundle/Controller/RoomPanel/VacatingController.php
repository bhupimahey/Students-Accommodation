<?php
namespace AppBundle\Controller\RoomPanel;
use AppBundle\Entity\AdminVacating; 
use AppBundle\Entity\AdminUsers; 
use AppBundle\Entity\AdminProperty; 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 
use Symfony\Component\HttpFoundation\Session\Session;
/**
 *
 * @Route("/roompanel")
 */
class VacatingController extends Controller
{ 
    /**
    * @Route("/vacating", name="admin_users_vacating")
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

	$property_dropdown    = $this->getDoctrine()->getRepository(AdminProperty::class)->AllPropertyDropdown();	
	$property_array=array();
	$property_array['Choose Property']='';
	if(	$property_dropdown){
	 foreach(	$property_dropdown as $propertylist){
	   if($propertylist->getPropertyStatus()=='1')  
	   $property_array[$propertylist->getPropertyTitle()]=$propertylist->getId();
	 }    
	}

   $vacating_date_sorting=array();
   $vacating_date_sorting['Sort By Vacating Date']='';
   
   $vacating_date_sorting['Desc']='desc';
   $vacating_date_sorting['Asc']='asc'; 
    
	
   $form  = $this->createFormBuilder()
	   	    	->add('property_dropdown', ChoiceType::class, ['choices' =>$property_array,'label'=>false,'required'=>false,'attr' => array('class' => 'form-control'),'data'=>''])
	   	    		->add('vacatingdate_sorting', ChoiceType::class, ['choices' =>$vacating_date_sorting,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>'desc'])
    		    ->add('Search', SubmitType::class, array('label'=> 'Search', 'attr' => array('value'=>'submit','class' => 'btn btn-primary m-b-0 btn-sm')))
			    ->getForm();        	   
   $form->handleRequest($request);
   if($form->isSubmitted() ){
	       $search_property_by=$form['property_dropdown']->getData();
	       $search_vacating_by=$form['vacatingdate_sorting']->getData();
	       
		}
	else if(isset($_GET['filter_value']) || isset($_GET['filter_value2'])){		 
	    $search_property_by = $_GET['filter_value'];	
		$search_vacating_by = $_GET['filter_value2'];
	}
	 else{
	    $search_property_by =''; 	     
	   	$search_vacating_by='';
	 }
	    
		
	$count_vacating = $this->getDoctrine()->getRepository(AdminVacating::class)->CountVacating($search_property_by,$search_vacating_by);						 
	$limit          = 10;
    $maxPages       = ceil($count_vacating / $limit);
    $thisPage       = $page;				 		
  
	$all_vacating    = $this->getDoctrine()->getRepository(AdminVacating::class)->AllVacating($thisPage,$limit,$search_property_by,$search_vacating_by);	
    $post_result     = $all_vacating->getIterator();	
	foreach($post_result as $postkey => $postval){
		$user_info=$this->getDoctrine()->getRepository(AdminUsers::class)->UserInfo($postval->getUserid());
		$postval->CustomerName    = $user_info->getCustomerName();
		$postval->CustomerMobile  = $user_info->getCustomerPhone();
		$postval->CustomerEmail  = $user_info->getCustomerUsername();
		
		if($user_info->getAssignedPropertyId()){
		$property_info=$this->getDoctrine()->getRepository(AdminProperty::class)->PropertyInfo($user_info->getAssignedPropertyId());
		$postval->PropertyName  = $property_info->getPropertyTitle();	
		}
		else
		$postval->PropertyName  = 'Not Assigned';						
	}
			
    $pagination = $this->render('pagination.html.twig',[
            'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'admin_users_vacating',
           ]);
					
	return $this->render('admin/vacating_list/view.html.twig',[
            'total_vacating'=>$count_vacating,'all_vacating' => $post_result,'maxPages'=>$maxPages,'thisPage'=>$thisPage,
			'routname'=>'admin_users_vacating','form' => $form->createView(),'search_property_by'=>$search_property_by,'search_vacating_by'=>$search_vacating_by
        ]);

    }
	
}
