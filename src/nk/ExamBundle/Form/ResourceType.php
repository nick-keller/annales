<?php

namespace nk\ExamBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ResourceType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'Promo',
                'attr' => array(
                    'placeholder' => 'E1, E3FI, MOTIS...'
                )
            ))
            ->add('code', 'text', array(
                'label' => 'Ressource',
                'attr' => array(
                    'placeholder' => '217, 2065...'
                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'nk\ExamBundle\Entity\Resource'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'nk_exambundle_resource';
    }
}
