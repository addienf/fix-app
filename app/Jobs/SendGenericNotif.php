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

    public $record;
    public array $roleNames;
    public string $notifClass;
    public string $urlPrefix;
    public string $notifTitle;
    public string $notifBody;

    /**
     * Create a new job instance.
     */
    public function __construct(
        $record,
        array $roleNames,
        string $notifClass,
        string $urlPrefix,
        string $notifTitle,
        string $notifBody
    ) {
        $this->record = $record;
        $this->roleNames = $roleNames;
        $this->notifClass = $notifClass;
        $this->urlPrefix = $urlPrefix;
        $this->notifTitle = $notifTitle;
        $this->notifBody = $notifBody;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $users = User::role($this->roleNames)->get(); // ← Spatie mendukung array di sini

            if ($users->isEmpty()) {
                Log::warning("⚠️ Tidak ada user dengan role: " . implode(', ', $this->roleNames));
                return;
            }

            Log::info("✅ User ditemukan dengan role: " . implode(', ', $this->roleNames), [
                'emails' => $users->pluck('email')->toArray(),
            ]);

            foreach ($users as $user) {
                $notification = new $this->notifClass(
                    $this->record,
                    $this->notifTitle,
                    $this->notifBody,
                    url("{$this->urlPrefix}/{$this->record->id}/edit"),
                    $this->notifTitle
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
            }
        } catch (\Throwable $e) {
            Log::error("❌ Gagal kirim notifikasi ke role", [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
