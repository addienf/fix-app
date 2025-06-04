<?php

namespace App\Jobs\Sales;

use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Models\User;
use App\Notifications\Sales\SpesifikasiProductNotif;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSpesifikasiProductNotif implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected SpesifikasiProduct $record;

    /**
     * Create a new job instance.
     */
    public function __construct(SpesifikasiProduct $record)
    {
        $this->record = $record;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //

        $users = User::where('role', ['sales', 'admin'])->get();

        foreach ($users as $user) {
            // Email (via Laravel Notification)
            $user->notify(new SpesifikasiProductNotif($this->record));

            // Filament Notification (database)
            Notification::make()
                ->title('Data Product Berhasil Dibuat')
                ->body('Ada data product baru yang berhasil dibuat.')
                ->actions([
                    Action::make('Lihat')
                        ->button()
                        ->url(url('/admin/sales/spesifikasi-product/' . $this->record->id . '/edit')),
                ])
                ->sendToDatabase($user);
        }
    }
}
