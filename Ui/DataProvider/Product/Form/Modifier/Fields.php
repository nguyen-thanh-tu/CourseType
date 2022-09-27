<?php

namespace TUTJunior\CourseType\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Ui\Component\Form\Fieldset;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Element\DataType\Text;

class Fields extends AbstractModifier
{
    private $locator;

    public function __construct(
        LocatorInterface $locator
    ) {
        $this->locator = $locator;
    }
    public function modifyData(array $data)
    {
        return $data;
    }

    public function modifyMeta(array $meta)
    {
        if ($this->locator->getProduct()->getTypeId() === 'course_type') {
            $meta = array_replace_recursive(
                $meta,
                [
                    'course' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'label' => __('Course Fields'),
                                    'collapsible' => true,
                                    'opened' => true,
                                    'componentType' => Fieldset::NAME,
                                    'dataScope' => 'data.course',
                                ],
                            ],
                        ],
                        'children' => $this->getFields()
                    ],
                ]
            );
        }
        return $meta;
    }

    protected function getFields()
    {
        return [
            'document' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label'         => __('Document'),
//                            'component'     => 'Magenest_GoogleShopping/js/dynamic/category/search',
                            'componentType' => Field::NAME,
                            'formElement'   => Input::NAME,
                            'dataScope'     => 'document',
                            'dataType'      => Text::NAME,
                            'sortOrder'     => 20
                        ],
                    ],
                ]
            ],
            'course_timeline' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label'         => __('Course Timeline'),
                            'componentType' => 'dynamicRows',
                            'renderDefaultRecord' => false,
                            'recordTemplate' => 'record',
                            'dataScope' => '',
                            'dndConfig' => [
                                'enabled' => false,
                            ],
                            'disabled' => false,
                            'sortOrder' => 10
                        ],
                    ],
                ],
                'children' => [
                    'record' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'componentType' => \Magento\Ui\Component\Container::NAME,
                                    'isTemplate' => true,
                                    'is_collection' => true,
                                    'component' => 'Magento_Ui/js/dynamic-rows/record',
                                    'dataScope' => '',
                                    'require' => '0',
                                ],
                            ],
                        ],
                        'children' => [
                            'from' => [
                                'arguments' => [
                                    'data' => [
                                        'config' => [
                                            'formElement' => 'date',
                                            'componentType' => Field::NAME,
                                            'dataType' => 'string',
                                            'label' => __('From'),
                                            'dataScope' => 'from',
                                            'require' => '1',
                                            'options' =>[
                                                'date_format' => 'yyyy-MM-dd',
                                                'time_format' => 'hh:mm:ss',
                                                'showsTime' => true,
                                                'timeOnly'=> true
                                            ],
                                            'storeTimeZone' => 'string'
                                        ],
                                    ],
                                ],
                            ],

                            'to' => [
                                'arguments' => [
                                    'data' => [
                                        'config' => [
                                            'formElement' => 'date',
                                            'componentType' => Field::NAME,
                                            'dataType' => 'string',
                                            'label' => __('To'),
                                            'dataScope' => 'to',
                                            'require' => '1',
                                            'options' =>[
                                                'date_format' => 'yyyy-MM-dd',
                                                'time_format' => 'hh:mm:ss',
                                                'showsTime' => true,
                                                'timeOnly'=> true
                                            ],
                                            'storeTimeZone' => 'string'
                                        ],
                                    ],
                                ],
                            ],

                            'quantity' => [
                                'arguments' => [
                                    'data' => [
                                        'config' => [
                                            'formElement' => Input::NAME,
                                            'componentType' => Field::NAME,
                                            'dataType' => Text::NAME,
                                            'label' => __('Quantity'),
                                            'dataScope' => 'quantity',
                                        ],
                                    ],
                                ],
                            ],

                            'actionDelete' => [
                                'arguments' => [
                                    'data' => [
                                        'config' => [
                                            'componentType' => 'actionDelete',
                                            'dataType' => Text::NAME,
                                        ],
                                    ],
                                ],
                            ]
                        ],
                    ]
                ]
            ]
        ];
    }
}

