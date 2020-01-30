<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class CommonRegisterRepository extends EntityRepository
{

  public function CheckActivation($activationcode)
    {
        return $this->createQueryBuilder('users')->select('users.account_id')
	 									->where('users.activation_token = :activation_token')
										->setParameters(['activation_token' => $activationcode])
										->orderBy('users.entry_time', 'DESC')
										->getQuery()->getResult();
    }

  public function UserInfo($user_id)
    {
     $user_info = $this->createQueryBuilder('users')
										->Where('users.account_id = :account_id')
										->setParameters(['account_id' => $user_id])
										->orderBy('users.entry_time', 'DESC')
										->getQuery()->getResult();
    if($user_info && isset($user_info[0]))
      return $user_info[0];
    }

  public function valid_user($account_username)
    {		
        return $this->createQueryBuilder('a')
		    ->Where('a.account_username = :username OR a.email_id = :email_id')
			->andWhere('a.account_type = :account_type')
            ->setParameters(['username' => $account_username,'email_id'=>$account_username])
			->setParameter('account_type', '2')
			->getQuery()->getResult();
    } 
	
  public function valid_login($account_username,$password)
    {
		$password = md5($password);
        return $this->createQueryBuilder('a')
		    ->andWhere('a.account_username = :username')				
			->andWhere('a.account_key = :password')
			->andWhere('a.account_type = :account_type')
			->setParameter('username', $account_username)
			->setParameter('password', $password)
			->setParameter('account_type', '2')
			->getQuery()->getResult();
    } 
}
