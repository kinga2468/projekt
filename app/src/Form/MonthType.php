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
                'required' => true,
                'attr' => [
                    'max_length' => 128,
                ],
            ]
        );
        $builder->add(
            'date_from',
            DateType::class,
            [
                'label' => 'label.date_from',
                'required' => true,
                'format' => 'y-M-d',
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day'
                ],

            ]
        );
        $builder->add(
            'date_to',
            DateType::class,
            [
                'label' => 'label.date_to',
                'required' => true,
                'format' => 'y-M-d',

            ]
        );
        $builder->add(
            'limit',
            IntegerType::class,
            [
                'label' => 'label.limit',
                'required' => true,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'month_type';
    }
}