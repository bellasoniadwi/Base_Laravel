<?php

namespace App\Http\Controllers;

use Google\Cloud\Firestore\FirestoreClient;
use Kreait\Firebase\Contract\Firestore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Laravel\Firebase\Facades\Firebase;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
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

        $collectionReference = $firestore->collection('users');
        $data = [];

        if ($role_akun == 'Superadmin') {
            $query = $collectionReference->where('role', '=', 'Instruktur')->orderBy('name');
        } elseif ($role_akun == 'Instruktur') {
            $query = $collectionReference->where('didaftarkan_oleh', '=', $nama_akun)->orderBy('name', 'asc');
        } else {
            $query = $collectionReference->orderBy('name');
        }

        $documents = $query->documents();

        foreach ($documents as $doc) {

            $documentData = $doc->data();
            $documentId = $doc->id();

            $name = $documentData['name'] ?? null;
            $email = $documentData['email'] ?? null;
            $nomor_induk = $documentData['nomor_induk'] ?? null;
            $angkatan = $documentData['angkatan'] ?? null;
            $role = $documentData['role'] ?? null;
            $pendaftar = $documentData['didaftarkan_oleh'] ?? null;
            $image = $documentData['image'] ?? null;

            $data[] = [
                'name' => $name,
                'email' => $email,
                'nomor_induk' => $nomor_induk,
                'angkatan' => $angkatan,
                'role' => $role,
                'pendaftar' => $pendaftar,
                'image' => $image
            ];
        }

        return view('pages.users', compact('data'));
    }


    public function create_form() {
        return view('pages.user_form');
    }

    protected $auth;

    public function __construct(Auth $auth) {
       $this->auth = $auth;
    }

    public function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'nomor_induk' => ['string', 'max:12'],
            'angkatan' => ['string', 'max:4'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string', 'max:255'],
            'image' => ['mimes:png,jpg,jpeg', 'max:2048']
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
                $uniqueId = microtime(true) * 1000;
                $storagePath = 'images/' . $uniqueId . '_' . now()->format('Y-m-d') . '.jpg';

                $storage->getBucket()->upload(
                    file_get_contents($imageFile->getRealPath()),
                    ['name' => $storagePath]
                );

                $imagePath = $storage->getBucket()->object($storagePath)->signedUrl(now()->addHour());
            } else {
                $imagePath = null; // If no image is uploaded, set the image path to null
            }

            $userProperties = [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'name' => $request->input('name'),
                'nomor_induk' => $request->input('nomor_induk'),
                'angkatan' => $request->input('angkatan'),
                'role' => $request->input('role'),
                'didaftarkan_oleh' => $name,
                'image' => $imagePath
            ];
  
            $createdUser = $this->auth->createUser($userProperties);

            $firestore = app(Firestore::class);
            $userRef = $firestore->database()->collection('users')->document($createdUser->uid);
            $userRef->set([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'nomor_induk' => $request->input('nomor_induk'),
                'angkatan' => $request->input('angkatan'),
                'role' => $request->input('role'),
                'didaftarkan_oleh' => $name,
                'image' => $imagePath
            ]);

            Alert::success('Akun baru berhasil ditambahkan');
            return redirect()->route('user.index');
        } catch (FirebaseException $e) {
            Session::flash('error', $e->getMessage());
            return back()->withInput();
        }
    }
}
