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

        // Get the current month and year in the UTC timezone
        $currentMonthYear = date('Y-m', strtotime('now'));
        $totalStudentInAMonth = 0;
        
        $data = [];
        foreach ($documents as $doc) {
            $documentData = $doc->data();
            $timestamps = $documentData['timestamps'] ?? null;

            // Check if the data is recorded in the current month
            $recordedMonthYear = date('Y-m', strtotime($timestamps));
            if ($recordedMonthYear === $currentMonthYear) {
                $totalStudentInAMonth++;
            }

            $data[] = [
                'timestamps' => $timestamps
            ];
        }

        $totalStudents = count($data);
        return view('pages.dashboard', compact('totalStudents', 'totalStudentInAMonth'));
    }

    public function tables()
    {
        return view('pages.tables');
    }

    public function signin()
    {
        return view('newauth.signin');
    }
}
