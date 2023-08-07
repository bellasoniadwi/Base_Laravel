<?php

namespace App\Http\Controllers;

use Google\Cloud\Firestore\FirestoreClient;
use Maatwebsite\Excel\Facades\Excel;
use Kreait\Firebase\Contract\Firestore;
use App\Exports\StudentsExport;
use DateTime;
use Google\Cloud\Core\Timestamp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Laravel\Firebase\Facades\Firebase;

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
        } elseif ($role_akun == 'Pelatih') {
            $query = $collectionReference->where('pelatih', '=', $nama_akun)->orderBy('name', 'asc');
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

    public function create_form() {
        return view('pages.student_form');
    }

    public function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'string', 'max:255'],
            'angkatan' => ['required', 'string', 'max:255'],
            'keterangan' => ['required', 'string', 'max:255'],
        ]);
    }

    public function create(Request $request) {
        try {
            $user = auth()->user();
    
            if ($user) {
                $id = $user->localId;
                $firestore = app('firebase.firestore');
                $database = $firestore->database();
    
                $userDocRef = $database->collection('users')->document($id);
                $userSnapshot = $userDocRef->snapshot();
    
                if ($userSnapshot->exists()) {
                    $name = $userSnapshot->data()['name'];
                } else {
                    $name = "Tidak Dikenali";
                }
            } else {
                $name = "Tidak Dikenali";
            }
    
            $this->validator($request->all())->validate();
    
            // Handle image upload and store its path in Firebase Storage
            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');

                $storage = Firebase::storage();
                $extension = $imageFile->getClientOriginalExtension();
                $storagePath = 'images/' . uniqid() . '_' . now()->format('Y-m-d_H-i-s') . '.' . $extension;

                $storage->getBucket()->upload(
                    file_get_contents($imageFile->getRealPath()),
                    ['name' => $storagePath]
                );

                $imagePath = $storage->getBucket()->object($storagePath)->signedUrl(now()->addHour()); // Generate a signed URL for public access
            } else {
                $imagePath = null; // If no image is uploaded, set the image path to null
            }
    
            $firestore = app(Firestore::class);
            $userRef = $firestore->database()->collection('students');
            $tanggal = new Timestamp(new DateTime());

            $userRef->add([
                'name' => $request->input('name'),
                'nim' => $request->input('nim'),
                'angkatan' => $request->input('angkatan'),
                'keterangan' => $request->input('keterangan'),
                'pelatih' => $name,
                'timestamps' => $tanggal,
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'image' => $imagePath,
            ]);
    
            return redirect()->route('siswa');
        } catch (FirebaseException $e) {
            Session::flash('error', $e->getMessage());
            return back()->withInput();
        }
    }
    

    public function exportExcel()
    {
        return Excel::download(new StudentsExport(), 'bukti_kehadiran.xlsx');
    }
}
