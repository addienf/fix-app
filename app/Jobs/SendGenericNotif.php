<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\GenericNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class SendGenericNotif implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Model $record;
    protected string $relationTable;
    protected string $foreignKeyColumn;
    protected string $signatureColumn;
    protected string $nameColumn;
    protected string $notificationClass;
    protected string $urlPrefix;
    protected string $notifTitle;
    protected string $notifBody;

    /**
     * Create a new job instance.
     */
    public function __construct(
        Model $record,
        string $relationTable,
        string $foreignKeyColumn,
        string $signatureColumn,
        string $nameColumn,
        string $notificationClass,
        string $urlPrefix,
        string $notifTitle,
        string $notifBody
    ) {
        $this->record = $record;
        $this->relationTable = $relationTable;
        $this->foreignKeyColumn = $foreignKeyColumn;
        $this->signatureColumn = $signatureColumn;
        $this->nameColumn = $nameColumn;
        $this->notificationClass = $notificationClass;
        $this->urlPrefix = $urlPrefix;
        $this->notifTitle = $notifTitle;
        $this->notifBody = $notifBody;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // STEP 1
        try {
            $signedUserIds = DB::table($this->relationTable)
                ->where($this->foreignKeyColumn, $this->record->id)
                ->whereNotNull($this->signatureColumn)
                ->pluck($this->nameColumn);

            Log::info('✅ STEP 1: Nama user ditemukan', [
                'user_ids' => $signedUserIds->toArray(),
            ]);
        } catch (\Throwable $e) {
            Log::error('❌ STEP 1 GAGAL', ['error' => $e->getMessage()]);
            return;
        }

        // STEP 2
        try {
            $users = User::whereIn('id', $signedUserIds)->get();

            if ($users->isEmpty()) {
                Log::warning('⚠️ STEP 2: Tidak ada user ditemukan');
                return;
            }

            Log::info('✅ STEP 2: User ditemukan', [
                'user_emails' => $users->pluck('email')->toArray(),
            ]);
        } catch (\Throwable $e) {
            Log::error('❌ STEP 2 GAGAL', ['error' => $e->getMessage()]);
            return;
        }

        // STEP 3
        foreach ($users as $user) {
            try {
                $notification = new GenericNotification(
                    $this->record,
                    $this->notifTitle, // untuk subject email
                    $this->notifBody,  // untuk isi email & database
                    url("{$this->urlPrefix}/{$this->record->id}/edit"), // URL
                    $this->notifTitle  // untuk title database
                );

                $user->notify($notification);
                Log::info("📨 Email dikirim ke {$user->email}");

                Notification::make()
                    ->title($this->notifTitle)
                    ->body($this->notifBody)
                    ->actions([
                        Action::make('Lihat')
                            ->button()
                            ->url(url("{$this->urlPrefix}/{$this->record->id}/edit")),
                    ])
                    ->sendToDatabase($user);

                Log::info("🗂️ Notifikasi database dikirim ke {$user->email}");
            } catch (\Throwable $e) {
                Log::error("❌ Gagal kirim notifikasi ke {$user->email}", [
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
