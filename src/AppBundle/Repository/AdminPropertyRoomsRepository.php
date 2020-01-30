<?php
namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdminPropertyRoomsRepository extends EntityRepository
{



 public function AllPropertyRooms($property_id)
    {
       return $this->createQueryBuilder('property_rooms')
            ->Where('property_rooms.property_id = :property_id')
			->setParameters(['property_id' => $property_id])
            ->getQuery()->getResult();
    }


}
