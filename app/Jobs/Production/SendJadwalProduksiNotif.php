<?php

namespace App\Jobs\Production;

use App\Models\Production\Jadwal\JadwalProduksi;
use App\Models\User;
use App\Notifications\Production\JadwalProduksiNotif;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class SendJadwalProduksiNotif implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected JadwalProduksi $record;
    /**
     * Create a new job instance.
     */
    public function __construct(JadwalProduksi $record)
    {
        $this->record = $record;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('🚀 [JOB] SendJadwalProduksiNotif dimulai', [
            'record_id' => $this->record->id,
        ]);

        // STEP 1: Ambil user ID dari signature
        try {
            $signedUserIds = DB::table('jadwal_produksi_pics')
                ->where('jadwal_produksi_id', $this->record->id)
                ->whereNotNull('approve_signature') // hanya yang sudah isi tanda tangan
                ->pluck('approve_name'); // ID user penerima

            Log::info('✅ STEP 1: Signed approve_user IDs ditemukan', [
                'user_ids' => $signedUserIds->toArray(),
            ]);
        } catch (\Throwable $e) {
            Log::error('❌ STEP 1 GAGAL: Gagal ambil user dari approve_signature', [
                'error' => $e->getMessage(),
            ]);
            return;
        }

        // STEP 2: Ambil user dari database
        try {
            $users = User::whereIn('id', $signedUserIds)->get();

            if ($users->isEmpty()) {
                Log::warning('⚠️ STEP 2: Tidak ada user yang ditemukan dari ID tersebut');
                return;
            }

            Log::info('✅ STEP 2: User berhasil ditemukan', [
                'user_emails' => $users->pluck('email')->toArray(),
            ]);
        } catch (\Throwable $e) {
            Log::error('❌ STEP 2 GAGAL: Gagal ambil data user', [
                'error' => $e->getMessage(),
            ]);
            return;
        }

        // STEP 3: Kirim notifikasi
        foreach ($users as $user) {
            try {
                // Email via Laravel Notification
                $user->notify(new JadwalProduksiNotif($this->record));
                Log::info("📨 Notifikasi email dikirim ke {$user->email}");

                // Filament Notification
                Notification::make()
                    ->title('Data Jadwal Produksi Berhasil Dibuat')
                    ->body('Ada data Jadwal Produksi yang telah Anda tanda tangani.')
                    ->actions([
                        Action::make('Lihat')
                            ->button()
                            ->url(url('/admin/produksi/jadwal-produksi/' . $this->record->id . '/edit')),
                    ])
                    ->sendToDatabase($user);

                Log::info("🗂️ Notifikasi database dikirim ke {$user->email}");
            } catch (\Throwable $e) {
                Log::error("❌ Gagal kirim notifikasi ke {$user->email}", [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('✅ [JOB] SendJadwalProduksiNotif SELESAI');
    }
}
