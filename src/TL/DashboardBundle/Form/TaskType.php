<?php

namespace TL\DashboardBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TaskType extends AbstractType
{
    /**
     * @param FormBuilderInterface
     * @param array $option
     */
     public function buildForm(FormBuilderInterface $builder, array $option)
     {
         $builder
            ->add('content', TextareaType::class)
            ->add('priority', CheckboxType::class, array('required' => false))
            ->add('save', SubmitType::class);
     }

     /**
      * @param OptionsResolver $resolver
      */
      public function configureOptions(OptionsResolver $resolver)
      {
          $resolver->setDefaults(array(
              'data_class' => "TL\DashboardBundle\Entity\Task"
          ));
      }
}