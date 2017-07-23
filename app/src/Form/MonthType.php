<?php
/**
 * Month type.
 */
namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\CallbackTransformer;

/**
 * Class MonthType.
 *
 * @package Form
 */
class MonthType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name',
            TextType::class,
            [
                'label' => 'label.name',
                'required' => true,          //Jeśli true to zostanie wyświetlony atrybut wymagany w formacie HTML5.
                'attr' => [
                    'max_length' => 128,
                ],
            ]
        );/*
        $builder->add(
            'date_from',
            DateType::class,
            [
                'label' => 'label.date_from',
                'format' => 'yyyy-MM-dd',
            ]
        );
        $builder->add(
            'date_to',
            DateType::class,
            [
                'label' => 'label.date_to',
                'format' => 'yyyy-MM-dd',

            ]
        );*/
        $builder->add(
            'upper_limit',
            IntegerType::class,
            [
                'label' => 'label.upper_limit',
                'required' => true,
                'attr' => [
                    'max_length' => 128,
                ],
            ]
        );/*
        $builder->get('date_from')
            ->addModelTransformer(new CallbackTransformer(
                function ($dateAsArray) {
                    // transform the array to a string
                    return implode(', ', (array)$dateAsArray);
                },
                function ($dateAsString) {
                    // transform the string back to an array
                    return explode(', ',  $dateAsString);
                }
            ))
        ;
        $builder->get('date_to')
            ->addModelTransformer(new CallbackTransformer(
                function ($dateAsArray) {
                    // transform the array to a string
                    return implode(', ', (array)$dateAsArray);
                },
                function ($dateAsString) {
                    // transform the string back to an array
                    return explode(', ', $dateAsString);
                }
            ))
        ;*/
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()                 //return a unique block prefix (e.g. app_password) to avoid collisions.
    {
        return 'month_type';
    }
}