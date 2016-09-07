<?php

namespace TL\DashboardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Folder
 *
 * @ORM\Table(name="tl_folder")
 * @ORM\Entity(repositoryClass="TL\DashboardBundle\Repository\FolderRepository")
 */
class Folder
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\Length(min="5", minMessage="The minimum number of caracters for a title is 5")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="TL\UserBundle\Entity\User", inversedBy="folders")
     * @ORM\JoinColumn(nullable=false)
     */
     private $user;

     /**
      * @ORM\OneToMany(targetEntity="TL\DashboardBundle\Entity\Task", mappedBy="folder", cascade={"persist", "remove"})
      */
     private $tasks;

     /**
      * @ORM\Column(name="nb_task_pending", type="integer")
      */
     private $nbTaskPending = 0;

     /**
      * @ORM\Column(name="nb_task_done", type="integer")
      */
     private $nbTaskDone = 0;

     /**
      * @ORM\Column(name="nb_task_prioritary", type="integer")
      */
     private $nbTaskPrioritary = 0;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Folder
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Folder
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * Set user
     *
     * @param \TL\UserBundle\Entity\User $user
     *
     * @return Folder
     */
    public function setUser(\TL\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \TL\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tasks = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add task
     *
     * @param \TL\DashboardBundle\Entity\Folder $task
     *
     * @return Folder
     */
    public function addTask(\TL\DashboardBundle\Entity\Task $task)
    {
        $this->tasks[] = $task;

        $task->setFolder($this);

        return $this;
    }

    /**
     * Remove task
     *
     * @param \TL\DashboardBundle\Entity\Folder $task
     */
    public function removeTask(\TL\DashboardBundle\Entity\Task $task)
    {
        $this->tasks->removeElement($task);
    }

    /**
     * Get tasks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Set nbTaskPending
     *
     * @param integer $nbTaskPending
     *
     * @return Folder
     */
    public function setNbTaskPending($nbTaskPending)
    {
        $this->nbTaskPending = $nbTaskPending;

        return $this;
    }

    /**
     * Get nbTaskPending
     *
     * @return integer
     */
    public function getNbTaskPending()
    {
        return $this->nbTaskPending;
    }

    /**
     * Set nbTaskDone
     *
     * @param integer $nbTaskDone
     *
     * @return Folder
     */
    public function setNbTaskDone($nbTaskDone)
    {
        $this->nbTaskDone = $nbTaskDone;

        return $this;
    }

    /**
     * Get nbTaskDone
     *
     * @return integer
     */
    public function getNbTaskDone()
    {
        return $this->nbTaskDone;
    }

    /**
     * Set nbTaskPrioritary
     *
     * @param integer $nbTaskPrioritary
     *
     * @return Folder
     */
    public function setNbTaskPrioritary($nbTaskPrioritary)
    {
        $this->nbTaskPrioritary = $nbTaskPrioritary;

        return $this;
    }

    /**
     * Get nbTaskPrioritary
     *
     * @return integer
     */
    public function getNbTaskPrioritary()
    {
        return $this->nbTaskPrioritary;
    }

    public function increaseNbTaskPending()
    {
        $this->nbTaskPending++;
    }

    public function decreaseNbTaskPending()
    {
        $this->nbTaskPending--;
    }

    public function increaseNbTaskDone()
    {
        $this->nbTaskDone++;
    }

    public function decreaseNbTaskDone()
    {
        $this->nbTaskDone--;
    }

    public function increaseNbTaskPrioritary()
    {
        $this->nbTaskPrioritary++;
    }

    public function decreaseNbTaskPrioritary()
    {
        $this->nbTaskPrioritary--;
    }
}
