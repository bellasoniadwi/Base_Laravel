<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Google\Cloud\Firestore\FirestoreClient;

class RekapExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // fetch nama, role
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

        // set data yang ditampilkan per role/nama
        if ($role_akun == 'Superadmin') {
            $query = $collectionReference->orderBy('name');
        } elseif ($role_akun == 'Instruktur') {
            $query = $collectionReference->where('instruktur', '=', $nama_akun);
        } else {
            $query = $collectionReference->orderBy('name');
        }
        $documents = $query->documents();

        // Inisialisasi array
        $totals = [];
        $rekapData = [];

        $currentMonthYear = date('Y-m', strtotime('now'));
        $currentMonthYearNow = date('M Y', strtotime('now'));
        foreach ($documents as $doc) {
            $documentData = $doc->data();
            $keterangan = $documentData['keterangan'] ?? null;
            $timestamps = $documentData['timestamps'] ?? null;
            $name = $documentData['name'] ?? null;
            $recordedMonthYear = date('Y-m', strtotime($timestamps));
            if ($recordedMonthYear === $currentMonthYear) {
                if (!isset($totals[$name])) {
                    $totals[$name] = [
                        'masuk' => 0,
                        'izin' => 0,
                        'sakit' => 0,
                    ];
                }
                // Menghitung total per nama
                if ($keterangan === "Masuk") {
                    $totals[$name]['masuk']++;
                } elseif ($keterangan === "Izin") {
                    $totals[$name]['izin']++;
                } elseif ($keterangan === "Sakit") {
                    $totals[$name]['sakit']++;
                }
            }
        }

        
        foreach ($totals as $name => $nameTotal) {
            // penentuan predikat
            $totalMasuk = $nameTotal['masuk'];
            if ($totalMasuk >= 18 && $totalMasuk <= 24) {
                $predikat = 'A';
            } elseif ($totalMasuk >= 15 && $totalMasuk <= 17) {
                $predikat = 'B';
            } elseif ($totalMasuk >= 12 && $totalMasuk <= 14) {
                $predikat = 'C';
            } else {
                $predikat = 'D';
            }

            $rekapData[] = [
                'name' => $name,
                'month' => date('M', strtotime($timestamps)),
                'year' => date('Y', strtotime($timestamps)),
                'total_masuk' => $totalMasuk,
                'total_izin' => $nameTotal['izin'],
                'total_sakit' => $nameTotal['sakit'],
                'predikat' => $predikat
            ];
        }

        return collect($rekapData);
    }

    public function headings(): array
    {
        return ['Name', 'Bulan', 'Tahun', 'Jumlah Masuk', 'Jumlah Izin', 'Jumlah Sakit', 'Predikat'];
    }
}
