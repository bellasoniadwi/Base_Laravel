<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Google\Cloud\Firestore\FirestoreClient;

class RekapExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $firestore = new FirestoreClient([
            'projectId' => 'project-sinarindo',
        ]);

        $collectionReference = $firestore->collection('students');
        $documents = $collectionReference->documents();
        $currentMonthYear = date('Y-m', strtotime('now'));

        $totals = [];

        foreach ($documents as $doc) {
            $documentData = $doc->data();
            $keterangan = $documentData['keterangan'] ?? null;
            $timestamps = $documentData['timestamps'] ?? null;
            $name = $documentData['name'] ?? null;

            // Check if the data is recorded in the current month
            $recordedMonthYear = date('Y-m', strtotime($timestamps));
            if ($recordedMonthYear === $currentMonthYear) {
                // Initialize the totals for each name
                if (!isset($totals[$name])) {
                    $totals[$name] = [
                        'masuk' => 0,
                        'izin' => 0,
                        'sakit' => 0,
                    ];
                }

                // Calculate totals for each name
                if ($keterangan === "Masuk") {
                    $totals[$name]['masuk']++;
                } elseif ($keterangan === "Izin") {
                    $totals[$name]['izin']++;
                } elseif ($keterangan === "Sakit") {
                    $totals[$name]['sakit']++;
                }
            }
        }

        $rekapData = [];
        foreach ($totals as $name => $nameTotal) {
            $rekapData[] = [
                'name' => $name,
                'total_masuk' => $nameTotal['masuk'],
                'total_izin' => $nameTotal['izin'],
                'total_sakit' => $nameTotal['sakit'],
            ];
        }

        return collect($rekapData);
    }

    public function headings(): array
    {
        return ['Name', 'Jumlah Masuk', 'Jumlah Izin', 'Jumlah Sakit'];
    }
}
