<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Google\Cloud\Firestore\FirestoreClient;

class KehadiranExport implements FromCollection, WithHeadings
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
        $data = [];

        foreach ($documents as $doc) {
            $documentData = $doc->data();
            $keterangan = $documentData['keterangan'] ?? null;
            $timestamps = $documentData['timestamps'] ?? null;
            $name = $documentData['name'] ?? null;

            $recordedMonthYear = date('Y-m', strtotime($timestamps));
            if ($recordedMonthYear === $currentMonthYear) {
                if (!isset($totals[$recordedMonthYear])) {
                    $totals[$recordedMonthYear] = [
                        'year' => date('Y', strtotime($timestamps)),
                        'month' => date('m', strtotime($timestamps)),
                        'total_students' => 0,
                        'total_masuk' => 0,
                        'total_izin' => 0,
                        'total_sakit' => 0,
                    ];
                }

                $totals[$recordedMonthYear]['total_students']++;
                if ($keterangan === "Masuk") {
                    $totals[$recordedMonthYear]['total_masuk']++;
                } elseif ($keterangan === "Izin") {
                    $totals[$recordedMonthYear]['total_izin']++;
                } elseif ($keterangan === "Sakit") {
                    $totals[$recordedMonthYear]['total_sakit']++;
                }
            }
        }

        // Convert the $totals array to a collection and return
        return collect(array_values($totals));
    }

    public function headings(): array
    {
        return ['Tahun', 'Bulan',  'Jumlah Siswa', 'Jumlah Masuk', 'Jumlah Izin', 'Jumlah Sakit'];
    }
}
