<?php
// src/AppBundle/Controller/RegisterController.php
namespace AppBundle\Controller;
use AppBundle\Entity\CommonRegister; 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class RegisterController extends Controller
{ 
    /** 
    
     * @Route("/register/activation/{token}" ,name="confirm_register")
    */
    public function indexAction($token)
    {  
        if($token){
           $entityManager = $this->getDoctrine()->getManager();
           $checkactivation   = $entityManager->getRepository(CommonRegister::class)->CheckActivation($token);	
           if($checkactivation){
                $user_id = $checkactivation[0]['account_id'];
                // update verified status
                $usersverfied = $entityManager->getRepository(CommonRegister::class)->find($user_id);
                $usersverfied->setActivationToken(NULL);
                $usersverfied->setCustomerAccountStatus('1');
                $entityManager->flush();
                $this->addFlash('success', 'Your account has been verified successfully, please login now');
    		    return $this->redirectToRoute('view_login');
          }
          else{
              $this->addFlash('error', 'there is a problem to verified your account or you have already verified');
    		  return $this->redirectToRoute('view_login');
            }
        }
        else{
            $this->addFlash('error', 'there is a problem to verified your account or you have already verified');
		    return $this->redirectToRoute('view_login');
           }
       
        
    }
    
	
}