<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Support\Facades\Session;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Exception\FirebaseException;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


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

         // FirebaseAuth.getInstance().getCurrentUser();
         try {
            $uid = Session::get('uid');
            $user = app('firebase.auth')->getUser($uid);
            return view('pages.dashboard', compact('totalStudents', 'totalStudentInAMonth'));
        } catch (\Exception $e) {
            return $e;
        }
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
