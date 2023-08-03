<?php

namespace App\Http\Controllers;

use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapExport;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function ceksaya()
    {
        $user = auth()->user();

        if ($user) {
            $id = $user->localId;
            $email = $user->email;

            $firestore = app('firebase.firestore');
            $database = $firestore->database();

            $userDocRef = $database->collection('users')->document($id);
            $userSnapshot = $userDocRef->snapshot();

            if ($userSnapshot->exists()) {
                $name = $userSnapshot->data()['name'];
                $role = $userSnapshot->data()['role'];
            } else {
                $name = "Name not found";
                $role = "Role not found";
            }
        } else {
            $id = "Id ga kebaca";
            $email = "Email ga kebaca";
            $name = "Name ga kebaca";
            $role = "Role ga kebaca";
        }

        return view('pages.ceksaya', compact('id', 'email', 'name', 'role'));
    }

    public function dashboard()
    {
        $firestore = new FirestoreClient([
            'projectId' => 'project-sinarindo',
        ]);
        $collectionReference = $firestore->collection('students');
        $documents = $collectionReference->documents();
        
        // Inisialisasi variabel
        $totalStudentInAMonth = 0;

        // Get the current month and year in the UTC timezone
        $currentMonthYear = date('Y-m', strtotime('now'));

        // Inisialisasi array untuk menyimpan total keterangan per field "name"
        $totals = [];
        $data = [];

        foreach ($documents as $doc) {
            $documentData = $doc->data();
            $keterangan = $documentData['keterangan'] ?? null;
            $timestamps = $documentData['timestamps'] ?? null;
            $name = $documentData['name'] ?? null;

            // Check if the data is recorded in the current month
            $recordedMonthYear = date('Y-m', strtotime($timestamps));
            if ($recordedMonthYear === $currentMonthYear) {
                //menghitung students yang tercatatat pada firestore collections students
                $totalStudentInAMonth++;

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

            //menghitung students yang tercatatat pada firestore collections students
            $data[] = [
                'timestamps' => $timestamps
            ];
        }

        //menghitung students yang tercatatat pada firestore collections students
        $totalStudents = count($data);

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

        return view('pages.dashboard', compact('totals', 'totalMasuk', 'totalIzin', 'totalSakit', 'totalStudents', 'totalStudentInAMonth'));

         // FirebaseAuth.getInstance().getCurrentUser();
         try {
            $uid = Session::get('uid');
            $user = app('firebase.auth')->getUser($uid);
            return view('pages.dashboard', compact('totalStudents', 'totalStudentInAMonth'));
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function exportExcel()
    {
        return Excel::download(new RekapExport(), 'rekap_kehadiran.xlsx');
    }
}