<?php
return [
    'id'         => 'order',
    'name'       => trans('Invoices'),
    'route'      => route('get.order.list'),
    'sort'       => 6,
    'active'     => true,
    'icon'       => 'fas fa-file-invoice',
    'middleware' => ['order'],
    'group'      => []
];
