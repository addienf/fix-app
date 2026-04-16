<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;

class GoogleSheetSyncService
{
    public function appendBarangMasuk(array $rows)
    {
        $client = new Client();

        $client->setApplicationName('Warehouse Sync');
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig(storage_path('app/google-service-account.json'));

        $service = new Sheets($client);

        $spreadsheetId = env('GOOGLE_SHEET_ID');

        $body = new ValueRange([
            'values' => $rows,
        ]);

        return $service->spreadsheets_values->append(
            $spreadsheetId,
            'Tes Sync!A1:G',
            $body,
            [
                'valueInputOption' => 'USER_ENTERED',
                'insertDataOption' => 'INSERT_ROWS',
            ]
        );
    }
}
