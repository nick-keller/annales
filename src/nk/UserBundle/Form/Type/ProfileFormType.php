<?php

namespace nk\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class ProfileFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('resource', 'entity', array(
            'class' => 'nk\ExamBundle\Entity\Resource',
            'label' => 'Promo',
        ));
    }

    public function getName()
    {
        return 'nk_user_profile';
    }
}
