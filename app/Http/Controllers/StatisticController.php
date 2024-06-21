<?php

namespace App\Http\Controllers;

use App\Models\Release;
use App\Models\Split;
use App\Models\History;

class StatisticController extends Controller
{
    public function get()
    {
        return view('admin.statistic.upload')->render();
    }

    public function post()
    {
        // $this->backupDatabase();
        $this->resetIncome();
        $this->addFileIntoDatabase();
        $this->calcIncome();

        return response()->json('true');
    }

    private function backupDatabase()
    {
        $databaseName = env('DB_DATABASE');
        $backupPath = 'backups/';
        $maxBackups = 10;
        $backupFiles = glob(base_path($backupPath . '*.sql'));

        if (count($backupFiles) > $maxBackups) {
            usort($backupFiles, function ($a, $b) {
                return filemtime($a) - filemtime($b);
            });

            $backupsToRemove = count($backupFiles) - $maxBackups;
            for ($i = 0; $i < $backupsToRemove; $i++) {
                unlink($backupFiles[$i]);
            }
        }

        $backupFileName = $databaseName . '_' . date('Y-m-d_H-i-s') . '.sql';
        $backupFullPath = base_path($backupPath . $backupFileName);

        if (!is_dir(base_path($backupPath))) {
            mkdir(base_path($backupPath), 0775, true);
        }

        $command = "mysqldump -u" . env('DB_USERNAME') . " -p" . env('DB_PASSWORD') . " {$databaseName} > '{$backupFullPath}'";
        exec($command);

        if (!file_exists($backupFullPath)) {
            throw new \Exception('Error creating the backup: ' . $command);
        }
    }

    public function addBarcodeToRelease($releaseTitle, $barcode)
    {
        $query = Release::where('title', $releaseTitle)->whereNull('barcode')->first();

        if ($query === null || !$query->exists()) {
            return;
        }

        $query->update([
            'barcode' => $barcode
        ]);
    }

    private function addFileIntoDatabase()
    {
        $file = request()->file('file');
        $csv = array_map('str_getcsv', file($file->getPathname()));
        $header = array_shift($csv);

        foreach ($csv as $row) {
            $data = array_combine($header, $row);
            $query = History::where('statement_date', date('Y-m-d', strtotime($data['Statement date'])))
                ->where('release_title', $data['Release title'])
                ->where('barcode', $data['Barcode'])->first();

            if ($query === null || !$query->exists()) {
                $newUpload = new History();
                $newUpload->statement_date = date('Y-m-d', strtotime($data['Statement date']));
                $newUpload->release_title = $data['Release title'];
                $newUpload->barcode = $data['Barcode'];
                $newUpload->track_downloads = $data['Track downloads'];
                $newUpload->track_streams = $data['Track streams'];
                $newUpload->release_downloads = $data['Release downloads'];
                $newUpload->track_downloads_revenue = $data['Track downloads revenue'];
                $newUpload->track_streams_revenue = $data['Track streams revenue'];
                $newUpload->release_downloads_revenue = $data['Release downloads revenue'];
                $newUpload->total_revenue = $data['Total revenue'];
                $newUpload->save();

                $this->addBarcodeToRelease($data['Release title'], $data['Barcode']);
            } else {
                $query = History::where('statement_date', date('Y-m-d', strtotime($data['Statement date'])))
                    ->where('release_title', $data['Release title'])
                    ->where('barcode', $data['Barcode'])
                    ->where('track_downloads', $data['Track downloads'])
                    ->where('track_streams', $data['Track streams'])
                    ->where('release_downloads', $data['Release downloads'])
                    ->where('track_downloads_revenue', round($data['Track downloads'], 2))
                    ->where('track_streams_revenue', round($data['Track streams revenue'], 2))
                    ->where('release_downloads_revenue', round($data['Release downloads revenue'], 2))
                    ->where('total_revenue', round($data['Total revenue'], 2))
                    ->first();

                if ($query === null || !$query->exists()) {
                    $query = History::where('statement_date', date('Y-m-d', strtotime($data['Statement date'])))
                        ->where('release_title', $data['Release title'])
                        ->where('barcode', $data['Barcode'])->first();
                    $query->update([
                        'statement_date' => date('Y-m-d', strtotime($data['Statement date'])),
                        'release_title' => $data['Release title'],
                        'barcode' => $data['Barcode'],
                        'track_downloads' => $data['Track downloads'],
                        'track_streams' => $data['Track streams'],
                        'release_downloads' => $data['Release downloads'],
                        'track_downloads_revenue' => $data['Track downloads revenue'],
                        'track_streams_revenue' => $data['Track streams revenue'],
                        'release_downloads_revenue' => $data['Release downloads revenue'],
                        'total_revenue' => $data['Total revenue']
                    ]);
                }
            }
        }
    }

    private function resetIncome()
    {
        // Release::each(function ($release) {
        //     $release->income = 0;
        //     $release->save();
        // });

        Split::each(function ($split) {
            $split->income = 0;
            $split->save();
        });
    }

    private function calcIncome()
    {
        $releases = Release::all();
        $histories = History::all();

        // foreach ($histories as $history) {
        //     $remains = $this->saveLabelIncome($releases, $history);
        // }


        // $this->saveArtistsIncome($release, $history, $remains);
    }
}
