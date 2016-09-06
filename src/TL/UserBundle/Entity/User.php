<?php

namespace TL\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="TL\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="tl_user")
 */

 class User extends BaseUser
 {
     /**
      * @var int
      *
      * @ORM\Id
      * @ORM\Column(type="integer")
      * @ORM\GeneratedValue(strategy="AUTO")
      */
      protected $id;

      /**
       *@ORM\OneToOne(targetEntity="TL\UserBundle\Entity\ProfileImage", cascade={"persist","remove"})
       */
       protected $profileImage;

       /**
        * @ORM\OneToMany(targetEntity="TL\DashboardBundle\Entity\Folder", mappedBy="user", cascade={"persist","remove"})
        */
        protected $folders;

    /**
     * Set profileImage
     *
     * @param \TL\UserBundle\Entity\ProfileImage $profileImage
     *
     * @return User
     */
    public function setProfileImage(\TL\UserBundle\Entity\ProfileImage $profileImage = null)
    {
        $this->profileImage = $profileImage;

        return $this;
    }

    /**
     * Get profileImage
     *
     * @return \TL\UserBundle\Entity\ProfileImage
     */
    public function getProfileImage()
    {
        return $this->profileImage;
    }

    /**
     * Add folder
     *
     * @param \TL\DashboardBundle\Entity\Folder $folder
     *
     * @return User
     */
    public function addFolder(\TL\DashboardBundle\Entity\Folder $folder)
    {
        $this->folders[] = $folder;

        $folder->setUser($this);

        return $this;
    }

    /**
     * Remove folder
     *
     * @param \TL\DashboardBundle\Entity\Folder $folder
     */
    public function removeFolder(\TL\DashboardBundle\Entity\Folder $folder)
    {
        $this->folders->removeElement($folder);
    }

    /**
     * Get folders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFolders()
    {
        return $this->folders;
    }
}
