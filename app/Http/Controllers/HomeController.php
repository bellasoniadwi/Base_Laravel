<?php

namespace App\Http\Controllers;

use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function ceksaya()
    {
        $user = auth()->user(); // Ubah sesuai dengan cara Anda mendapatkan informasi akun

        if ($user) {
            $id = $user->localId; // Ubah sesuai dengan atribut nama akun pada objek $user
            $email = $user->email;

            // Mengakses Firestore
            $firestore = app('firebase.firestore');
            $database = $firestore->database();

            // Mendapatkan reference ke collection "users" dan document dengan id yang sesuai
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