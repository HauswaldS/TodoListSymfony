<?php

namespace TL\DashboardBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FolderType extends AbstractType
{
    /**
     * @param FormBuilderInterface
     * @param array $option
     */
     public function buildForm(FormBuilderInterface $builder, array $options)
     {
         $builder
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->add('save', SubmitType::class);
     }


    /**
     * @param OptionsResolver $resolver
     */
     public function configureOptions(OptionsResolver $resolver)
     {
         $resolver->setDefaults(array(
             'data_class' => 'TL\DashboardBundle\Entity\Folder'
         ));
     }
}