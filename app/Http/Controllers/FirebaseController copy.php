<?php

namespace App\Http\Controllers;

use Google\Cloud\Firestore\FirestoreClient;

class FirebaseController extends Controller
{
    public function index()
    {
        $firestore = new FirestoreClient([
            'projectId' => 'project-sinarindo',
        ]);
        $collectionReference = $firestore->collection('students');

        // Mengambil semua data pada collection
        $query = $collectionReference->orderBy('timestamps', 'desc');
        $documents = $query->documents();

        
        $data = [];
        foreach ($documents as $doc) {
            $documentData = $doc->data();

            // ambil value dari firestore
            $name = $documentData['name'] ?? null;
            $nim = $documentData['nim'] ?? null;
            $angkatan = $documentData['angkatan'] ?? null;
            $timestamps = $documentData['timestamps'] ?? null;
            $image = $documentData['image'] ?? null;
            $latitude = $documentData['latitude'] ?? null;
            $longitude = $documentData['longitude'] ?? null;

            // generate url langsung dari lat, lang
            $googleMapsUrl = sprintf('https://www.google.com/maps?q=%f,%f', $latitude, $longitude);

            $data[] = [
                'name' => $name,
                'nim' => $nim,
                'angkatan' => $angkatan,
                'timestamps' => $timestamps,
                'image' => $image,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'googleMapsUrl' => $googleMapsUrl,
            ];
        }

        return view('pages.students', compact('data'));
    }
}
