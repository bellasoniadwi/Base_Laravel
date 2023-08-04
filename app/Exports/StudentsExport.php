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
        $data = [];

        if ($role_akun == 'Superadmin') {
            $query = $collectionReference->orderBy('name');
        } elseif ($role_akun == 'Admin') {
            $query = $collectionReference->where('pelatih', '=', $nama_akun);
        } else {
            $query = $collectionReference->orderBy('name');
        }

        $documents = $query->documents();
        foreach ($documents as $doc) {
            $documentData = $doc->data();
            $id = $doc->id();
            $name = $documentData['name'] ?? null;
            $nim = $documentData['nim'] ?? null;
            $angkatan = $documentData['angkatan'] ?? null;
            $keterangan = $documentData['keterangan'] ?? null;
            $pelatih = $documentData['pelatih'] ?? null;
            $timestamps = $documentData['timestamps'] ?? null;
            $image = $documentData['image'] ?? null;
            $latitude = $documentData['latitude'] ?? null;
            $longitude = $documentData['longitude'] ?? null;
            
            

            $data[] = [
                'id' => $id,
                'name' => $name,
                'nim' => $nim,
                'angkatan' => $angkatan,
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
        return ['ID', 'Name', 'NIM', 'Angkatan', 'Keterangan','Pembimbing','Timestamps', 'Image', 'Latitude', 'Longitude' ];
    }
}
