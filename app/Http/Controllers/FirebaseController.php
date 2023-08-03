<?php

namespace App\Http\Controllers;

use Google\Cloud\Firestore\FirestoreClient;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;

class FirebaseController extends Controller
{
    public function index()
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
        $data = [];

        if ($role_akun == 'Superadmin') {
            $query = $collectionReference->orderBy('name');
        } elseif ($role_akun == 'Admin') {
            $query = $collectionReference->where('pelatih', '=', $nama_akun);
        } else {
            $query = $collectionReference->orderBy('name');
        }

        $documents = $query->documents();

            foreach ($documents as $doc) {

                $documentData = $doc->data();
                $documentId = $doc->id();

                $name = $documentData['name'] ?? null;
                $nim = $documentData['nim'] ?? null;
                $angkatan = $documentData['angkatan'] ?? null;
                $timestamps = $documentData['timestamps'] ?? null;
                $image = $documentData['image'] ?? null;
                $latitude = $documentData['latitude'] ?? null;
                $longitude = $documentData['longitude'] ?? null;
                $googleMapsUrl = sprintf('https://www.google.com/maps?q=%f,%f', $latitude, $longitude);

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
        return view('pages.students', compact('data'));
    }

   

    public function exportExcel()
    {
        return Excel::download(new StudentsExport(), 'students.xlsx');
    }
}
