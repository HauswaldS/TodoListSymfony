<?php

namespace TL\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TL\UserBundle\Entity\User;

class LoadUser extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        //Get the FOSUserManager that was injected previously
        $userManager = $this->container->get('fos_user.user_manager');

        $userInfo = array(
                  'username'    => 'TodoAdmin',
                  'email'       => 'admin@gmail.com',
                  'password'    => 'admin',
                  'role'        => 'ROLE_ADMIN'
            );

                $user = $userManager->createUser();
                $user->setUsername($userInfo['username']);
                $user->setEmail($userInfo['email']);
                $user->setPlainPassword($userInfo['password']);
                $user->setRoles(array($userInfo['role']));
                $user->setEnabled(true);
                $userManager->updateUser($user, true);

                $this->addReference('user-1', $user);
    }

    public function getOrder(){
        return 1;
    }
}