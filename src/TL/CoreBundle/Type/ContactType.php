<?php

namespace TL\CoreBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolvers;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use TL\CoreBundle\Entity\ContactMail;

class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface
     * @param array $option
     */
     public function buildForm(FormBuilderInterface $builder, array $option)
     {
         $builder
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('email', EmailType::class)
            ->add('send', SubmitType::class);
     }

     /**
      * @param OptionsResolver $resolver
      */
     public function configureOptions(OptionsResolver $resolver)
     {
         $resolver->setDefaults(array(
             'data_class'   => 'TL\CoreBundle\Entity\ContactMail'
         ));
     }

}