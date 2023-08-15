<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Google\Cloud\Firestore\FirestoreClient;

class KehadiranExport implements FromCollection, WithHeadings
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
        } elseif ($role_akun == 'Instruktur') {
            $query = $collectionReference->where('instruktur', '=', $nama_akun);
        } else {
            $query = $collectionReference->orderBy('name');
        }

        $documents = $query->documents();

        $totals = [];

        foreach ($documents as $doc) {
            $documentData = $doc->data();
            $keterangan = $documentData['keterangan'] ?? null;
            $timestamps = $documentData['timestamps'] ?? null;

            $recordedMonthYear = date('Y-m', strtotime($timestamps));
                if (!isset($totals[$recordedMonthYear])) {
                    $totals[$recordedMonthYear] = [
                        'month' => date('M', strtotime($timestamps)),
                        'year' => date('Y', strtotime($timestamps)),
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

        // Convert the $totals array to a collection and return
        return collect(array_values($totals));
    }

    public function headings(): array
    {
        return ['Bulan', 'Tahun', 'Jumlah Siswa', 'Jumlah Masuk', 'Jumlah Izin', 'Jumlah Sakit'];
    }
}
