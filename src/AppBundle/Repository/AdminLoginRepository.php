<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdminLoginRepository extends EntityRepository
{

		

 public function valid_login($account_username,$password)
    {
		$password = md5($password);
        return $this->createQueryBuilder('a')
		    ->andWhere('a.account_username = :username')				
			->andWhere('a.account_key = :password')
			->andWhere('a.account_type = :account_type')
			->setParameter('username', $account_username)
			->setParameter('password', $password)		
			->setParameter('account_type', '1')
			->getQuery()->getResult();
    } 

}
