<?php
namespace AppBundle\Controller\RoomPanel;
use AppBundle\Entity\AdminGallery; 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 
use Symfony\Component\Form\Extension\Core\Type\FileType ;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 *
 * @Route("/roompanel")
 */
class GalleryController extends Controller
{ 
    /**
    * @Route("/gallery", name="view_gallery")
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
	
	$count_gallery = $this->getDoctrine()
           			   ->getRepository(AdminGallery::class)
           			   ->CountGallery();	
					 
	$limit = 10;
    $maxPages = ceil($count_gallery / $limit);
    $thisPage = $page;

 
  	$gallery = new AdminGallery();   
	$all_images = $this->getDoctrine()
            ->getRepository(AdminGallery::class)
            ->AllImages($thisPage,$limit);
			
			
 $pagination = $this->render('pagination.html.twig',[
            'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'view_gallery',
           ]);
					
	return $this->render('admin/gallery/view.html.twig',[
            'total_gallery'=>$count_gallery,'all_images' => $all_images,'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'view_gallery',
        ]);			
	
    }
	
/**
     * Matches /gallery/*
     *
     * @Route("/gallery/add", name="admin_add_images")
    */	
	
  public function addimagesAction(Request $request)
    {
  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
	   $gallery = new AdminGallery();   
	   $form = $this->createFormBuilder($gallery)
	          ->add('gallery_title', TextType::class, array('attr' => array('class' => 'form-control')))
	          ->add('photo', FileType::class, [
					'mapped' => false,
					'attr' => array('class' => 'form-control'),
					'constraints' => [
						new File([
							'maxSize' => '1024k',
							'mimeTypes' => [
								'image/jpeg',
								'image/png',
								'image/jpg',
								'image/gif',
							],							              		    
							'mimeTypesMessage' => 'Please upload a valid image file',
						])
					  ],
                   ])		        
			  ->add('Save', SubmitType::class, array('label'=> 'Save', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))
				 ->getForm();        
	    # Handle form response
        $form->handleRequest($request);	  
		  	
	  if($form->isSubmitted() ){	
	    
			$gallery_title  = $form['gallery_title']->getData();
			$gallery_photo  = $form['photo']->getData();	
			
			if($gallery_photo){
	        $event_originalFilename = pathinfo($gallery_photo->getClientOriginalName(), PATHINFO_FILENAME);		   
		    //$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $event_originalFilename);
            $newFilename = uniqid().'.'.$gallery_photo->guessExtension();
				
			try {
                    $gallery_photo->move(
                        $this->getParameter('gallery_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
			}
			else
			$newFilename='';
			
		   
			$time  = new \DateTime(date("Y-m-d H:i:s"));
			$gallery->setGalleryTitle($gallery_title);
			$gallery->setPhoto($newFilename);
			$gallery->setEntryTime($time);	
			
			# finally add data in database
			$sn = $this->getDoctrine()->getManager();      
			$sn -> persist($gallery);
			$sn -> flush();
			$this->addFlash('success', 'Image has been added successfully');
			return $this->redirectToRoute('view_gallery');
	   }
	   return $this->render('admin/gallery/add.html.twig',array('form' => $form->createView() ));

    }



	/**
     * Matches /gallery/*
	 
	 /**
     * @Route("/gallery/edit/{slug}", name="edit_image")
     */
    public function editAction(Request $request,$slug)
      {
		   $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login'); 
	    $image_info = $this->getDoctrine()->getRepository('AppBundle:AdminGallery')->findBy(array("id"=>$slug));
	   
	   if(!$image_info)
	    return  $this->redirectToRoute('view_gallery');	
	   
       if(!$image_info[0]->getGalleryTitle())
	      $gallery_title ='';
	   else	   	  
	       $gallery_title = $image_info[0]->getGalleryTitle();
		      
	  
	   $gallery = new AdminGallery();   
	   $form = $this->createFormBuilder($gallery)
	            ->add('gallery_title', TextType::class, array('attr' => array('class' => 'form-control','value'=>$gallery_title)))
	   			->add('photo', FileType::class, [
					'mapped' => false,					
					'required'=>false,
					'attr' => array('class' => 'form-control'),
					'constraints' => [
						new File([
							'maxSize' => '1024k',
							'mimeTypes' => [
								'image/jpeg',
								'image/png',
								'image/jpg',
								'image/gif',
							],
							'mimeTypesMessage' => 'Please upload a valid image file',
						])
					  ],
                   ])
				 ->add('Save', SubmitType::class, array('label'=> 'Save', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))
				 ->getForm();        
	    # Handle form response
        	$form->handleRequest($request);	  
		  
	
	   if($form->isSubmitted() ){	
	        $entityManager = $this->getDoctrine()->getManager();
            $events = $entityManager->getRepository(AdminGallery::class)->find($slug);
		  
			$gallery_title  = $form['gallery_title']->getData();
			$gallery_photo  = $form['photo']->getData();	
			$time  = new \DateTime(date("Y-m-d H:i:s"));
			
			if($gallery_photo){
	        $gallery_originalFilename = pathinfo($gallery_photo->getClientOriginalName(), PATHINFO_FILENAME);		   
		    //$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $event_originalFilename);
            $newFilename = uniqid().'.'.$gallery_photo->guessExtension();
				
			try {
                    $gallery_photo->move(
                        $this->getParameter('gallery_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
			
			$events->setGalleryTitle($gallery_title);
			$events->setPhoto($newFilename);
			$events->setEntryTime($time);		
			$entityManager->flush();	
			}
			else{
			$events->setGalleryTitle($gallery_title);
			$events->setEntryTime($time);		
			$entityManager->flush();	
			}
		 	
			$this->addFlash('success', 'Image has been updated successfully');
			return $this->redirectToRoute('view_gallery');
	   }
	   
	   
	return $this->render('admin/gallery/edit.html.twig',[
            'form' => $form->createView(),
			'id'=>	$slug	
        ]);
    }



 /**
     * @Route("/gallery/delete/{slug}", name="admin_delete_image")
     */
    public function deleventAction($slug)
    {
		  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
	
	    $entityManager = $this->getDoctrine()->getManager();
		$repository    = $this->getDoctrine()->getRepository(AdminGallery::class);
		$gallery         = $repository->find($slug);
		$entityManager->remove($gallery);
		$entityManager->flush();	
		
		$this->addFlash('success', 'Image has been deleted successfully');
		return $this->redirectToRoute('view_gallery');		
	
    }			

	
}
