<?php

return [
    [
        'mainPart' => 'Cooling System',
        'parts' => [
            ['text' => 'Measure the current of cooling system (compressor)', 'show' => false],
            'Pressure analyzer (10 Psi - 15 Psi)',
            'Normal 2,58 - 2,76A in 220-240V/50Hz',
            'Normal 2,34 - 2,39A in 203-230V/60Hz',
            'Normal 3,46 - 3,71A in 220-240V/50Hz',
            'Normal 3,4 - 3,56A in 203-230V/60Hz',
            ['text' => 'Check the freon leak with halogen leak detector', 'show' => false],
            ['text' => 'Cleaning evaporator (sample unloaded ±50 minutes)', 'show' => false],
        ],
    ],
    [
        'mainPart' => 'Humidification System',
        'parts' => [
            ['info' => 'Measure the resistance of the water heater'],
            'Water Heater 1000 W (46–53 Ohm)',
            'Water Heater 1350 W (33–40 Ohm)',
            ['text' => 'Clean the container of humidifier', 'show' => false],
            ['text' => 'Clean water heater surface', 'show' => false],
            ['text' => 'Clean & check level switch sensor', 'show' => false],
            ['text' => 'Clean the water inlet', 'show' => false],
            ['info' => 'Take photo of heater condition & resistance (Ampere Meter)'],
        ],
    ],
    [
        'mainPart' => 'Air Heating System',
        'parts' => [
            ['info' => 'Measure the resistance of the air heater'],
            'Air Heater 1500 W (30–38 Ohm)',
            'Air Heater 1000 W (46–53 Ohm)',
            'Air Heater 1350 W (33–40 Ohm)',
            ['info' => 'Take photo of air heater condition & resistance'],
        ],
    ],
    [
        'mainPart' => 'Water Feeding System',
        'parts' => [
            'Measure feeding water conductivity (Standard: 20–100 µS)',
        ],
    ],
    [
        'mainPart' => 'Controlling System',
        'parts' => [
            'Check analogue output temperature function',
            'Check analogue output humidity function',
        ],
    ],
    [
        'mainPart' => 'Logging System',
        'parts' => [
            ['info' => 'Screenshot/photo chamber connected to Wonderware'],
            ['text' => 'Report data', 'show' => false],
            ['text' => 'Trend graph', 'show' => false],
            ['text' => 'Event & historical alarm', 'show' => false],
        ],
    ],
    [
        'mainPart' => 'Alarm System',
        'parts' => [
            ['info' => 'Trigger and check all individual alarms'],
            ['text' => 'Safety device', 'show' => false],
            ['text' => 'Low water protection', 'show' => false],
            ['text' => 'Temperature out of range', 'show' => false],
            ['text' => 'Humidity out of range', 'show' => false],
            ['text' => 'Low-level water', 'show' => false],
            ['text' => 'Check all alarm lamps', 'show' => false],
            ['text' => 'Check all alarm buzzers', 'show' => false],
            ['text' => 'Synchronisation of individual alarms & CAS', 'show' => false],
        ],
    ],
    [
        'mainPart' => 'Interior TL Lamp',
        'parts' => [
            ['text' => 'Check lamp functionality', 'show' => false],
            ['text' => 'Check lamp cover condition', 'show' => false],
        ],
    ],
    [
        'mainPart' => 'Check with Thermal Camera',
        'parts' => [
            'MCB Panel (35–45 °C)',
            'Contactor (35–45 °C)',
            'SSR (35–45 °C)',
        ],
    ],
];
