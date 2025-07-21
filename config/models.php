<?php

use App\Models\Engineering\Berita\BeritaAcara;
use App\Models\Engineering\Maintenance\ChamberG2\ChamberG2;
use App\Models\Engineering\Maintenance\ChamberR2\ChamberR2;
use App\Models\Engineering\Maintenance\ChamberWalkinG2\ChamberWalkinG2;
use App\Models\Engineering\Maintenance\ColdRoom\ColdRoom;
use App\Models\Engineering\Maintenance\Refrigerator\Refrigerator;
use App\Models\Engineering\Maintenance\RissingPipette\RissingPipette;
use App\Models\Engineering\Maintenance\WalkinChamber\WalkinChamber;
use App\Models\Engineering\Permintaan\PermintaanSparepart;
use App\Models\Engineering\Service\ServiceReport;
use App\Models\Engineering\SPK\SPKService;
use App\Models\General\Customer;
use App\Models\General\Product;
use App\Models\Production\Jadwal\JadwalProduksi;
use App\Models\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectrical;
use App\Models\Production\Penyerahan\PenyerahanProdukJadi;
use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Production\SPK\SPKQuality;
use App\Models\Production\SPK\SPKVendor;
use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use App\Models\Quality\Defect\DefectStatus;
use App\Models\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSS;
use App\Models\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSS;
use App\Models\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSS;
use App\Models\Quality\Pengecekan\PengecekanPerforma;
use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
use App\Models\Quality\Standarisasi\StandarisasiDrawing;
use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Models\User;
use App\Models\Warehouse\Incomming\IncommingMaterial;
use App\Models\Warehouse\Pelabelan\QCPassed;
use App\Models\Warehouse\Peminjaman\PeminjamanAlat;
use App\Models\Warehouse\PermintaanBahanWBB\PermintaanBahan;
use App\Models\Warehouse\SerahTerima\SerahTerimaBahan;

return [
    'general' => [
        'user' => [
            'label' => 'User',
            'model' => User::class,
        ],
        'customer' => [
            'label' => 'Customer',
            'model' => Customer::class,
        ],
        'produk' => [
            'label' => 'Produk',
            'model' => Product::class,
        ],
    ],

    'sales' => [
        'specProduct' => [
            'label' => 'Spesifikasi Product',
            'model' => SpesifikasiProduct::class,
        ],
        'spkMkt' => [
            'label' => 'SPK Marketing',
            'model' => SPKMarketing::class,
        ],
    ],

    'warehouse' => [
        'incomingMaterial' => [
            'label' => 'Incoming Material',
            'model' => IncommingMaterial::class,
        ],
        'pelabelanQC' => [
            'label' => 'Pelabelan QC Passed',
            'model' => QCPassed::class,
        ],
        'permintaanBahan' => [
            'label' => 'Permintaan Bahan Warehouse',
            'model' => PermintaanBahan::class,
        ],
        'peminjamanAlat' => [
            'label' => 'Peminjaman Alat',
            'model' => PeminjamanAlat::class,
        ],
        'serahTerima' => [
            'label' => 'Serah Terima Bahan',
            'model' => SerahTerimaBahan::class,
        ],
    ],

    'quality' => [
        'standarisasiDrawing' => [
            'label' => 'Standarisasi Gambar Kerja',
            'model' => StandarisasiDrawing::class,
        ],
        'kelengkapanMaterialSS' => [
            'label' => 'Kelengkapan Material Stainless Steel',
            'model' => KelengkapanMaterialSS::class,
        ],
        'pengecekanMaterialSS' => [
            'label' => 'Pengecekan Material Stainless Steel',
            'model' => PengecekanMaterialSS::class,
        ],
        'incomingMaterialSS' => [
            'label' => 'Incoming Material Stainless Steel',
            'model' => IncommingMaterialSS::class,
        ],
        'pengecekanMaterialElectrical' => [
            'label' => 'Pengecekan Material Electrical',
            'model' => PengecekanMaterialElectrical::class,
        ],
        'incomingMaterialNonSS' => [
            'label' => 'Incoming Material Non Stainless Steel',
            'model' => IncommingMaterialNonSS::class,
        ],
        'pengcekanPerforma' => [
            'label' => 'Pengecekan Performa',
            'model' => PengecekanPerforma::class,
        ],
        'defectStatus' => [
            'label' => 'Defect Status',
            'model' => DefectStatus::class,
        ],
    ],

    'purchase' => [
        'permintaanPembelian' => [
            'label' => '',
            'model' => PermintaanPembelian::class,
        ]
    ],

    'produksi' => [
        'jadwalProduksi' => [
            'label' => 'Jadwal Produksi',
            'model' => JadwalProduksi::class,
        ],
        'permintaanBahanProduksi' => [
            'label' => 'Permintaan Bahan dan Alat Produksi',
            'model' => PermintaanAlatDanBahan::class,
        ],
        'penyerahanProdukJadi' => [
            'label' => 'Penyerahan Produk Jadi',
            'model' => PenyerahanProdukJadi::class,
        ],
        'serahTerimaElectrical' => [
            'label' => 'Serah Terima Elctrical',
            'model' => PenyerahanElectrical::class,
        ],
        'spkVendor' => [
            'label' => 'SPK Vendor',
            'model' => SPKVendor::class,
        ],
        'spkQuality' => [
            'label' => 'SPK Quality',
            'model' => SPKQuality::class,
        ],
    ],

    'engineer' => [
        'spkService' => [
            'label' => 'SPK Service',
            'model' => SPKService::class,
        ],
        'permintaanSparepart' => [
            'label' => 'Permintaan Sparepart',
            'model' => PermintaanSparepart::class,
        ],
        'beritaAcara' => [
            'label' => 'Berita Acara',
            'model' => BeritaAcara::class,
        ],
        'walkinChamber' => [
            'label' => 'Walk-in Chamber',
            'model' => WalkinChamber::class,
        ],
        'chamberR2' => [
            'label' => 'Chamber R2',
            'model' => ChamberR2::class,
        ],
        'refrigerator' => [
            'label' => 'Refrigerator',
            'model' => Refrigerator::class,
        ],
        'coldRoom' => [
            'label' => 'Cold Room',
            'model' => ColdRoom::class,
        ],
        'rissingPipette' => [
            'label' => 'Rissing Pipette',
            'model' => RissingPipette::class,
        ],
        'walkinChamberG2' => [
            'label' => 'Walk-in Chamber G2',
            'model' => ChamberWalkinG2::class,
        ],
        'chamberG2' => [
            'label' => 'Chamber G2',
            'model' => ChamberG2::class,
        ],
        'serviceReport' => [
            'label' => 'Service Report',
            'model' => ServiceReport::class,
        ],
    ]
];
