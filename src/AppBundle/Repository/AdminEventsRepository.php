<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdminEventsRepository extends EntityRepository
{

		
 public function CountEvents()
    {
     return $this->createQueryBuilder('events')->select('count(events.event_id)')->orderBy('events.entry_time', 'DESC')
											   ->getQuery()->getSingleScalarResult();
    }
	
  public function AllEvents($currentPage = 1,$limit)
    {
       $query = $this->createQueryBuilder('events')->orderBy('events.entry_time', 'DESC')->getQuery();			
	  $paginator = $this->paginate($query, $currentPage, $limit);
      return $paginator;
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
