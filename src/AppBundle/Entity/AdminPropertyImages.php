<?php
// src/AppBundle/Entity/AdminPropertyImages.php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM; 
/**
* AdminPropertyImages
*
* @ORM\Table(name="property_images")
* @ORM\Entity(repositoryClass="AppBundle\Repository\AdminPropertyImagesRepository")
*/

class AdminPropertyImages
{
/**
    * @var int
    *
    * @ORM\Column(name="image_id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
   private $image_id;
 
	 
   /**
    * @var int
    *
    * @ORM\Column(name="property_id", type="integer")
    */
   private $property_id;
   

   /**
    * @var string
    *
    * @ORM\Column(name="image_name", type="string")
    */
   private $image_name;


   /**
    * @var string
    *
    * @ORM\Column(name="ip_address", type="string", length=40)
    */	
   private $ip_address;
 
   /**
    * @var string
    *
    * @ORM\Column(name="entry_time", type="datetime")
    */	
    private $entry_time;
		
 
    public function getImageId()
    {
        return $this->image_id;
    }	
    public function getPropertyId()
    {
        return $this->property_id;
    }	  

    public function setPropertyId($property_id)
    {
        return $this->property_id= $property_id;
    }
    
   public function getImageName()
    {
        return $this->image_name;
    }

  public function setImageName($image_name)
    {
        return $this->image_name=$image_name;
    }

  public function setIpAddress($ip_address)
    {
        return $this->ip_address=$ip_address;
    }

   public function getEntryTime()
    {
        return $this->entry_time;
    }	
	
   public function setEntryTime($entry_time)
    {
        return $this->entry_time=$entry_time;
    }	
	
    
}