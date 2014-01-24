<?php

namespace nk\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class ProfileFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('resource', 'entity', array(
                'class' => 'nk\ExamBundle\Entity\Resource',
                'label' => 'Promo',
            ))
            ->add('weekBeforeExam', 'choice', array(
                'label' => 'Rappels examens',
                'choices' => array(
                    'Jamais',
                    "Le vendredi d'avant",
                    '2 semaines avant',
                    '3 semaines avant',
                    '1 mois avant'
                )
            ))
        ;
    }

    public function getName()
    {
        return 'nk_user_profile';
    }
}
