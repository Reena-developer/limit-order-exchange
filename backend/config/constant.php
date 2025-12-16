<?php

return [
    
    'commission_rate' => env('COMMISSION_RATE', 0.015),
    
    'order_status' => [
        'open'      => 1,
        'filled'    => 2,
        'cancelled' => 3,
    ],
    
    'symbols' => [
        'BTC',
        'ETH',
    ],
    
    'decimal' => [
        'price'  => 2,
        'amount' => 8,
    ],
    
    'pagination' => [
        'per_page' => 10,
        'max_per_page' => 100,
    ],

];
