<?php
namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdminPropertyRepository extends EntityRepository
{


 public function CountProperty()
    {
     return $this->createQueryBuilder('property')
	        ->select('count(property.property_id)')
            ->orderBy('property.entry_time', 'DESC')
            ->getQuery()->getSingleScalarResult();
    }

  public function PropertyInfo($property_id)
    {
     return $this->createQueryBuilder('property')
										->Where('property.property_id = :property_id')
										->setParameters(['property_id' => $property_id])
										->orderBy('property.entry_time', 'DESC')
										->getQuery()->getResult()[0];
    }

				
 public function AllProperty($currentPage = 1 , $limit)
    {
       $query = $this->createQueryBuilder('property')
            ->orderBy('property.entry_time', 'DESC')
            ->getQuery();
			
	 $paginator = $this->paginate($query, $currentPage, $limit);
	 
	 
      return $paginator;
    }


 public function AllPropertyDropdown()
    {
       return $this->createQueryBuilder('property')
            ->orderBy('property.entry_time', 'DESC')
            ->getQuery()->getResult();
    }

	
 public function paginate($dql, $page = 1, $limit)
  {
    $paginator = new Paginator($dql);

    $paginator->getQuery()
        ->setFirstResult($limit * ($page - 1)) // Offset
        ->setMaxResults($limit); // Limit

    return $paginator;
  }
}
