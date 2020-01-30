<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdminUsersRequestsHistoryRepository extends EntityRepository
{
		

 public function LatestRequestHistory($request_id)
    {
       $query = $this->createQueryBuilder('users_requests_history')
             ->where('users_requests_history.srequest_id = :srequest_id')
             ->setParameters(['srequest_id' =>$request_id])
	         ->orderBy('users_requests_history.history_id', 'DESC')
			 ->setMaxResults(1)             
             ->getQuery()->getResult();
      return $query;
    }
	
}
