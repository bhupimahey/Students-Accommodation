<?php

namespace AppBundle\Controller\RoomPanel;

use AppBundle\Entity\AdminBlog; 



use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response; 

use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType; 

use Symfony\Component\Form\Extension\Core\Type\FileType ;

use Symfony\Component\Form\Extension\Core\Type\DateType ;

use Symfony\Component\Validator\Constraints\File;



use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use AppBundle\Entity\AdminBlogCategory;



use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

use Symfony\Component\HttpFoundation\Session\Session;

use Doctrine\ORM\Tools\Pagination\Paginator;





/**

 *

 * @Route("/roompanel")

 */

class BlogController extends Controller

{ 

    /**

    * @Route("/blog", name="view_blog")

    */

	

  public function indexAction()

    {
  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
	 if(isset($_GET['page']))		 

		$page = $_GET['page'];	

	 else

	    $page ='1'; 		

		

	$blog     = new AdminBlog();  

	

	$count_blog = $this->getDoctrine()

           			 ->getRepository(AdminBlog::class)

           			 ->CountBlog();			



	$limit = 10;

    $maxPages = ceil($count_blog / $limit);

    $thisPage = $page;

		

		  	

	$all_blog = $this->getDoctrine()

           			->getRepository(AdminBlog::class)

         			->AllBlog($thisPage,$limit);

     

	  $pagination = $this->render('pagination.html.twig',[

            'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'view_blog',

        ]);

			

	return $this->render('admin/blog/view.html.twig',[

            'all_blog' => $all_blog,'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'view_blog'

        ]);



    }

	

   /**

     * Matches /blog/*

     *

     * @Route("/blog/add", name="add_blog")

    */	

	

  public function addblogAction(Request $request)

    {
  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
  	    $session = new Session();

	    if(!$session->get('user_id'))

		  return $this->redirectToRoute('admin_login');

	   

	   $blog   = new AdminBlog();   

	   $form   = $this->createFormBuilder($blog)

       	 	      ->add('blog_title', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control')))

				  ->add('blog_category', EntityType::class,  [

   							 'class' => 'AppBundle:AdminBlogCategory',

							 'placeholder' => 'Choose Category',

							 'label'=>false,

							 'attr' => array('class' => 'form-control'),

   							 'choice_label' => function ($category) {

     			  						 return $category->getBlogCategoryTitle();

   						 		},

							])

				  ->add('blog_description', TextareaType::class, array('required'=>false,'label'=>false,'attr' => array('class' => 'form-control ckeditor')))

	   			  ->add('blog_photo', FileType::class, [

					'mapped' => false,

					'required'=>false,

					'label'=>false,

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

	   

	      	$blog_category   = $form['blog_category']->getData();

			$blog_categoryid = $blog_category->getId();

			$blog_title      = $form['blog_title']->getData();

			$blog_url_title  = preg_replace('/\s+/', '_', $blog_title);

			$blog_desc       = $form['blog_description']->getData();

			$blog_photo      = $form['blog_photo']->getData();				

			

			if($blog_photo){	      

            $newFilename    = uniqid().'.'.$blog_photo->guessExtension();				

			try {

                    $blog_photo->move(

                        $this->getParameter('blog_directory'),

                        $newFilename

                    );

                } catch (FileException $e) {

                    // ... handle exception if something happens during file upload

                }

			}

			else

			   $newFilename='';

			   

		        $time  = new \DateTime(date("Y-m-d H:i:s"));

			    $blog->setBlogTitle($blog_title);

				$blog->setBlogUrlTitle($blog_url_title);

				$blog->setBlogDescription($blog_desc);

				$blog->setBlogCategory($blog_categoryid);

				$blog->setBlogPhoto($newFilename);

				$blog->setIpAddress($request->getClientIp());

				$blog->setEntryTime($time);		

			

			# finally add data in database

			$sn = $this->getDoctrine()->getManager();      

			$sn -> persist($blog);

			$sn -> flush();

			$this->addFlash('success', 'Blog has been added successfully');

			return $this->redirectToRoute('view_blog');

	   }

	   return $this->render('admin/blog/add.html.twig',array('form' => $form->createView() ));

    }







	/**

     * Matches /blog/*

	 

	 /**

     * @Route("/blog/edit/{slug}", name="edit_blog")

     */

    public function editBlogAction(Request $request,$slug)

      {
  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
	    $blog_info = $this->getDoctrine()->getRepository('AppBundle:AdminBlog')->findBy(array("id"=>$slug));

	    if(!$blog_info)

	    return  $this->redirectToRoute('view_blog');	

	   

       if($blog_info){

	       $blog_title    = $blog_info[0]->getBlogTitle();

	       $blog_category = $blog_info[0]->getBlogCategory();

		   $blog_desc     = $blog_info[0]->getBlogDescription();

	   }

		 else{

		  $blog_title=$blog_category=$blog_desc='';	 			 

		 }

		 

	     $blog   = new AdminBlog();  

	     $form   = $this->createFormBuilder($blog)

	   	 	      ->add('blog_title', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$blog_title)))

	

	->add('blog_category', EntityType::class,  [

				  			 'class' => 'AppBundle:AdminBlogCategory',

							 'placeholder' => 'Choose Category',			

							 'label'=>false,

							 'attr' => array('class' => 'form-control'),							 

   							 'choice_label' => function ($category) {

     			  						 return $category->getBlogCategoryTitle();

   						 		},

							 

							])

				  ->add('blog_description', TextareaType::class, array('data'=>$blog_desc,'required'=>false,'label'=>false,'attr' => array('class' => 'form-control ckeditor')))

	   			  ->add('blog_photo', FileType::class, [

					'mapped' => false,

					'required'=>false,

					'label'=>false,

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

	        $entityManager   = $this->getDoctrine()->getManager();

            $blog            = $entityManager->getRepository(AdminBlog::class)->find($slug);		  

			$blog_category   = $form['blog_category']->getData();

			$blog_categoryid = $blog_category->getCategoryId();

			$blog_title      = $form['blog_title']->getData();

			$blog_desc       = $form['blog_description']->getData();

			$blog_photo      = $form['blog_photo']->getData();	

			$time            = new \DateTime(date("Y-m-d H:i:s"));	

			$blog_url_title  = preg_replace('/\s+/', '_', $blog_title);		

			

			

			if($blog_photo){

	         $newFilename = uniqid().'.'.$blog_photo->guessExtension();				

			try {

                 $blog_photo->move(

                        $this->getParameter('blog_directory'),

                        $newFilename

                  );

                } catch (FileException $e) {

                    // ... handle exception if something happens during file upload

                }			

				$blog->setBlogTitle($blog_title);

				$blog->setBlogDescription($blog_desc);

				$blog->setBlogCategory($blog_categoryid);

				$blog->setBlogPhoto($newFilename);						

				$entityManager->flush();	

			}

			else{

				$blog->setBlogTitle($blog_title);

				$blog->setBlogDescription($blog_desc);

				$blog->setBlogCategory($blog_categoryid);					

				$entityManager->flush();	

			}		 	

			$this->addFlash('success', 'Blog has been updated successfully');

			return $this->redirectToRoute('view_blog');

	   }

	   

	   

	return $this->render('admin/blog/edit.html.twig',[

            'form' => $form->createView(),

			'id'=>	$slug	

        ]);

    }







 /**

     * @Route("/blog/delete/{slug}", name="admin_delete_blog")

     */

    public function delblogAction($slug)

    {

  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
	    $entityManager = $this->getDoctrine()->getManager();

		$repository    = $this->getDoctrine()->getRepository(AdminBlog::class);

		$category      = $repository->find($slug);

		$entityManager->remove($category);

		$entityManager->flush();			

		$this->addFlash('success', 'Blog has been deleted successfully');

		return $this->redirectToRoute('view_blog');			

    }			













 /**

     * @Route("/blog/category", name="admin_view_blog_category")

     */	

  public function BlogCategoryAction()

    {
  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
	 if(isset($_GET['page']))		 

		$page = $_GET['page'];	

	 else

	    $page ='1'; 		

		

	$blogcategory = new AdminBlogCategory();  

	

	$count_blog   = $this->getDoctrine()

           			 ->getRepository(AdminBlogCategory::class)

           			 ->CountBlogCategory();			

	$limit    = 10;

    $maxPages = ceil($count_blog / $limit);

    $thisPage = $page;

				  	

	$all_blog_category = $this->getDoctrine()

           			->getRepository(AdminBlogCategory::class)

         			->AllBlogCategory($thisPage,$limit);

     

	  $pagination = $this->render('pagination.html.twig',[

            'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'view_blog',

        ]);

			

	return $this->render('admin/category/view.html.twig',[

            'all_category' => $all_blog_category,'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'admin_view_blog_category'

        ]);



    }	





	/**

     * Matches /blog/*

/**

     * @Route("/blog/category/add", name="add_blog_category")

     */	

	

  public function addBlogCategoryAction(Request $request)

    {

  	    $session = new Session();

	    if(!$session->get('user_id'))

		  return $this->redirectToRoute('admin_login');

	   

	   $blog   = new AdminBlogCategory();   

	   $form   = $this->createFormBuilder($blog)

       	 	      ->add('blog_category_title', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control')))

				  ->add('blog_category_description', TextareaType::class, array('required'=>false,'label'=>false,'attr' => array('class' => 'form-control')))	   			  

				 ->add('Save', SubmitType::class, array('label'=> 'Save', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))

				 ->getForm();        

	    # Handle form response

        $form->handleRequest($request);	  	  

	

	   if($form->isSubmitted() ){	

	   

	    	  	$blog_category   = $form['blog_category_title']->getData();

				$blog_desc       = $form['blog_category_description']->getData();

			   

		        $time  = new \DateTime(date("Y-m-d H:i:s"));

			    $blog->setBlogCategoryTitle($blog_category);

				$blog->setBlogCategoryDescription($blog_desc);

				$blog->setIpAddress($request->getClientIp());

				$blog->setEntryTime($time);		

			

			# finally add data in database

			$sn = $this->getDoctrine()->getManager();      

			$sn -> persist($blog);

			$sn -> flush();

			$this->addFlash('success', 'Category has been added successfully');

			return $this->redirectToRoute('admin_view_blog_category');

	   }

	       return $this->render('admin/category/add.html.twig',array('form' => $form->createView() ));

    }

	

 	/**

     * Matches /blog/*

	 

	 /**

     * @Route("/blog/category/edit/{slug}", name="edit_blog_category")

     */

    public function editBlogCategoryAction(Request $request,$slug)

      {
  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
	    $blogcatg_info = $this->getDoctrine()->getRepository('AppBundle:AdminBlogCategory')->findBy(array("category_id"=>$slug));

	    if(!$blogcatg_info)

	    return  $this->redirectToRoute('admin_view_blog_category');	

	   

        if($blogcatg_info){

	       $blog_category = $blogcatg_info[0]->getBlogCategoryTitle();

		   $blog_desc     = $blogcatg_info[0]->getBlogCategoryDescription();

	     }		

		 

	     $blogcategory   = new AdminBlogCategory();  

	     $form   = $this->createFormBuilder($blogcategory)

	   	 	      ->add('blog_category_title', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$blog_category)))	

				  ->add('blog_category_description', TextareaType::class, array('data'=>$blog_desc,'required'=>false,'label'=>false,'attr' => array('class' => 'form-control')))

	   			  

				 ->add('Save', SubmitType::class, array('label'=> 'Save', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))

				 ->getForm();        

	   # Handle form response

       $form->handleRequest($request);	  		  

	

	   if($form->isSubmitted() ){	

	        $entityManager   = $this->getDoctrine()->getManager();

            $blogcategory    = $entityManager->getRepository(AdminBlogCategory::class)->find($slug);		  

			$blog_category   = $form['blog_category_title']->getData();

			$blog_desc       = $form['blog_category_description']->getData();	

			

			$blogcategory->setBlogCategoryTitle($blog_category);

			$blogcategory->setBlogCategoryDescription($blog_desc);

			$entityManager->flush();	

			$this->addFlash('success', 'Category has been updated successfully');

			return $this->redirectToRoute('admin_view_blog_category');

	    }   

	   

	return $this->render('admin/category/edit.html.twig',[

            'form' => $form->createView(),

			'id'=>	$slug	

        ]);

    }







 /**

     * @Route("/blog/category/delete/{slug}", name="delete_blog_category")

     */

    public function delblogCategoryAction($slug)

    {
  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
	    $entityManager = $this->getDoctrine()->getManager();

		$repository    = $this->getDoctrine()->getRepository(AdminBlogCategory::class);

		$category      = $repository->find($slug);

		$entityManager->remove($category);

		$entityManager->flush();			

		$this->addFlash('success', 'Category has been deleted successfully');

		return $this->redirectToRoute('admin_view_blog_category');			

    }			

	

	

	

}

