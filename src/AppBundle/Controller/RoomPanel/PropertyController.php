<?php
namespace AppBundle\Controller\RoomPanel;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\AdminProperty; 
use AppBundle\Entity\AdminPropertyRooms;
use AppBundle\Entity\AdminPropertyImages;
use AppBundle\Entity\AdminUsers; 
use AppBundle\Entity\AdminUsersSuggestions; 
use AppBundle\Service\CommonConfig;
use AppBundle\Entity\AdminPropertyCleanDates; 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 
use Symfony\Component\Form\Extension\Core\Type\FileType ;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 *
 * @Route("/roompanel")
 */
class PropertyController extends Controller
{ 
    /**
    * @Route("/property", name="view_property")
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
		
	$property     = new AdminProperty();  
	
	$prop_status_dropdown=array();
    $prop_status_dropdown['Choose Status']='';
    $prop_status_dropdown['Active']='1';
    $prop_status_dropdown['Inactive']='0';
   
    if(isset($_GET['filter_value']))		 
	$prop_status = $_GET['filter_value'];
	else
    $prop_status ='';	
 
 $form  = $this->createFormBuilder()
	   	    	->add('prop_status', ChoiceType::class, ['choices' =>$prop_status_dropdown,'label'=>false,'required'=>false,'attr' => array('class' => 'form-control'),'data'=>$prop_status])
    		    ->add('Search', SubmitType::class, array('label'=> 'Search', 'attr' => array('value'=>'submit','class' => 'btn btn-primary m-b-0 btn-sm')))
			    ->getForm();        	   
  $form->handleRequest($request); 
		
   if($form->isSubmitted() ){
	       $prop_status=$form['prop_status']->getData();
	       
		}
 
	$count_property = $this->getDoctrine()
           			 ->getRepository(AdminProperty::class)
           			 ->CountProperty($prop_status);			

	$limit = 10; 
    $maxPages = ceil($count_property / $limit);
    $thisPage = $page;
		
		  	
	  $all_property = $this->getDoctrine()
           			->getRepository(AdminProperty::class)
         			->AllProperty($thisPage,$limit,$prop_status);

       $post_result = $all_property->getIterator();	
	   foreach($post_result as $postkey => $postval){
	       
	       // is property assigned or not 
	       $IsAssigned = $this->getDoctrine()->getRepository(AdminUsers::class)
         			->PropertyAssigned($postval->getId());
	       
	       $active_occupants = $this->getDoctrine()->getRepository(AdminUsers::class)->PropertyActiveTenants($postval->getId());
	       
			$SuggestionsUsers = $this->getDoctrine()->getRepository(AdminUsersSuggestions::class)
         			->PropertyUsersSuggestions($postval->getId());	
				  $total_suggestions=0;
				  $all_sggestions=array();
				  foreach($SuggestionsUsers as $SuggestionsUsers_key => $SuggestionsUsers_val){
					 
					  $user_info=$this->getDoctrine()
					                               ->getRepository(AdminUsers::class)
         			                                ->UserInfo($SuggestionsUsers_val->getUserid());
							
						if($user_info){		
						    $total_suggestions=$total_suggestions+1;		   
							$SuggestionsUsers_val->customer_name  = $user_info->getCustomerName();	
							$SuggestionsUsers_val->customer_email = $user_info->getCustomerEmail();	
							$SuggestionsUsers_val->customer_phone = $user_info->getCustomerPhone();	
							$all_sggestions[]=$SuggestionsUsers_val;
						}
						else
						    $all_sggestions[]=array();
					
					$postval->SuggestionUsers=	$all_sggestions;
				
		          }				
		       	$postval->is_assigned=$IsAssigned;   
				$postval->SuggestionCounter=$total_suggestions;
				$postval->active_occupants=$active_occupants;
					
	      
	   }
	   $pagination  = $this->render('pagination.html.twig',[
            'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'view_property','filter_name'=>'search_property','filter_value'=>$prop_status,
        ]);
		
			
	return $this->render('admin/property/view.html.twig',[
            'all_property' => $post_result,'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'view_property','filter_name'=>'search_property',
            'filter_value'=>$prop_status,'form' => $form->createView()
        ]);

    }
	
   /**
     * Matches /property/*
     *
     * @Route("/property/add", name="admin_add_property")
    */	
	
  public function addPropertyAction(Request $request,CommonConfig $CommonConfig)
    {
		  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		  
       $type_of_property   = $CommonConfig->PropertyConfig('type_of_property');
       $property_bedrooms  = $CommonConfig->PropertyConfig('total_bedrooms');
       $property_bathrooms = $CommonConfig->PropertyConfig('total_bathrooms');
       $property_parking   = $CommonConfig->PropertyConfig('parking_status');
       $property_internet  = $CommonConfig->PropertyConfig('internet_status'); 
       $total_flatmates    = $CommonConfig->PropertyConfig('total_flatmates');
       $bond_status        = $CommonConfig->PropertyConfig('bond_status');
       $bills_status       = $CommonConfig->PropertyConfig('bills_status');
       $room_type          = $CommonConfig->PropertyConfig('roomtypes_status');
       $room_furnishings   = $CommonConfig->PropertyConfig('roomfurnishings_status');
       $room_bathroom      = $CommonConfig->PropertyConfig('bathrooms_status');
       $room_furnishings_feature  = $CommonConfig->PropertyConfig('room_furnishing_features');
       $flatmatespref_status      = $CommonConfig->PropertyConfig('flatmatespref_status');
       $length_of_stay            = $CommonConfig->PropertyConfig('length_of_stay');     
	      
	   $form   = $this->createFormBuilder()
       ->add('property_title', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control')))
       ->add('property_type', ChoiceType::class, ['choices' =>$type_of_property,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>''])
	   ->add('property_bedrooms', ChoiceType::class, ['choices' =>$property_bedrooms,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>''])
       ->add('property_bathrooms', ChoiceType::class, ['choices' =>$property_bathrooms,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>''])
	   ->add('property_parking', ChoiceType::class, ['choices' =>$property_parking,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>''])
       ->add('property_internet', ChoiceType::class, ['choices' =>$property_internet,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>''])
	   ->add('total_flatmates', ChoiceType::class, ['choices' =>$total_flatmates,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>''])
	   ->add('bond_status', ChoiceType::class, ['choices' =>$bond_status,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>''])
	   ->add('bills_status', ChoiceType::class, ['choices' =>$bills_status,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>''])
   	   ->add('room_type', ChoiceType::class, ['choices' =>$room_type,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>''])
	   ->add('room_furnishings', ChoiceType::class, ['choices' =>$room_furnishings,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>''])
	   ->add('room_bathroom', ChoiceType::class, ['choices' =>$room_bathroom,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>''])
	   ->add('flatmatespref', ChoiceType::class, ['choices' =>$flatmatespref_status,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>''])
	   ->add('property_address', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control')))
	   ->add('room_rent', TextType::class, array('required'=>false,'label'=>false,'attr' => array('class' => 'form-control')))
	   ->add('minimum_stay_length', ChoiceType::class, ['choices' =>$length_of_stay,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>''])
	   ->add('room_furnishings_features', ChoiceType::class, ['choices' => $room_furnishings_feature,'expanded'  => false,'label'=>false,'attr' => array('class' => 'form-control'),'multiple'  => true,])
	   ->add('about_flatmates', TextareaType::class, array('label'=>false,'attr' => array('class' => 'form-control')))
       ->add('about_living_property', TextareaType::class, array('label'=>false,'attr' => array('class' => 'form-control')))
	   ->add('lease_start_date', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control datepicker')))
	   ->add('available_date', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control datepicker')))
	   ->add('lease_end_date', TextType::class, array('label'=>false,'required'=>false,'attr' => array('class' => 'form-control datepicker')))
	   ->add('property_tenant', TextType::class, array('label'=>false,'attr' => array('maxlength'=>'2','class' => 'form-control')))
	   ->add('property_status',ChoiceType::class,array('label'=>false,'attr' => array('class' => 'radiobuttons'),'choices' => ['Yes' => '1','No' => '0'],'data'=>'1','choices_as_values' => true,'multiple'=>false,'expanded'=>true))
	   ->add('property_description', TextareaType::class, array('required'=>false,'label'=>false,'attr' => array('class' => 'form-control ckeditor')))
	   ->add('Save', SubmitType::class, array('label'=> 'Save', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))
	   ->getForm();        
	    # Handle form response
        $form->handleRequest($request);	  	  
	
	   if($form->isSubmitted() ){	
	    
	        $property                  = new AdminProperty();
	      	$property_title            = $form['property_title']->getData();
			$property_type             = $form['property_type']->getData();
			$property_url_title        = preg_replace('/\s+/', '_', $property_title);
			$property_address          = $form['property_address']->getData();
			$property_bedrooms         = $form['property_bedrooms']->getData();	
			$property_bathrooms        = $form['property_bathrooms']->getData();
			$property_parking          = $form['property_parking']->getData();	
			$property_internet         = $form['property_internet']->getData();
			$total_flatmates           = $form['total_flatmates']->getData();	
			$bond_status               = $form['bond_status']->getData();
			$bills_status              = $form['bills_status']->getData();	
			$flatmatespref             = $form['flatmatespref']->getData();
			$minimum_stay_length       = $form['minimum_stay_length']->getData();
			$room_furnishings_features = $form['room_furnishings_features']->getData();
			$about_flatmates           = $form['about_flatmates']->getData();
			$about_living_property     = $form['about_living_property']->getData();
			$property_tenant           = $form['property_tenant']->getData();	
			$property_startdate        = $form['lease_start_date']->getData();
			$property_endate           = $form['lease_end_date']->getData();	
			$property_sttaus           = $form['property_status']->getData();	
			$property_desc             = $form['property_description']->getData();
			
			$property_available_date   = $form['available_date']->getData();
			
		//	$property_title_exists = $this->getDoctrine()->getRepository('AppBundle:AdminProperty')->TitleExists($property_title);
			 $time  = new \DateTime(date("Y-m-d H:i:s"));
			
			    $property->setPropertyTitle($property_title);
			    $property->setPropertyType($property_type);
			    $property->setPropertyBedrooms($property_bedrooms);
			    $property->setPropertyBathrooms($property_bathrooms);
			    $property->setPropertyParking($property_parking);
			    $property->setPropertyInternet($property_internet);
			    $property->setTotalFlatmates($total_flatmates);
			    $property->setBondStatus($bond_status);
			    $property->setBillsStatus($bills_status);
			    $property->setFlatmatesPreference($flatmatespref);
			    $property->setAboutFlatmates($about_flatmates);
			    $property->setAboutLivingProperty($about_living_property);
			    $property->setPropertyUrlTitle($property_url_title);
				$property->setPropertyTenant($property_tenant);
				$property->setMinimumStayLength($minimum_stay_length);
				
				
				
				$room_furnishings_features_list='';
				if($room_furnishings_features){
				    foreach($room_furnishings_features as $features_key =>$features_value)
				        $room_furnishings_features_list.=$features_value.',';
				    
				    $room_furnishings_features_list = rtrim($room_furnishings_features_list,",");
				    
				   $property->setRoomFurnishingsFeatures($room_furnishings_features_list); 
				  }
				
				$property->setPropertyDescription($property_desc);
				
				if($property_address)
				$property->setPropertyAddress($property_address);
				else
				$property->setPropertyAddress('');
				
				$property->setLeaseStartDate($property_startdate);
				$property->setLeaseEndDate($property_endate);
				$property->setAvailableDate($property_available_date);
				
				$property->setPropertyStatus($property_sttaus);		
				$property->setIpAddress($request->getClientIp());
				$property->setEntryTime($time);		
			
				# finally add data in database
				$sn = $this->getDoctrine()->getManager();      
				$sn->persist($property);
				$sn->flush();
				$property_id = $property->getId();
			  	
			      
				// add property rooms
				$propertyrooms    = new AdminPropertyRooms();
				$rooms_sn         = $this->getDoctrine()->getManager(); 
				$room_type        = $request->request->get('prop_room_type');
		        $room_furnishings = $request->request->get('prop_room_furnishings');
		        $room_bathroom    = $request->request->get('prop_room_bathroom');
		        $room_rent        = $request->request->get('prop_room_rent');
		        if($room_type){
		            foreach($room_type as $roomkey =>$room_type_value){
		                $prop_room_furnishings = $room_furnishings[$roomkey];
		                $prop_room_bathroom    = $room_bathroom[$roomkey];
		                $prop_room_rent        = $room_rent[$roomkey];
		                
		                $propertyrooms->setPropertyId($property_id);
		                $propertyrooms->setRoomType($room_type_value);
		                $propertyrooms->setRoomFurnishings($prop_room_furnishings);
		                $propertyrooms->setRoomBathroom($prop_room_bathroom);
		                $propertyrooms->setRoomRent($prop_room_rent);
		                $propertyrooms->setIpAddress($request->getClientIp());
			         	$propertyrooms->setEntryTime($time);	
		                $rooms_sn->persist($propertyrooms);
			       	    $rooms_sn->flush();
				        $rooms_sn->clear();   
		            }
		            
		        }
                
                if($form['room_type']->getData() && $form['room_rent']->getData()){
                    $spropertyrooms    = new AdminPropertyRooms();
			     	$srooms_sn         = $this->getDoctrine()->getManager();
				
                    $room_type_s        = $form['room_type']->getData();
		            $room_furnishings_s = $form['room_furnishings']->getData();
		            $room_bathroom_s    = $form['room_bathroom']->getData();
		            $room_rent_s        = $form['room_rent']->getData();
                    
                    $spropertyrooms->setPropertyId($property_id);
		            $spropertyrooms->setRoomType($room_type_s);
		            $spropertyrooms->setRoomFurnishings($room_furnishings_s);
		            $spropertyrooms->setRoomBathroom($room_bathroom_s);
		            $spropertyrooms->setRoomRent($room_rent_s);
		            $spropertyrooms->setIpAddress($request->getClientIp());
			        $spropertyrooms->setEntryTime($time);	
		            $srooms_sn->persist($spropertyrooms);
			       	$srooms_sn->flush();
                }
                
		        $session = new Session();
	           if($session->get('s_temp_images')){
	               $propertyimages = new AdminPropertyImages();
	               $images_sn         = $this->getDoctrine()->getManager(); 
	               $session_images = $session->get('s_temp_images');
	               foreach($session_images as $image_name){
	                    $propertyimages->setPropertyId($property_id);
		                $propertyimages->setImageName($image_name);
		                $propertyimages->setIpAddress($request->getClientIp());
			         	$propertyimages->setEntryTime($time);	
		                $images_sn->persist($propertyimages);
			       	    $images_sn->flush();
				        $images_sn->clear(); 
	               }
	           $session->remove('s_temp_images');	    
	           }
		        
				     
				$this->addFlash('success', 'Property has been added successfully');
				return $this->redirectToRoute('view_property');
	   }
	   return $this->render('admin/property/add.html.twig',array('form' => $form->createView() ));
    }


/**
 * @Route("/property/setup_clean_date", name="ajax_property_cleandate")
 */ 
 public function CleandatePropertyAction(Request $request)    
 {
	   $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		  
    if ($request->isXMLHttpRequest()) {   
	    $propertycleandates   = new AdminPropertyCleanDates(); 
	    $cleandate     = $request->request->get('cleandate');
		$clean_notes   = $request->request->get('clean_notes');
		$property_ids  = $request->request->get('property_id');
		$sn = $this->getDoctrine()->getManager();   
		if($property_ids){
		 foreach($property_ids as $propertykey => $property_id){
			    $time  = new \DateTime(date("Y-m-d H:i:s"));
						
			    $propertycleandates->setCleaningDate($cleandate);
			    if($clean_notes)
				$propertycleandates->setNotes($clean_notes);
				else
				$propertycleandates->setNotes('');
				
				$propertycleandates->setPropertyId($property_id);
				$propertycleandates->setIpAddress($request->getClientIp());
				$propertycleandates->setEntryTime($time);	
				   
				$sn->persist($propertycleandates);
				$sn ->flush(); 
				$sn->clear();
		    }
			if($sn){
		$this->addFlash('success', 'Cleaning date has been setup successfully');
		return new JsonResponse(array('success' => '1','error'=>'0','message'=>'Cleaning date has been setup successfully'));
				}
		}
        
    }
   else
    return new Response('This is not ajax!', 400);
}



	/**
     * Matches /property/*
	 
	 /**
     * @Route("/property/edit/{slug}", name="edit_property")
     */
    public function editPropertyAction(Request $request,$slug,CommonConfig $CommonConfig)
      {
  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
       $type_of_property   = $CommonConfig->PropertyConfig('type_of_property');
       $property_bedrooms  = $CommonConfig->PropertyConfig('total_bedrooms');
       $property_bathrooms = $CommonConfig->PropertyConfig('total_bathrooms');
       $property_parking   = $CommonConfig->PropertyConfig('parking_status');
       $property_internet  = $CommonConfig->PropertyConfig('internet_status'); 
       $total_flatmates    = $CommonConfig->PropertyConfig('total_flatmates');
       $bond_status        = $CommonConfig->PropertyConfig('bond_status');
       $bills_status       = $CommonConfig->PropertyConfig('bills_status');
       $room_type          = $CommonConfig->PropertyConfig('roomtypes_status');
       $room_furnishings   = $CommonConfig->PropertyConfig('roomfurnishings_status');
       $room_bathroom      = $CommonConfig->PropertyConfig('bathrooms_status');
       $room_furnishings_feature  = $CommonConfig->PropertyConfig('room_furnishing_features');
       $flatmatespref_status      = $CommonConfig->PropertyConfig('flatmatespref_status');
       $length_of_stay            = $CommonConfig->PropertyConfig('length_of_stay');     

          
	    $property_info = $this->getDoctrine()->getRepository('AppBundle:AdminProperty')->findBy(array("property_id"=>$slug));
	    if(!$property_info)
	    return  $this->redirectToRoute('view_property');	
	   
       if($property_info){
            $active_occupants          = $this->getDoctrine()->getRepository('AppBundle:AdminUsers')->PropertyActiveTenants($slug);
           
	       $property_title            = $property_info[0]->getPropertyTitle();
	       $property_desc             = $property_info[0]->getPropertyDescription();
		   $property_address          = $property_info[0]->getPropertyAddress();
		   $property_start_date       = $property_info[0]->getLeaseStartDate();
		   $property_end_date         = $property_info[0]->getLeaseEndDate();
		   $property_status           = $property_info[0]->getPropertyStatus();
		   $property_tenant           = $property_info[0]->getPropertyTenant();
		   $sel_type_of_property      = $property_info[0]->getPropertyType();
		   $sel_property_bedrooms     = $property_info[0]->getPropertyBedrooms();
		   $sel_property_bathrooms    = $property_info[0]->getPropertyBathrooms();
		   $sel_property_parking      = $property_info[0]->getPropertyParking();
		   $sel_property_internet     = $property_info[0]->getPropertyInternet();
		   $sel_total_flatmates       = $property_info[0]->getTotalFlatmates();
		   $sel_bond_status           = $property_info[0]->getBondStatus();
		   $sel_bill_status           = $property_info[0]->getBillsStatus();
		   $sel_flatmatespref         = $property_info[0]->getFlatmatesPreference();
		   $sel_minimum_stay_length   = $property_info[0]->getMinimumStayLength();
		   $sel_about_flatmates       = $property_info[0]->getAboutFlatmates();
		   $sel_about_living_property =  $property_info[0]->getAboutLivingProperty();
		   $sel_room_furnishings_features = $property_info[0]->getRoomFurnishingsFeatures();
		   $sel_available_date 				=$property_info[0]->getAvailableDate();
		   
		   if($sel_room_furnishings_features){
		       
		       $sel_room_furnishings_features = explode(",",$sel_room_furnishings_features);
		   }else
		    $sel_room_furnishings_features=array();
		   if($property_info[0]->getLeaseStartDate())
		   $property_startdate= $property_info[0]->getLeaseStartDate();
		   else
		   $property_startdate ='';
		   if($property_info[0]->getLeaseEndDate())
		   $property_enddate  = $property_info[0]->getLeaseEndDate();
		   else
		   $property_enddate  ='';
		   
		   $property_status   = $property_info[0]->getPropertyStatus();
	   } 
	   
	   
	     $property_rooms = $this->getDoctrine()->getRepository('AppBundle:AdminPropertyRooms')->AllPropertyRooms($slug);
	   
	     $property   = new AdminProperty();  
	    
	    $form   = $this->createFormBuilder()
       	 ->add('property_title', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$property_title)))
       	 ->add('property_type', ChoiceType::class, ['choices' =>$type_of_property,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>$sel_type_of_property])
	    ->add('property_bedrooms', ChoiceType::class, ['choices' =>$property_bedrooms,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>$sel_property_bedrooms])
     	->add('property_bathrooms', ChoiceType::class, ['choices' =>$property_bathrooms,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>$sel_property_bathrooms])
	    ->add('property_parking', ChoiceType::class, ['choices' =>$property_parking,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>$sel_property_parking])
        ->add('property_internet', ChoiceType::class, ['choices' =>$property_internet,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>$sel_property_internet])
		->add('total_flatmates', ChoiceType::class, ['choices' =>$total_flatmates,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>$sel_total_flatmates])
		->add('bond_status', ChoiceType::class, ['choices' =>$bond_status,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>$sel_bond_status])
		->add('bills_status', ChoiceType::class, ['choices' =>$bills_status,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>$sel_bill_status])
	    ->add('flatmatespref', ChoiceType::class, ['choices' =>$flatmatespref_status,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>$sel_flatmatespref])
	    ->add('property_address', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$property_address)))
	    ->add('minimum_stay_length', ChoiceType::class, ['choices' =>$length_of_stay,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>$sel_minimum_stay_length])
	    ->add('room_furnishings_features', ChoiceType::class, ['choices' => $room_furnishings_feature,'expanded'  => false,'data'=>$sel_room_furnishings_features,'label'=>false,'attr' => array('class' => 'form-control'),'multiple'  => true,])
        ->add('about_flatmates', TextareaType::class, array('label'=>false,'data'=>$sel_about_flatmates,'attr' => array('class' => 'form-control')))
        ->add('about_living_property', TextareaType::class, array('label'=>false,'data'=>$sel_about_living_property,'attr' => array('class' => 'form-control')))
	    ->add('lease_start_date', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control datepicker','value'=>$property_start_date)))
	    
		->add('available_date', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control datepicker','value'=>$sel_available_date)))
		->add('lease_end_date', TextType::class, array('label'=>false,'required'=>false,'attr' => array('class' => 'form-control datepicker','value'=>$property_end_date)))
		->add('property_tenant', TextType::class, array('label'=>false,'attr' => array('maxlength'=>'2','class' => 'form-control','value'=>$property_tenant)))
		->add('property_status',ChoiceType::class,array('label'=>false,'attr' => array('class' => 'radiobuttons'),'choices' => ['Yes' => '1','No' => '0'],'data'=>$property_status,'choices_as_values' => true,'multiple'=>false,'expanded'=>true))
		->add('property_description', TextareaType::class, array('required'=>false,'data'=>$property_desc,'label'=>false,'attr' => array('class' => 'form-control ckeditor')))
	   	->add('Save', SubmitType::class, array('label'=> 'Save', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))
		->getForm();  
	    # Handle form response
      $form->handleRequest($request);	  
        	
      if($form->isSubmitted() ){	
	        $entityManager             = $this->getDoctrine()->getManager();
            $property                  = $entityManager->getRepository(AdminProperty::class)->find(array('property_id'=>$slug));
	       
	       	$property_title            = $form['property_title']->getData();
			$property_type             = $form['property_type']->getData();
			$property_url_title        = preg_replace('/\s+/', '_', $property_title);
			$property_address          = $form['property_address']->getData();
			$property_bedrooms         = $form['property_bedrooms']->getData();	
			$property_bathrooms        = $form['property_bathrooms']->getData();
			$property_parking          = $form['property_parking']->getData();	
			$property_internet         = $form['property_internet']->getData();
			$total_flatmates           = $form['total_flatmates']->getData();	
			$bond_status               = $form['bond_status']->getData();
			$bills_status              = $form['bills_status']->getData();	
			$flatmatespref             = $form['flatmatespref']->getData();
			$minimum_stay_length       = $form['minimum_stay_length']->getData();
			$room_furnishings_features = $form['room_furnishings_features']->getData();
			$about_flatmates           = $form['about_flatmates']->getData();
			$about_living_property     = $form['about_living_property']->getData();
			$property_tenant           = $form['property_tenant']->getData();	
			$property_startdate        = $form['lease_start_date']->getData();
			$property_endate           = $form['lease_end_date']->getData();	
			$property_sttaus           = $form['property_status']->getData();	
			$property_desc             = $form['property_description']->getData();
			$available_date            = $form['available_date']->getData();
			$time                      = new \DateTime(date("Y-m-d H:i:s"));
			
			$property->setPropertyTitle($property_title);
            $property->setPropertyType($property_type);
            $property->setPropertyBedrooms($property_bedrooms);
            $property->setPropertyBathrooms($property_bathrooms);
            $property->setPropertyParking($property_parking);
            $property->setPropertyInternet($property_internet);
            $property->setTotalFlatmates($total_flatmates);
            $property->setBondStatus($bond_status);
            $property->setBillsStatus($bills_status);
            $property->setFlatmatesPreference($flatmatespref);
            $property->setAboutFlatmates($about_flatmates);
            $property->setAboutLivingProperty($about_living_property);
            $property->setPropertyUrlTitle($property_url_title);
            $property->setPropertyTenant($property_tenant);
            $property->setMinimumStayLength($minimum_stay_length);
			$room_furnishings_features_list='';
			if($room_furnishings_features){
			   foreach($room_furnishings_features as $features_key =>$features_value)
			        $room_furnishings_features_list.=$features_value.',';
				    
				   $room_furnishings_features_list = rtrim($room_furnishings_features_list,",");
				   $property->setRoomFurnishingsFeatures($room_furnishings_features_list); 
				}
				
				if($property_desc)
				$property->setPropertyDescription($property_desc);
				else
				$property->setPropertyDescription('');
				if($property_address)
				$property->setPropertyAddress($property_address);
				else
				$property->setPropertyAddress('');
				
				
				$property->setAvailableDate($available_date);
				
				
				$property->setLeaseStartDate($property_startdate);
				$property->setLeaseEndDate($property_endate);
				
				$property->setIpAddress($request->getClientIp());
				$property->setPropertyStatus($property_sttaus);	
				
				// if property end date is entered then make inactive
			    /* if($property_endate!='' || $property_endate!='0000-00-00' ){
				      $property->setPropertyStatus(0);	   
				 // make tenants of this property inactive
				 $active_occupants = $this->getDoctrine()->getRepository(AdminUsers::class)->PropertyActiveTenants($slug);
				 
				 if($active_occupants){
				     foreach($active_occupants as $proptenants){
				         $user_id = $proptenants['account_id'];
				         $property_tenant_info  = $entityManager->getRepository(AdminUsers::class)->find(array('account_id'=>$user_id));
				         $property_tenant_info->setCustomerAccountStatus(0);
				     }
				     
				 }
				 
				}else{
				    */
				    
				//}
				
				$property->setModifiedTime($time);		
				$entityManager->flush();
			  	
                
				// add property rooms
				$propertyrooms    = new AdminPropertyRooms();
				$rooms_sn         = $this->getDoctrine()->getManager(); 
				$room_type        = $request->request->get('prop_room_type');
		        $room_furnishings = $request->request->get('prop_room_furnishings');
		        $room_bathroom    = $request->request->get('prop_room_bathroom');
		        $room_rent        = $request->request->get('prop_room_rent');
		        if($room_type){
		               
		                $em = $this->getDoctrine()->getManager();
                        $RAW_QUERY = 'DELETE FROM property_rooms where property_rooms.property_id = '.$slug.';';
                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                        $statement->execute();
		            
		            
		            foreach($room_type as $roomkey =>$room_type_value){
		                $prop_room_furnishings = $room_furnishings[$roomkey];
		                $prop_room_bathroom    = $room_bathroom[$roomkey];
		                $prop_room_rent        = $room_rent[$roomkey];
		                
		                $propertyrooms->setPropertyId($slug);
		                $propertyrooms->setRoomType($room_type_value);
		                $propertyrooms->setRoomFurnishings($prop_room_furnishings);
		                $propertyrooms->setRoomBathroom($prop_room_bathroom);
		                $propertyrooms->setRoomRent($prop_room_rent);
		                $propertyrooms->setIpAddress($request->getClientIp());
			         	$propertyrooms->setEntryTime($time);	
		                $rooms_sn->persist($propertyrooms);
			       	    $rooms_sn->flush();
				        $rooms_sn->clear();   
		            }
		            
		        }
                
		        $session = new Session();
	           if($session->get('s_temp_images')){
	               $propertyimages = new AdminPropertyImages();
	               $images_sn         = $this->getDoctrine()->getManager(); 
	               $session_images = $session->get('s_temp_images');
	               foreach($session_images as $image_name){
	                    $propertyimages->setPropertyId($slug);
		                $propertyimages->setImageName($image_name);
		                $propertyimages->setIpAddress($request->getClientIp());
			         	$propertyimages->setEntryTime($time);	
		                $images_sn->persist($propertyimages);
			       	    $images_sn->flush();
				        $images_sn->clear(); 
	               }
	            $session->remove('s_temp_images');	
	             
	           }
		        
				     
				$this->addFlash('success', 'Property has been updated successfully');
				return $this->redirectToRoute('view_property');
	   }
	 
	   
	return $this->render('admin/property/edit.html.twig',[
            'form' => $form->createView(),
			'id'=>	$slug,	
			'property_rooms'=>$property_rooms,
			'active_occupants'=>count($active_occupants),
			'room_types'=>$room_type,
			'room_furnishings_list'=>$room_furnishings,
			'room_bathroom_list'=>$room_bathroom
        ]);
    }


	/**
     * Matches /property/*
	 /**
     *  @Route("/property/upload_images", name="upload_property_images")
     */

    public function PropertyImagesAction(Request $request,CommonConfig $CommonConfig)
    {
		  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		  
      $output='';
      $cache_images=array();
      
     @set_time_limit(5 * 60);
     $targetDir = $this->getParameter('property_directory');
     $cleanupTargetDir = false; 
     $maxFileAge = 5 * 3600; 
     
   
    if (isset($_REQUEST["name"])) {
    	$fileName = $_REQUEST["name"];
    } elseif (!empty($_FILES)) {
    	$fileName = $_FILES["file"]["name"];
    } else {
    	$fileName = uniqid("file_");
    }
    
   $filename = @basename($_FILES['file']['name']);
   $file_ext = $ext = pathinfo($filename, PATHINFO_EXTENSION);
   $dest_filename = md5(uniqid(rand(), true)) . '.' . $file_ext;

    
    $filePath = $targetDir . DIRECTORY_SEPARATOR . $dest_filename;
    
    // Chunking might be enabled
    $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
    $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;

// Remove old temp files	
if ($cleanupTargetDir) {
	if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
	}

	while (($file = readdir($dir)) !== false) {
		$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

		// If temp file is current file proceed to the next
		if ($tmpfilePath == "{$filePath}.part") {
			continue;
		}

		// Remove temp file if it is older than the max age and is not the current file
		if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
			@unlink($tmpfilePath);
		}
	}
	closedir($dir);
}	


// Open temp file
if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
	die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
}

if (!empty($_FILES)) {
	if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
	}

	// Read binary input stream and append it to temp file
	if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
	}
} else {	
	if (!$in = @fopen("php://input", "rb")) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
	}
}

while ($buff = fread($in, 4096)) {
	fwrite($out, $buff);
}

@fclose($out);
@fclose($in);

// Check if file has been uploaded
if (!$chunks || $chunk == $chunks - 1) {
	// Strip the temp .part suffix off 
	rename("{$filePath}.part", $filePath);


            $session = new Session();		 
			 
		     $session->set('user_id', '1');
		     
		     if(!$session->get('s_temp_images')){
		          $cache_images[$dest_filename]= $dest_filename;
                  $session->set('s_temp_images', $cache_images); 
		         
		     }
		     else{
		        $other_images = array();
                $other_images[$dest_filename]= $dest_filename;
                $session_images=$session->get('s_temp_images');
                foreach($session_images as $sessimg_key => $sessionimgval){
                  $other_images[$sessionimgval]= $sessionimgval;
                }
                $session->set('s_temp_images', $other_images); 
		     }
       }

        // Return Success JSON-RPC response
        die('{"jsonrpc" : "done", "result" :done, "id" : "id"}');
    }
    

	/**
     * Matches /property/*
	 /**
     *  @Route("/property/send_mail", name="send_property_mail_users")
     */

    public function MailPropertyAction(Request $request,CommonConfig $CommonConfig)
    {
		  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		  
     if ($request->isXMLHttpRequest()) {   
	    $property_id      = $request->request->get('property_id');
	    $mail_subject     = $request->request->get('mail_subject');
	    $mail_cc          = $request->request->get('mailcc');
	    $mail_content     = $request->request->get('mailcontent');
	    
	    if($_FILES['file']!=''){
	    $attachment_file   =$_FILES['file'];
	    }
	    else
	    $attachment_file   ='';
	    
		if($property_id){
	        $entityManager = $this->getDoctrine()->getManager();
        	if($property_id){
        	    $property_ids = explode(",",$property_id);
        	    foreach($property_ids as $ppkey => $property_id_val){
        	        if($property_id_val >0){
        	      
        	         $users_info   = $entityManager->getRepository(AdminUsers::class)->PropertyActiveTenants($property_id_val);
        	         if( $users_info){
	                    foreach( $users_info as $key => $user_row){
	                       $user_info   = $entityManager->getRepository(AdminUsers::class)->find($user_row['account_id']); 
	                       $user_email  = $user_info->getCustomerEmail();
	                        if($user_email)
	                       $CommonConfig->SendMail($user_email, $mail_subject, $mail_content,'info@prestige.22creative.in','Prestige Team',$mail_cc,$attachment_file);
	                    }
	                 }
	              }
        	   }	
        	} 
        		$this->addFlash('success', 'Mail has been send successfully');
        		return new JsonResponse(array('success' => '1','error'=>'0','message'=>'mail send successfully'));
		    }
        }
    else
           return new Response('This is not ajax!', 400);
    }
	



 /**
     * @Route("/property/delete/{slug}", name="admin_delete_property")
     */
    public function delpropertyAction($slug)
    {
		  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		  
	    $entityManager = $this->getDoctrine()->getManager();
		$repository    = $this->getDoctrine()->getRepository(AdminProperty::class);
		$property      = $repository->find($slug);
		$entityManager->remove($property);
		$entityManager->flush();			
		
		// Remove property rooms
		$em = $this->getDoctrine()->getManager();
        $RAW_QUERY = 'DELETE FROM property_rooms where property_rooms.property_id = '.$slug.';';
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        
        // Remove property cleaning dates
		$em1 = $this->getDoctrine()->getManager();
        $RAW_QUERY1 = 'DELETE FROM property_cleaning_dates where property_cleaning_dates.property_id = '.$slug.';';
        $statement1 = $em1->getConnection()->prepare($RAW_QUERY1);
        $statement1->execute();
        
         // Remove property images
		$em2 = $this->getDoctrine()->getManager();
        $RAW_QUERY2 = 'DELETE FROM property_images where property_images.property_id = '.$slug.';';
        $statement2 = $em1->getConnection()->prepare($RAW_QUERY2);
        $statement2->execute();       
	
	     // Remove property tenants
		$em3 = $this->getDoctrine()->getManager();
        $RAW_QUERY3 = 'DELETE FROM users where users.assigned_property_id = '.$slug.' AND users.assigned_property_id>0;';
        $statement3 = $em1->getConnection()->prepare($RAW_QUERY3);
        $statement3->execute();       
        

	     // Remove property user suggestions
		$em4 = $this->getDoctrine()->getManager();
        $RAW_QUERY4 = 'DELETE FROM users where users_suggestions.property_id = '.$slug.' ;';
        $statement4 = $em1->getConnection()->prepare($RAW_QUERY4);
        $statement4->execute();       


		
		$this->addFlash('success', 'Property has been deleted successfully');
		return $this->redirectToRoute('view_property');			
    }			


	
}
