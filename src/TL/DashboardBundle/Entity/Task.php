<?php

namespace TL\DashboardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
/**
 * Task
 *
 * @ORM\Table(name="tl_task")
 * @ORM\Entity(repositoryClass="TL\DashboardBundle\Repository\TaskRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Task
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
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status = false;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\Length(min=5, minMessage="The minimum lenght for the task content is 5 caracters")
     */
    private $content;

    /**
     * @var bool
     *
     * @ORM\Column(name="priority", type="boolean")
     */
    private $priority;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Assert\DateTime()
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finished_at", type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    private $finishedAt = null;

    /**
     * @var TL\DashboardBundle\Entity\Folder
     *
     * @ORM\ManyToOne(targetEntity="TL\DashboardBundle\Entity\Folder", inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     */
     private $folder;

     private $daysLeftToFinish;

    public function __construct()
    {
        $this->createdAt = new \Datetime('now');
        $this->daysLeftToFinish = null;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Task
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Task
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set priority
     *
     * @param boolean $priority
     *
     * @return Task
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return boolean
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Task
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set folder
     *
     * @param \TL\DashboardBundle\Entity\Folder $folder
     *
     * @return Task
     */
    public function setFolder(\TL\DashboardBundle\Entity\Folder $folder)
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Get folder
     *
     * @return \TL\DashboardBundle\Entity\Folder
     */
    public function getFolder()
    {
        return $this->folder;
    }


    /**
     * Set finishedAt
     *
     * @param \DateTime $finishedAt
     *
     * @return Task
     */
    public function setFinishedAt($finishedAt)
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }

    /**
     * Get finishedAt
     *
     * @return \DateTime
     */
    public function getFinishedAt()
    {
        return $this->finishedAt;
    }

    /**
     * When the Task is created
     *
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        //If the task Priority is true
        if($this->getPriority() === true)
        {
            $this->folder->increaseNbTaskPrioritary();
        }
        $this->folder->increaseNbTaskPending();
    }

     /**
      * Before removing a Task
      *
      * @ORM\PreRemove
      */
     public function preRemove()
     {
         //If the task Priority is true when removed
         if($this->getPriority() === true){

             //Decrease the number of prioritary Task of the Folder
            $this->getFolder()->decreaseNbTaskPrioritary();

             //Decrease the number of pending Task of the Folder
            $this->folder->decreaseNbTaskPending();
            
            //If the Task is set as Done
         } else if ($this->getStatus() === true){
             //Decrease the number of done Task of the Folder
             $this->getFolder()->decreaseNbTaskDone();
         }
     }


     public function setDaysLeftToFinish($daysLeft)
     {
         $this->DaysLeftToFinish = $daysLeft;
     }

     public function getDaysLeftToFinish()
     {
         return $this->DaysLeftToFinish;
     }

     /**
      * @Assert\Callback
      */
     public function isFinishedAtValid(ExecutionContextInterface $context)
     {
         if( new\DateTime('now') > $this->getFinishedAt()){
             $context
                    ->buildViolation('Your date isn\'t valid, you cannot set a task for yesterday ! Sorry.')
                    ->atPath('finishedAt')
                    ->addViolation();
         }
     }
}
