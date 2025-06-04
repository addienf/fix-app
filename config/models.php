<?php

use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Models\User;

return [
    'user' => [
        'label' => 'User',
        'model' => User::class,
    ],
    'specProduct' => [
        'label' => 'Spesifikasi Product',
        'model' => SpesifikasiProduct::class,
    ],
    'spkMkt' => [
        'label' => 'SPK Marketing',
        'model' => SPKMarketing::class,
    ],
];
