<?php

namespace App\Http\Controllers;

use App\Models\Release;
use App\Models\Split;
use App\Models\History;
use App\Models\Income;

class StatisticController extends Controller
{
    public function get()
    {
        return view('admin.statistic.upload')->render();
    }

    public function post()
    {
        $this->backupDatabase();
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

        try {
            $query->update([
                'barcode' => $barcode
            ]);
        } catch (\Exception $e) {
            dump('Barcode not added to release: ' . $releaseTitle . ' - ' . $barcode);
        }
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
            }

            $this->addBarcodeToRelease($data['Release title'], $data['Barcode']);
        }
    }

    private function resetIncome()
    {
        Release::each(function ($release) {
            $release->income = 0;
            $release->save();
        });

        Split::each(function ($split) {
            $split->income = 0;
            $split->save();
        });
    }

    private function saveLabelIncome($history, $label)
    {
        $label->percentage = $label->percentage ?? 0;
        $history->total_revenue = $history->total_revenue ?? 0;
        $remains = 0;

        if ((bool) $label->reserved === true) {
            if ($label->income + $history->total_revenue >= 50) {
                $remains = abs(50 - ($label->income + $history->total_revenue));
                $label->reserved = false;
                $label->income = 50 + ($remains * ($label->percentage / 100));
            } else {
                $label->income += $history->total_revenue;
            }
        } else {
            $label->income += ($history->total_revenue * ($label->percentage / 100));
        }

        $label->save();

        return $remains;
    }

    private function saveArtistsIncome($history, $remains, $release)
    {
        $query = Split::where('release_id', $release->id)->get();

        $query->each(function ($artist) use ($history, $release, $remains) {
            $artist->percentage = $artist->percentage ?? 0;
            $history->total_revenue = $history->total_revenue ?? 0;
            $split = 0;

            if ((bool) $release->reserved === false) {
                if ($remains > 0) {
                    $split = $remains * ($artist->percentage / 100);
                } else {
                    $split = ($history->total_revenue * ($artist->percentage / 100));
                }
            }

            $query = Income::where('statement_date', date('Y-m-d', strtotime($history['statement_date'])))->where('split_id', $artist->id)->first();

            if ($query === null || !$query->exists()) {
                $newIncome = new Income();
                $newIncome->statement_date = date('Y-m-d', strtotime($history['statement_date']));
                $newIncome->income = $split;
                $newIncome->split_id = $artist->id;
                $newIncome->save();
            }

            $artist->income += $split;
            $artist->save();
        });
    }

    private function calcIncome()
    {
        $histories = History::all();

        foreach ($histories as $history) {
            $query = Release::where('barcode', $history->barcode)->first();

            if ($query === null || !$query->exists()) {
                dump('Release not found: ' . $history->release_title . ' - ' . $history->barcode);
                continue;
            }

            $remains = $this->saveLabelIncome($history, $query);
            $this->saveArtistsIncome($history, $remains, $query);
        }
    }
}
