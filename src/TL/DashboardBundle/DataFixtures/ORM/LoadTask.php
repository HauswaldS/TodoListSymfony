<?php

namespace TL\DashboardBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TL\DashboardBundle\Entity\Task;

class LoadTask extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $tasksList = array(
            array(
                'content'   => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Natus labore quod aut nulla. Tempora minus molestiae, dolore, pariatur quis officiis, perferendis voluptatibus consequatur id, et a nemo commodi sed quo.',
            ),
            array(
                'content'   => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Natus labore quod aut nulla. Tempora minus molestiae, dolore, pariatur quis officiis, perferendis voluptatibus consequatur id, et a nemo commodi sed quo.',
            )        
        );

        foreach($tasksList as $taskInfo){
            $task = new Task();
            $task->setStatus(false);
            $task->setContent($taskInfo['content']);
            $task->setPriority(false);
            $task->setFolder($this->getReference('folder-1'));
            $manager->persist($task);
            $manager->flush();
        }      
    }

    public function getOrder()
    {
        return 3;
    }
}