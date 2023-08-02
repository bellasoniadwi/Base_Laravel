<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Google\Cloud\Firestore\FirestoreClient;

class StudentsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $firestore = new FirestoreClient([
            'projectId' => 'project-sinarindo',
        ]);

        $collectionReference = $firestore->collection('students');
        $documents = $collectionReference->documents();

        $data = [];
        foreach ($documents as $doc) {
            $documentData = $doc->data();
            $id = $doc->id();
            $name = $documentData['name'] ?? null;
            $nim = $documentData['nim'] ?? null;
            $angkatan = $documentData['angkatan'] ?? null;
            $timestamps = $documentData['timestamps'] ?? null;
            $image = $documentData['image'] ?? null;
            $latitude = $documentData['latitude'] ?? null;
            $longitude = $documentData['longitude'] ?? null;
            

            $data[] = [
                'id' => $id,
                'name' => $name,
                'nim' => $nim,
                'angkatan' => $angkatan,
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
        return ['ID', 'Name', 'NIM', 'Angkatan', 'Timestamps', 'Image', 'Latitude', 'Longitude'];
    }
}
