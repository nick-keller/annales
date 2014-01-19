<?php

namespace nk\DocumentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StandardizeType extends AbstractType
{
    private $metadata;

    function __construct($metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('from', 'choice', array(
                'label' => 'Source',
                'choices' => array_combine($this->metadata, $this->metadata),
            ))
            ->add('to', 'text', array(
                'label' => 'Destination',
                'attr' => array(
                    'data-autocomplete' => implode(',', $this->metadata),
                )
            ))
            ->add('field', 'hidden')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'nk\DocumentBundle\Entity\Standardize'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'nk_documentbundle_standardize';
    }
}
