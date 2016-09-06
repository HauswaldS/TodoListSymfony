<?php 

namespace TL\DashboardBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TL\DashboardBundle\Entity\Folder;

class LoadFolder extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $folderInfo = array(
                'title'    => 'Work Todo',
                'description'   => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Natus labore quod aut nulla. Tempora minus molestiae, dolore.'
        );

            $folder = new Folder();
            $folder->setTitle($folderInfo['title']);
            $folder->setDescription($folderInfo['description']);
            $folder->setUser($this->getReference('user-1'));
            $manager->persist($folder);
            $manager->flush($folder);
            $this->addReference('folder-1', $folder);
    
    }

    public function getOrder()
    {
        return 2;
    }
}