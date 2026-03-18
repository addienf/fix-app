<?php
return [
    [
        'mainPart' => 'Cooling System',
        'parts' => [
            ['text' => 'Measure the current of cooling system (compressor)', 'show' => false],
            'Pressure analyzer (10Psi - 15Psi)',
            'Normal (2,1 - 2,6 Amp)',
            'Normal (1,0 - 1,5 Amp)',
            'Normal (3,0 - 3,6 Amp)',
        ],
    ],

    [
        'mainPart' => 'Logging System',
        'parts' => [
            ['info' => 'Take the screenshoot / photo of logging data'],
            ['text' => 'Check the memory card logger', 'show' => false],
            ['text' => 'Check used capacity', 'show' => false],
            ['text' => 'Check total files (max 512 files)', 'show' => false],
        ],
    ],

    [
        'mainPart' => 'Interior TL Lamp',
        'parts' => [
            ['text' => 'Check the lamp funcionality', 'show' => false],
            ['text' => 'Check the lamp cover condition', 'show' => false],
        ],
    ],

    [
        'mainPart' => 'Check with Thermal Camera',
        'parts' => [
            'MCB Panel (Temperature 40-45°C)',
            'Contactor (Temperature 40-45°C)',
            'SPC (Temperature 40-45°C)',
        ],
    ],

];
