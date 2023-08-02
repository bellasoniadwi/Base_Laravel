<?php

namespace App\Http\Controllers;

// use Illuminate\Support\Facades\Validator;
use Google\Cloud\Firestore\FirestoreClient;
use Kreait\Firebase\Contract\Firestore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Exception\FirebaseException;

// use Illuminate\Http\Request;
// use Kreait\Firebase\Exception\FirebaseException;
// use Illuminate\Support\Facades\Session;
// use Kreait\Firebase\Auth;

class UserController extends Controller
{
    public function index()
    {
        $firestore = new FirestoreClient([
            'projectId' => 'project-sinarindo',
        ]);

        $collectionReference = $firestore->collection('users');
        $documents = $collectionReference->documents();

        $data = [];

        foreach ($documents as $doc) {

            $documentData = $doc->data();
            $documentId = $doc->id();

            $name = $documentData['name'] ?? null;
            $email = $documentData['email'] ?? null;
            $role = $documentData['role'] ?? null;

            $data[] = [
                'name' => $name,
                'email' => $email,
                'role' => $role,
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
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string', 'max:255'],
        ]);
    }

    public function create(Request $request) {
        try {
            $this->validator($request->all())->validate();
            $userProperties = [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'name' => $request->input('name'),
                'role' => $request->input('role'), 
            ];
  
            $createdUser = $this->auth->createUser($userProperties);

            $firestore = app(Firestore::class);
            $userRef = $firestore->database()->collection('users')->document($createdUser->uid);
            $userRef->set([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'role' => $request->input('role'),
            ]);

            return redirect()->route('user.index');
        } catch (FirebaseException $e) {
            Session::flash('error', $e->getMessage());
            return back()->withInput();
        }
    }
}
