<?php
namespace AppBundle\Controller;
use AppBundle\Entity\AdminGallery; 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response; 
class GalleryController extends Controller
{ 
/**
 * @Route("/gallery", name="gallery")
 */ 
  public function indexAction()
    {			
	$gallery = new AdminGallery();   
	$all_images = $this->getDoctrine()
            ->getRepository(AdminGallery::class)
            ->AllImages();
	
	    return $this->render('default/gallery.html.twig',[
            'all_images' => $all_images
        ]);
		
	   
    }
}
