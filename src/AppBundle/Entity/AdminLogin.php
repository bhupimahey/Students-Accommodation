<?php
// src/AppBundle/Entity/AdminLogin.php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM; 
/**
* Contact
*
* @ORM\Table(name="users")
* @ORM\Entity(repositoryClass="AppBundle\Repository\AdminLoginRepository")
*/
class AdminLogin
{
/**
    * @var int
    *
    * @ORM\Column(name="account_id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
   private $id;
   
   /**
    * @var string
    *
    * @ORM\Column(name="account_username", type="string", length=70)
    */
   private $account_username;

 /**
    * @var string
    *
    * @ORM\Column(name="account_key", type="string", length=100)
    */
   private $account_key;
   
   /**
    * @var string
    *
    * @ORM\Column(name="account_type", type="string", length=1)
    */
   private $account_type; 

   /**
    * @var string
    *
    * @ORM\Column(name="full_name", type="string", length=70)
    */	
   private $full_name;



    public function getAccountUsername()
    {
        return $this->account_username;
    }
	
	   public function setAccountUsername($username)
    {
        return $this->account_username=$username;
    }

 
    public function getAccountKey()
    {
        return $this->account_key;
    }
	
   public function setAccountKey($password)
    {
        return $this->account_key=$password;
    }
			
    public function getId()
    {
        return $this->account_id;
    }
}