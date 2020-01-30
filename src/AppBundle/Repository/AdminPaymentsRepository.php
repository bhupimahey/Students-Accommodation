<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdminPaymentsRepository extends EntityRepository
{

		
 public function CountPayments()
    {
     return $this->createQueryBuilder('users_payment')->select('count(users_payment.payment_id)')
	 									->orderBy('users_payment.entry_time', 'DESC')
										->getQuery()->getSingleScalarResult();
    }
 	
  public function AllPayments($currentPage = 1,$limit)
    {
       $query = $this->createQueryBuilder('users_payment')
										->orderBy('users_payment.entry_time', 'DESC')
										->getQuery();			
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
