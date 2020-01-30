<?php

// src/AppBundle/Controller/PagesController.php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\AdminPages; 

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;





class PagesController extends Controller

{

    /**

    

     * @Route("/pages/{page_url}/{page_id}" ,name="pages")

    */

    public function indexAction($page_url,$page_id)

    {  
		$page_info = $this->getDoctrine()->getRepository(AdminPages::class)->PageInfo($page_id);
		if($page_info){
		
			$page_title = $page_info->getPageTitle();
			$page_description = $page_info->getPageDescription();
		
	  return $this->render('default/page_info.html.twig',['page_title'=>$page_title,'page_description'=>$page_description]);
	  
		}
		else{
		return $this->redirectToRoute('home_page');		
		}

	

    }

    

	

}