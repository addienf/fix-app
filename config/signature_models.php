<?php

use App\Models\Engineering\Berita\Pivot\BeritaAcaraPIC;
use App\Models\Engineering\Maintenance\ChamberG2\Pivot\ChamberG2PIC;
use App\Models\Engineering\Maintenance\ChamberR2\Pivot\ChamberR2PIC;
use App\Models\Engineering\Maintenance\ChamberWalkinG2\Pivot\ChamberWalkinG2PIC;
use App\Models\Engineering\Maintenance\ColdRoom\Pivot\ColdRoomPIC;
use App\Models\Engineering\Maintenance\Refrigerator\Pivot\RefrigeratorPIC;
use App\Models\Engineering\Maintenance\RissingPipette\Pivot\RissingPipettePIC;
use App\Models\Engineering\Maintenance\WalkinChamber\Pivot\WalkinChamberPIC;

return [
    'berita-acara' => BeritaAcaraPIC::class,

    'walkin-test-chamber' => WalkinChamberPIC::class,

    'stability-chamber' => ChamberR2PIC::class,

    'refrigerator' => RefrigeratorPIC::class,

    'cold-room' => ColdRoomPIC::class,

    'rissing-pipette' => RissingPipettePIC::class,

    'walkin-test-chamber-g2' => ChamberWalkinG2PIC::class,

    'chamber-g2' => ChamberG2PIC::class,
];
