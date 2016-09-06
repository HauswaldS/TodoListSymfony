<?php

namespace TL\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table(name="tl_profile_image")
 * @Vich\Uploadable
 */

 class ProfileImage
 {
     /**
      * @ORM\ID
      * @ORM\Column(type="integer")
      * @ORM\GeneratedValue(strategy="AUTO")
      */
      private $id;

      /**
       * @Vich\UploadableField(mapping="profile_image", fileNameProperty="imageName")
       *
       * @var File
       */
       private $imageFile;

       /**
        * @ORM\Column(type="string", length=255)
        *
        * @var String
        */
        private $imageName;

        /**
         * @ORM\Column(type="datetime")
         *
         * @var \DateTime
         */
         private $updatedAt;

         /**
          * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image 
          *
          * @return ProfileImage
          */
          public function setImageFile(File $image = null)
          {
              $this->imageFile = $image;
              //It is required that at least one field changes when using Doctrine
              //Otherwise the event listener won't be called and the file will be lost
              if($image){
                  $this->updatedAt = new \DateTime('now');
              }

              return $this;
          }

        /**
         * @param file|null 
         *
         * @return ProfileImage
         */
         public function getImageFile()
         {
             return $this->imageFile;
         }

         /**
          * @param string $imageName 
          *
          * @return ProfileImage
          */
          public function setImageName($imageName)
          {
              $this->imageName = $imageName;
              
              return $this;
          }

        /**
         * @return string|null
         */          
         public function getImageName()
         {
             return $this->imageName;
         }
 }