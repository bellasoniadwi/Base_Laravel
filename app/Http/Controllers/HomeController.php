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

    public function rekap()
{
    $firestore = new FirestoreClient([
        'projectId' => 'project-sinarindo',
    ]);
    $collectionReference = $firestore->collection('students');
    $documents = $collectionReference->documents();

    // Get the current month and year in the UTC timezone
    $currentMonthYear = date('Y-m', strtotime('now'));

    // Inisialisasi array untuk menyimpan total keterangan per field "name"
    $totals = [];

    foreach ($documents as $doc) {
        $documentData = $doc->data();
        $keterangan = $documentData['keterangan'] ?? null;
        $timestamps = $documentData['timestamps'] ?? null;
        $name = $documentData['name'] ?? null;

        // Check if the data is recorded in the current month
        $recordedMonthYear = date('Y-m', strtotime($timestamps));
        if ($recordedMonthYear === $currentMonthYear) {
            // Jika field "name" belum ada dalam array $totals, tambahkan dengan nilai awal 0
            if (!isset($totals[$name])) {
                $totals[$name] = [
                    'masuk' => 0,
                    'izin' => 0,
                    'sakit' => 0,
                ];
            }

            // Hitung total keterangan "Masuk", "Izin", dan "Sakit" per field "name"
            if ($keterangan === "Masuk") {
                $totals[$name]['masuk']++;
            } elseif ($keterangan === "Izin") {
                $totals[$name]['izin']++;
            } elseif ($keterangan === "Sakit") {
                $totals[$name]['sakit']++;
            }
        }
    }

    // Hitung total keseluruhan dari masing-masing keterangan
    $totalMasuk = 0;
    $totalIzin = 0;
    $totalSakit = 0;

    // Looping untuk menghitung totalMasuk, totalIzin, dan totalSakit dari value field "nama" yang sama
    foreach ($totals as $nameTotal) {
        $totalMasuk += $nameTotal['masuk'];
        $totalIzin += $nameTotal['izin'];
        $totalSakit += $nameTotal['sakit'];
    }

    return view('pages.rekap', compact('totals', 'totalMasuk', 'totalIzin', 'totalSakit'));
}


}