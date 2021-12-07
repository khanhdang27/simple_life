<?php
return [
    'id'         => 'service',
    'name'       => trans('Service Management'),
    'sort'       => 3,
    'active'     => true,
    'icon'       => 'fas fa-cocktail',
    'middleware' => [],
    'group'      => [
        [
            'id'         => 'service-type',
            'name'       => trans('Service Types'),
            'route'      => route("get.service_type.list"),
            'middleware' => ['service-type']
        ],
        [
            'id'         => 'service',
            'name'       => trans('Services'),
            'route'      => route('get.service.list'),
            'middleware' => 'service'
        ],
        [
            'id'         => 'service-voucher',
            'name'       => trans('Service Voucher'),
            'route'      => route('get.service_voucher.list'),
            'middleware' => 'service-voucher'
        ]
    ]
];