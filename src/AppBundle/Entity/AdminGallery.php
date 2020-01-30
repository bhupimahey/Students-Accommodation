<?php
// src/AppBundle/Entity/AdminGallery.php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM; 
/**
* AdminGallery
*
* @ORM\Table(name="gallery")
* @ORM\Entity(repositoryClass="AppBundle\Repository\AdminGalleryRepository")
*/
class AdminGallery
{
/**
    * @var int
    *
    * @ORM\Column(name="gallery_id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
   private $id;
  
   /**
    * @var string
    *
    * @ORM\Column(name="gallery_title", type="string", length=150)
    */
   private $gallery_title;
   
      
   /**
    * @var string
    *
    * @ORM\Column(name="photo", type="string", length=150)
    */	
   private $photo;
   

   /**
    * @var string
    *
    * @ORM\Column(name="entry_time", type="datetime")
    */	
    private $entry_time;
	
	
 
    public function getId()
    {
        return $this->id;
    }	
	  
   public function getPhoto()
    {
        return $this->photo;
    }

  public function setPhoto($photo)
    {
        return $this->photo=$photo;
    }

   public function getGalleryTitle()
    {
        return $this->gallery_title;
    }
	
   public function setGalleryTitle($gallery_title)
    {
        return $this->gallery_title=$gallery_title;
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