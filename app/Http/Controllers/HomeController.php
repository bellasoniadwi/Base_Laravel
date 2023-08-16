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
        $dataUser = [];

        if ($role_akun == 'Superadmin') {
            $query = $collectionReference->orderBy('name');
        } elseif ($role_akun == 'Instruktur') {
            $query = $collectionReference->where('instruktur', '=', $nama_akun)->orderBy('name', 'asc');
        } else {
            $query = $collectionReference->orderBy('name');
        }

        

        $documents = $query->documents();

        
        

        // Get the current month and year in the UTC timezone
        $currentMonthYear = date('Y-m', strtotime('now'));
        $currentMonthYearNow = date('M Y', strtotime('now'));

        // Hitung jumlah hari dalam bulan ini (dengan mengabaikan hari Sabtu dan Minggu)
        $currentYear = date('Y');
        $currentMonth = date('m');
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
        $activeDaysInMonth = 0;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDayOfWeek = date('N', strtotime("$currentYear-$currentMonth-$day"));
            if ($currentDayOfWeek >= 1 && $currentDayOfWeek <= 5) { // Hari Senin-Jumat
                $activeDaysInMonth++;
            }
        }

        // Inisialisasi array untuk menyimpan total keterangan per field "name"
        $totalKeteranganPerName = [];

        
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
                    if (!isset($totalKeteranganPerName[$name])) {
                        $totalKeteranganPerName[$name] = 0;
                    }
                    $totalKeteranganPerName[$name]++;
                } elseif ($keterangan === "Izin") {
                    $totals[$name]['izin']++;
                    if (!isset($totalKeteranganPerName[$name])) {
                        $totalKeteranganPerName[$name] = 0;
                    }
                    $totalKeteranganPerName[$name]++;
                } elseif ($keterangan === "Sakit") {
                    $totals[$name]['sakit']++;
                    if (!isset($totalKeteranganPerName[$name])) {
                        $totalKeteranganPerName[$name] = 0;
                    }
                    $totalKeteranganPerName[$name]++;
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

        $totalWithoutKeteranganPerName = [];
        // Menghitung total tanpa keterangan per nama
        foreach ($totals as $name => $nameTotal) {
            $totalWithoutKeteranganPerName[$name] = $activeDaysInMonth - $totalKeteranganPerName[$name];
        }


        // Menghitung jumlah total tanpa keterangan
        $totalWithoutKeterangan = $activeDaysInMonth - ($totalMasuk + $totalIzin + $totalSakit);

        // Inisialisasi variabel
        $totalStudentInAMonth = 0;

        //Start function untuk menampilkan Akun Siswa Tercatat di dashboard
        $collectionReferenceUser = $firestore->collection('users');
        $userDocuments = $collectionReferenceUser->documents();
        
        if ($role_akun == 'Superadmin') {
            $queryUser = $collectionReferenceUser->orderBy('name');
        } elseif ($role_akun == 'Instruktur') {
            $queryUser = $collectionReferenceUser->where('didaftarkan_oleh', '=', $nama_akun)->orderBy('name', 'asc');
        } else {
            $queryUser = $collectionReferenceUser->orderBy('name');
        }

        $documentsUser = $queryUser->documents();

        foreach ($documentsUser as $docUser) {
                //menghitung students yang tercatatat pada firestore collections users
                $totalStudentInAMonth++;

            $documentDataUser = $docUser->data();
            $name = $documentDataUser['name'] ?? null;

            //menghitung students yang tercatatat pada firestore collections users
            $dataUser[] = [
                'name' => $name
            ];
        }

        //menghitung students yang tercatatat pada firestore collections users
        $totalStudents = count($dataUser);
        //End function untuk menampilkan Akun Siswa Tercatat di dashboard

        return view('pages.dashboard', compact('totals', 'totalMasuk', 'totalIzin', 'totalSakit', 'totalStudents', 'totalStudentInAMonth', 'currentMonthYearNow', 'totalWithoutKeteranganPerName'));

    }

    //export Rekap jumlah siswa serta keterangannya pada dashboard
    public function exportExcel()
    {
        return Excel::download(new RekapExport(), 'rekap_siswa.xlsx');
    }

    //export Rekap Kehadiran pada dashboard
    public function exportExcelkehadiran()
    {
        return Excel::download(new KehadiranExport(), 'rekap_kehadiran.xlsx');
    }

    public function notauthorize() {
        return view('newlayout.authorization');
    }
}