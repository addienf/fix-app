<?php

namespace App\Jobs\Sales;

use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Models\User;
use App\Notifications\Sales\SpkMarketingNotif;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendSpkMarketingNotif implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected SPKMarketing $record;

    /**
     * Create a new job instance.
     */
    public function __construct(SPKMarketing $record)
    {
        $this->record = $record;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('🚀 [JOB] SendSPKMarketingNotif dimulai', [
            'record_id' => $this->record->id,
        ]);

        // STEP 1: Ambil user ID dari signature
        try {
            $signedUserIds = DB::table('spk_marketing_pics')
                ->where('spk_marketing_id', $this->record->id)
                ->whereNotNull('receive_signature') // hanya yang sudah isi tanda tangan
                ->pluck('receive_name'); // ID user penerima

            Log::info('✅ STEP 1: Signed receive_user IDs ditemukan', [
                'user_ids' => $signedUserIds->toArray(),
            ]);
        } catch (\Throwable $e) {
            Log::error('❌ STEP 1 GAGAL: Gagal ambil user dari receive_signature', [
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
                $user->notify(new SpkMarketingNotif($this->record));
                Log::info("📨 Notifikasi email dikirim ke {$user->email}");

                // Filament Notification
                Notification::make()
                    ->title('Data SPK Marketing Berhasil Dibuat')
                    ->body('Ada data SPK Marketing yang telah Anda tanda tangani.')
                    ->actions([
                        Action::make('Lihat')
                            ->button()
                            ->url(url('/admin/sales/spk-marketing/' . $this->record->id . '/edit')),
                    ])
                    ->sendToDatabase($user);

                Log::info("🗂️ Notifikasi database dikirim ke {$user->email}");
            } catch (\Throwable $e) {
                Log::error("❌ Gagal kirim notifikasi ke {$user->email}", [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('✅ [JOB] SendSPKMarketingNotif SELESAI');
    }
}
