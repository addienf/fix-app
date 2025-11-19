<?php

namespace Database\Factories\Sales\SPKMarketings;

use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class SPKMarketingFactory extends Factory
{
    protected $model = SPKMarketing::class;

    public function definition(): array
    {
        return [
            'spesifikasi_product_id' => SpesifikasiProduct::inRandomOrder()->value('id'),
            'no_spk' => 'SPK-' . $this->faker->unique()->numerify('###/MKT/' . now()->format('m/y')),
            'tanggal' => $this->faker->date(),
            'no_order' => strtoupper($this->faker->bothify('ORD###??')),
            'dari' => $this->faker->company(),
            'kepada' => $this->faker->company(),
            'status_penerimaan' => 'Diterima',
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (SPKMarketing $spk) {

            /** Dummy signature PNG (kecil agar tidak berat) */
            $dummyImageBase64 = 'iVBORw0KGgoAAAANSUhEUgAAAAoAAAAFCAIAAABp6VxuAAAAD0lEQVQI12NgYGD4z0ABBgAANH8EAU6YzWAAAAAASUVORK5CYII=';
            $dummyImage = base64_decode($dummyImageBase64);

            /** Folder tujuan */
            $folder = 'Sales/SPK/Signatures/' . $spk->id;

            /** Generate file */
            $createName = 1;
            $receiveName = 1;

            $createFile = $folder . '/create-' . uniqid() . '.png';
            $receiveFile = $folder . '/receive-' . uniqid() . '.png';

            /** Simpan file ke storage/public */
            Storage::put('public/' . $createFile, $dummyImage);
            Storage::put('public/' . $receiveFile, $dummyImage);

            /** Simpan PIC */
            $spk->pic()->create([
                'create_signature'  => $createFile,
                'create_name'       => $createName,
                'receive_signature' => $receiveFile,
                'receive_name'      => $receiveName,
            ]);
        });
    }
}
