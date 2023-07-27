<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Firestore\FirestoreClient;

class HomeController extends Controller
{
    public function dashboard()
    {
        $firestore = new FirestoreClient([
            'projectId' => 'project-sinarindo',
        ]);
        $collectionReference = $firestore->collection('students');
        $documents = $collectionReference->documents();
        $data = [];
        foreach ($documents as $doc) {
            $documentData = $doc->data();
            $name = $documentData['name'] ?? null;
            $nim = $documentData['nim'] ?? null;
            $angkatan = $documentData['angkatan'] ?? null;
            $timestamps = $documentData['timestamps'] ?? null;
            $image = $documentData['image'] ?? null;

            $data[] = [
                'name' => $name,
                'nim' => $nim,
                'angkatan' => $angkatan,
                'timestamps' => $timestamps,
                'image' => $image
            ];
        }

        $totalStudents = count($data);
        return view('pages.dashboard', compact('totalStudents'));
    }

    public function tables()
    {
        return view('pages.tables');
    }
}
