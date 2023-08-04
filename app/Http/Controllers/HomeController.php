<?php

namespace App\Http\Controllers;

use App\Exports\KehadiranExport;
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
        $user = auth()->user();

        if ($user) {
            $id = $user->localId;

            $firestore = app('firebase.firestore');
            $database = $firestore->database();

            $userDocRef = $database->collection('users')->document($id);
            $userSnapshot = $userDocRef->snapshot();

            if ($userSnapshot->exists()) {
                $nama_akun = $userSnapshot->data()['name'];
                $role_akun = $userSnapshot->data()['role'];
            } else {
                $nama_akun = "Name not found";
                $role_akun = "Role not found";
            }
        } else {
            $nama_akun = "Name ga kebaca";
            $role_akun = "Role ga kebaca";
        }

        $firestore = new FirestoreClient([
            'projectId' => 'project-sinarindo',
        ]);

        $collectionReference = $firestore->collection('students');
        // Inisialisasi array untuk menyimpan total keterangan per field "name"
        $totals = [];
        $data = [];

        if ($role_akun == 'Superadmin') {
            $query = $collectionReference->orderBy('name');
        } elseif ($role_akun == 'Admin') {
            $query = $collectionReference->where('pelatih', '=', $nama_akun)->orderBy('name', 'asc');
        } else {
            $query = $collectionReference->orderBy('name');
        }

        $documents = $query->documents();

        
        // Inisialisasi variabel
        $totalStudentInAMonth = 0;

        // Get the current month and year in the UTC timezone
        $currentMonthYear = date('Y-m', strtotime('now'));
        $currentMonthYearNow = date('M Y', strtotime('now'));

        

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
        return view('pages.dashboard', compact('totals', 'totalMasuk', 'totalIzin', 'totalSakit', 'totalStudents', 'totalStudentInAMonth', 'currentMonthYearNow'));
    }


    public function exportExcel()
    {
        return Excel::download(new RekapExport(), 'rekap_students.xlsx');
    }

    public function exportExcelkehadiran()
    {
        return Excel::download(new KehadiranExport(), 'rekap_kehadiran.xlsx');
    }

    public function notauthorize() {
        return view('newlayout.authorization');
    }
}