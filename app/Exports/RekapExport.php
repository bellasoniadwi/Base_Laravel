<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Google\Cloud\Firestore\FirestoreClient;

class RekapExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $user = auth()->user();

        if ($user) {
            $id = $user->localId;

            $firestore = app('firebase.firestore');
            $database = $firestore->database();

            $userDocRef = $database->collection('users')->document($id);
            $userSnapshot = $userDocRef->snapshot();

            if ($userSnapshot->exists()) {
                $nama_akun = $userSnapshot->data()['name'];
                $role_akun = $userSnapshot->data()['role'];
            } else {
                $nama_akun = "Name not found";
                $role_akun = "Role not found";
            }
        } else {
            $nama_akun = "Name ga kebaca";
            $role_akun = "Role ga kebaca";
        }

        $firestore = new FirestoreClient([
            'projectId' => 'project-sinarindo',
        ]);

        $collectionReference = $firestore->collection('students');

        if ($role_akun == 'Superadmin') {
            $query = $collectionReference->orderBy('name');
        } elseif ($role_akun == 'Pelatih') {
            $query = $collectionReference->where('pelatih', '=', $nama_akun);
        } else {
            $query = $collectionReference->orderBy('name');
        }

        $documents = $query->documents();

        $totals = [];

        foreach ($documents as $doc) {
            $documentData = $doc->data();
            $keterangan = $documentData['keterangan'] ?? null;
            $timestamps = $documentData['timestamps'] ?? null;
            $name = $documentData['name'] ?? null;

            // Check if the data is recorded in the current month
            $recordedMonthYear = date('Y-m', strtotime($timestamps));
            if (!isset($totals[$recordedMonthYear])) {
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
                'month' => date('M', strtotime($timestamps)),
                'year' => date('Y', strtotime($timestamps)),
                'total_masuk' => $nameTotal['masuk'],
                'total_izin' => $nameTotal['izin'],
                'total_sakit' => $nameTotal['sakit'],
            ];
        }

        return collect($rekapData);
    }

    public function headings(): array
    {
        return ['Name', 'Bulan', 'Tahun', 'Jumlah Masuk', 'Jumlah Izin', 'Jumlah Sakit'];
    }
}
