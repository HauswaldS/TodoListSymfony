<?php

namespace TL\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use TL\UserBundle\Form\ProfileImageType;

class ProfileEditType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('profileImage', ProfileImageType::class, array('required'=>false));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_profile_edit';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}