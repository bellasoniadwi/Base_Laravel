<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Google\Cloud\Firestore\FirestoreClient;

class StudentsExport implements FromCollection, WithHeadings
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
                $nomor_induk_akun = $userSnapshot->data()['nomor_induk'];
                $angkatan_akun = $userSnapshot->data()['angkatan'];
            } else {
                $nama_akun = "Name not found";
                $role_akun = "Role not found";
                $nomor_induk_akun = "Nomor Induk not found";
                $angkatan_akun = "Angkatan not found";
            }
        } else {
            $nama_akun = "Name ga kebaca";
            $role_akun = "Role ga kebaca";
            $nomor_induk_akun = "Nomor Induk not found";
            $angkatan_akun = "Angkatan not found";
        }

        $firestore = new FirestoreClient([
            'projectId' => 'project-sinarindo',
        ]);

        $collectionReference = $firestore->collection('students');
        $data = [];

        if ($role_akun == 'Superadmin') {
            $query = $collectionReference->orderBy('name');
        } elseif ($role_akun == 'Instruktur') {
            $query = $collectionReference->where('instruktur', '=', $nama_akun);
        } else {
            $query = $collectionReference->orderBy('name');
        }

        $documents = $query->documents();
        
        foreach ($documents as $doc) {
            $documentData = $doc->data();
            $id = $doc->id();
            $name = $documentData['name'] ?? null;
            $nomor_induk_akun = $userSnapshot->data()['nomor_induk'];
                $angkatan_akun = $userSnapshot->data()['angkatan'];
            $keterangan = $documentData['keterangan'] ?? null;
            $pelatih = $documentData['pelatih'] ?? null;
            $timestamps = $documentData['timestamps'] ?? null;
            $image = $documentData['image'] ?? null;
            $latitude = $documentData['latitude'] ?? null;
            $longitude = $documentData['longitude'] ?? null;
            
            
            

            $data[] = [
                'id' => $id,
                'name' => $name,
                'nomor_induk' => $nomor_induk_akun,
                'angkatan' => $angkatan_akun,
                'keterangan' => $keterangan,
                'pelatih' => $pelatih,
                'timestamps' => $timestamps,
                'image' => $image,
                'latitude' => $latitude,
                'longitude' => $longitude,
                
                
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return ['ID', 'Nama', 'Nomor Induk', 'Angkatan', 'Keterangan','Instruktur','Timestamps', 'Image', 'Latitude', 'Longitude' ];
    }
}
