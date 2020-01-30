<?php
// src/AppBundle/Entity/AdminServices.php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM; 
/**
* AdminServices
*
* @ORM\Table(name="services")
* @ORM\Entity(repositoryClass="AppBundle\Repository\AdminServicesRepository")
*/
class AdminServices
{
/**
    * @var int
    *
    * @ORM\Column(name="service_id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
   private $service_id;
  
   /**
    * @var string
    *
    * @ORM\Column(name="service_title", type="string", length=150)
    */
   private $service_title;
   
      
   /**
    * @var string
    *
    * @ORM\Column(name="service_description", type="string", length=150)
    */
   private $service_description;



   /**
    * @var string
    *
    * @ORM\Column(name="entry_time", type="datetime")
    */	
    private $entry_time;
	
	
 
    public function getId()
    {
        return $this->service_id;
    }	
	  
   public function getServiceTitle()
    {
        return $this->service_title;
    }
	
   public function setServiceTitle($service_title)
    {
        return $this->service_title=$service_title;
    }


   public function getServiceDescription()
    {
        return $this->service_description;
    }
	
   public function setServiceDescription($service_description)
    {
        return $this->service_description=$service_description;
    }


   public function getEntryTime()
    {
        return $this->entry_time;
    }	
	
   public function setEntryTime($entry_time)
    {
        return $this->entry_time=$entry_time;
    }		

   public function setIpAddress($ip_address)
    {
        return $this->ip_address=$ip_address;
    }	
 }