<?php

namespace App\Http\Controllers;

use Google\Cloud\Firestore\FirestoreClient;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;

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
        $documents = $collectionReference->documents();

        // Initialize an empty array to hold the data
        $data = [];

        // Loop through each document to extract the data
        foreach ($documents as $doc) {

            
            // ambil semua data
            $documentData = $doc->data();
            $documentId = $doc->id();

            /// ambil value dari firestore
            $name = $documentData['name'] ?? null;
            $nim = $documentData['nim'] ?? null;
            $angkatan = $documentData['angkatan'] ?? null;
            $timestamps = $documentData['timestamps'] ?? null;
            $image = $documentData['image'] ?? null;
            $latitude = $documentData['latitude'] ?? null;
            $longitude = $documentData['longitude'] ?? null;
            

            // generate url langsung dari lat, lang
            $googleMapsUrl = sprintf('https://www.google.com/maps?q=%f,%f', $latitude, $longitude);


            // Generate QR code untuk setiap document ID dan tambahkan ke dalam array data
            

            // Add the extracted data to the $data array
            $data[] = [
                'name' => $name,
                'nim' => $nim,
                'angkatan' => $angkatan,
                'timestamps' => $timestamps,
                'image' => $image,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'googleMapsUrl' => $googleMapsUrl,
                'id'=>$documentId
            ];

        }
        
        // Pass the data to the view
        return view('pages.students', compact('data'));
    }

   

    public function exportExcel()
    {
        return Excel::download(new StudentsExport(), 'students.xlsx');
    }


    
}
