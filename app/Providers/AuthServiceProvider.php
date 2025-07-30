<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Engineering\Berita\BeritaAcara;
use App\Models\Engineering\Complain\Complain;
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
use App\Models\Sales\URS;
use App\Models\Warehouse\Incomming\IncommingMaterial;
use App\Models\Warehouse\Pelabelan\QCPassed;
use App\Models\Warehouse\Peminjaman\PeminjamanAlat;
use App\Models\Warehouse\PermintaanBahanWBB\PermintaanBahan;
use App\Models\Warehouse\SerahTerima\SerahTerimaBahan;
use App\Policies\Engineering\Berita\BeritaAcaraPolicy;
use App\Policies\Engineering\Complain\ComplainPolicy;
use App\Policies\Engineering\Maintenance\ChamberG2\ChamberG2Policy;
use App\Policies\Engineering\Maintenance\ChamberR2\ChamberR2Policy;
use App\Policies\Engineering\Maintenance\ChamberWalkinG2\ChamberWalkinG2Policy;
use App\Policies\Engineering\Maintenance\ColdRoom\ColdRoomPolicy;
use App\Policies\Engineering\Maintenance\Refrigerator\RefrigeratorPolicy;
use App\Policies\Engineering\Maintenance\RissingPipette\RissingPipettePolicy;
use App\Policies\Engineering\Maintenance\WalkinChamber\WalkinChamberPolicy;
use App\Policies\Engineering\Permintaan\PermintaanSparepartPolicy;
use App\Policies\Engineering\Service\ServiceReportPolicy;
use App\Policies\Engineering\SPK\SPKServicePolicy;
use App\Policies\General\CustomerPolicy;
use App\Policies\Production\Jadwal\JadwalProduksiPolicy;
use App\Policies\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectricalPolicy;
use App\Policies\Production\Penyerahan\PenyerahanProdukJadiPolicy;
use App\Policies\Production\PermintaanBahanProduksi\PermintaanAlatDanBahanPolicy;
use App\Policies\Production\SPK\SPKQualityPolicy;
use App\Policies\Production\SPK\SPKVendorPolicy;
use App\Policies\Purchasing\Permintaan\PermintaanPembelianPolicy;
use App\Policies\Quality\Defect\DefectStatusPolicy;
use App\Policies\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSSPolicy;
use App\Policies\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSSPolicy;
use App\Policies\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSSPolicy;
use App\Policies\Quality\Pengecekan\PengecekanPerformaPolicy;
use App\Policies\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectricalPolicy;
use App\Policies\Quality\PengecekanMaterial\SS\PengecekanMaterialSSPolicy;
use App\Policies\Quality\Standarisasi\StandarisasiDrawingPolicy;
use App\Policies\Sales\SpesifikasiProducts\SpesifikasiProductPolicy;
use App\Policies\Sales\SPKMarketings\SPKMarketingPolicy;
use App\Policies\Sales\URSPolicy;
use App\Policies\Warehouse\Incomming\IncommingMaterialPolicy;
use App\Policies\Warehouse\Pelabelan\QCPassedPolicy;
use App\Policies\Warehouse\Peminjaman\PeminjamanAlatPolicy;
use App\Policies\Warehouse\PermintaanBahanWBB\PermintaanBahanPolicy;
use App\Policies\Warehouse\SerahTerima\SerahTerimaBahanPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //

        //General
        Customer::class => CustomerPolicy::class,
        URS::class => URSPolicy::class,

        //Sales
        SpesifikasiProduct::class => SpesifikasiProductPolicy::class,
        SPKMarketing::class => SPKMarketingPolicy::class,

        //Produksi
        JadwalProduksi::class => JadwalProduksiPolicy::class,
        PenyerahanElectrical::class => PenyerahanElectricalPolicy::class,
        PenyerahanProdukJadi::class => PenyerahanProdukJadiPolicy::class,
        PermintaanAlatDanBahan::class => PermintaanAlatDanBahanPolicy::class,
        SPKQuality::class => SPKQualityPolicy::class,
        SPKVendor::class => SPKVendorPolicy::class,

        //Purchasing
        PermintaanPembelian::class => PermintaanPembelianPolicy::class,

        //Warehouse
        IncommingMaterial::class => IncommingMaterialPolicy::class,
        QCPassed::class => QCPassedPolicy::class,
        PeminjamanAlat::class => PeminjamanAlatPolicy::class,
        PermintaanBahan::class => PermintaanBahanPolicy::class,
        SerahTerimaBahan::class => SerahTerimaBahanPolicy::class,

        //QC & QA
        DefectStatus::class => DefectStatusPolicy::class,
        IncommingMaterialSS::class => IncommingMaterialSSPolicy::class,
        IncommingMaterialNonSS::class => IncommingMaterialNonSSPolicy::class,
        KelengkapanMaterialSS::class => KelengkapanMaterialSSPolicy::class,
        PengecekanMaterialSS::class => PengecekanMaterialSSPolicy::class,
        PengecekanMaterialElectrical::class => PengecekanMaterialElectricalPolicy::class,
        PengecekanPerforma::class => PengecekanPerformaPolicy::class,
        StandarisasiDrawing::class => StandarisasiDrawingPolicy::class,

        //Engineering
        BeritaAcara::class => BeritaAcaraPolicy::class,
        PermintaanSparepart::class => PermintaanSparepartPolicy::class,
        ServiceReport::class => ServiceReportPolicy::class,
        SPKService::class => SPKServicePolicy::class,
        ChamberG2::class => ChamberG2Policy::class,
        ChamberR2::class => ChamberR2Policy::class,
        ChamberWalkinG2::class => ChamberWalkinG2Policy::class,
        ColdRoom::class => ColdRoomPolicy::class,
        Refrigerator::class => RefrigeratorPolicy::class,
        RissingPipette::class => RissingPipettePolicy::class,
        WalkinChamber::class => WalkinChamberPolicy::class,
        ServiceReport::class => ServiceReportPolicy::class,
        Complain::class => ComplainPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
