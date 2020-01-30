<?php
namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdminPropertyImagesRepository extends EntityRepository
{



 public function AllPropertyImages($property_id)
    {
       return $this->createQueryBuilder('property_images')
            ->Where('property_images.property_id = :property_id')
			->setParameters(['property_id' => $property_id])
            ->getQuery()->getResult();
    }


}
