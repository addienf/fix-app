<?php

namespace Database\Factories\Sales\SpesifikasiProducts;

use App\Models\General\Product;
use App\Models\Sales\SpesifikasiProducts\Pivot\SpesifikasiProductDetail;
use App\Models\Sales\SpesifikasiProducts\Pivot\SpesifikasiProductFiles;
use App\Models\Sales\SpesifikasiProducts\Pivot\SpesifikasiProductPIC;
use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Models\Sales\URS;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class SpesifikasiProductFactory extends Factory
{
    protected $model = SpesifikasiProduct::class;
    public function definition(): array
    {
        $status = 'Diketahui MR';

        $statusPenerimaan = $this->faker->randomElement(['yes', 'no']);

        return [

            'urs_id' => URS::inRandomOrder()->value('id') ?? 1,
            'is_stock' => $this->faker->boolean ? 1 : 0,
            'detail_specification' => $this->faker->sentence(8),
            'delivery_address' => $this->faker->address(),
            'estimasi_pengiriman' => $this->faker->dateTimeBetween('+1 day', '+60 days')->format('Y-m-d'),
            'status_penerimaan_order' => $statusPenerimaan,
            'alasan' => $statusPenerimaan === 'yes' ? $this->faker->sentence() : null,
            'status' => $status,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (SpesifikasiProduct $sp) {
            $dummyImageBase64 = 'iVBORw0KGgoAAAANSUhEUgAAAAoAAAAFCAIAAABp6VxuAAAAD0lEQVQI12NgYGD4z0ABBgAANH8EAU6YzWAAAAAASUVORK5CYII=';
            $dummyImage = base64_decode($dummyImageBase64);
            $dummyPdfContent = '%PDF-1.4\n%âãÏÓ\n1 0 obj\n<< /Type /Catalog >>\nendobj\nxref\n0 1\n0000000000 65535 f \ntrailer\n<< /Root 1 0 R >>\nstartxref\n9\n%%EOF'; // tiny fake
            $detailCount = rand(1, 3);

            for ($i = 0; $i < $detailCount; $i++) {
                $product = Product::inRandomOrder()->first();
                $productId = $product?->id ?? 1;
                $isMecmesin = false;
                if ($product) {
                    $slug = $product->slug ?? null;
                    $categoryId = $product->category_id ?? null;
                    if ($slug === 'mecmesin' || $categoryId === 2) {
                        $isMecmesin = true;
                    }
                }

                if ($isMecmesin) {
                    $spec_mec = [
                        'test_type' => $this->faker->randomElement(['tensile', 'compression', 'torque']), // kode value
                        'jenis_tes' => $this->faker->randomElement(['digital', 'computerised']),
                        'capacity' => (string)$this->faker->numberBetween(1, 100),
                        'sample' => (string)$this->faker->numberBetween(1, 50),
                    ];

                    $detail = SpesifikasiProductDetail::create([
                        'spesifikasi_product_id' => $sp->id,
                        'product_id' => $productId,
                        'specification' => null,
                        'specification_mecmesin' => $spec_mec,
                        'quantity' => $this->faker->numberBetween(1, 5),
                    ]);
                } else {
                    $specArray = [];
                    $cfg = config('spec.spesifikasi', []);

                    foreach ($cfg as $key => $label) {

                        // 3 field yang pakai value_bool
                        if ($label === 'Tipe Chamber') {
                            $specArray[] = [
                                'name'       => $label,
                                'value_str'  => null,
                                'value_bool' => $this->faker->randomElement(['knockdown', 'regular']),
                            ];
                            continue;
                        }

                        if ($label === 'Water Feeding System') {
                            $specArray[] = [
                                'name'       => $label,
                                'value_str'  => null,
                                'value_bool' => $this->faker->randomElement(['yes', 'no']),
                            ];
                            continue;
                        }

                        if ($label === 'Software') {
                            $specArray[] = [
                                'name'       => $label,
                                'value_str'  => null,
                                'value_bool' => $this->faker->randomElement(['with', 'without']),
                            ];
                            continue;
                        }

                        // lainnya pakai STR
                        if (stripos($label, 'Temperature') !== false) {
                            $value = $this->faker->numberBetween(0, 80) . ' °C';
                        } elseif (stripos($label, 'Humidity') !== false) {
                            $value = $this->faker->numberBetween(10, 95) . ' %';
                        } elseif (stripos($label, 'Room Dimension') !== false) {
                            $value = $this->faker->randomElement(['2x2 m', '3x4 m', '4x5 m']);
                        } elseif (stripos($label, 'Capacity') !== false) {
                            $value = $this->faker->numberBetween(10, 500) . ' L';
                        } elseif (stripos($label, 'Rack Quantity') !== false) {
                            $value = $this->faker->numberBetween(0, 20);
                        } else {
                            $value = $this->faker->word();
                        }

                        $specArray[] = [
                            'name'       => $label,
                            'value_str'  => $value,
                            'value_bool' => null,
                        ];
                    }

                    $detail = SpesifikasiProductDetail::create([
                        'spesifikasi_product_id' => $sp->id,
                        'product_id' => $productId,
                        'specification' => $specArray,
                        'specification_mecmesin' => null,
                        'quantity' => $this->faker->numberBetween(1, 5),
                    ]);
                }

                // buat file PDF (1 file) di Sales/Spesifikasi/Files
                $fileName = 'Sales/Spesifikasi/Files/' . $sp->id . '/' . uniqid('file_') . '.pdf';
                Storage::put('public/' . $fileName, $dummyPdfContent);

                SpesifikasiProductFiles::create([
                    'spesifikasi_product_detail_id' => $detail->id,
                    'file_path' => $fileName,
                ]);
            }

            // buat PIC (signatures) di Sales/Spesifikasi/Signatures
            $signedName = 1;
            $acceptedName = 1;
            $ackName = 1;

            $signedFile = 'Sales/Spesifikasi/Signatures/signed-' . $sp->id . '-' . uniqid() . '.png';
            $acceptedFile = 'Sales/Spesifikasi/Signatures/accepted-' . $sp->id . '-' . uniqid() . '.png';
            $ackFile = 'Sales/Spesifikasi/Signatures/ack-' . $sp->id . '-' . uniqid() . '.png';

            Storage::put('public/' . $signedFile, $dummyImage);
            Storage::put('public/' . $acceptedFile, $dummyImage);
            Storage::put('public/' . $ackFile, $dummyImage);

            SpesifikasiProductPIC::create([
                'spesifikasi_product_id' => $sp->id,
                'signed_signature' => $signedFile,
                'signed_name' => $signedName,
                'signed_date' => now(),

                'accepted_signature' => $acceptedFile,
                'accepted_name' => $acceptedName,
                'accepted_date' => now(),

                'acknowledge_signature' => $ackFile,
                'acknowledge_name' => $ackName,
                'acknowledge_date' => now(),
            ]);
        });
    }
}
