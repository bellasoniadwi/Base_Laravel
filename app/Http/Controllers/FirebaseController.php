<?php

namespace App\Http\Controllers;

use Google\Cloud\Firestore\FirestoreClient;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\HtmlString;



class FirebaseController extends Controller
{
    public function index()
    {
        $firestore = new FirestoreClient([
            'projectId' => 'project-sinarindo',
        ]);
        $collectionReference = $firestore->collection('students');

        // Get all documents from the collection
        $documents = $collectionReference->documents();

        // Initialize an empty array to hold the data
        $data = [];

        // Loop through each document to extract the data
        foreach ($documents as $doc) {
            // ambil semua data
            $documentData = $doc->data();

            // ambil value dari firestore
            $name = $documentData['name'] ?? null;
            $nim = $documentData['nim'] ?? null;
            $angkatan = $documentData['angkatan'] ?? null;
            $timestamps = $documentData['timestamps'] ?? null;
            $image = $documentData['image'] ?? null;

            // Add the extracted data to the $data array
            $data[] = [
                'name' => $name,
                'nim' => $nim,
                'angkatan' => $angkatan,
                'timestamps' => $timestamps,
                'image' => $image
            ];

            QrCode::format('svg')->generate($name);
        }

        // Pass the data to the view
        return view('pages.students', compact('data'));
    }
}
