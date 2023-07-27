<?php

namespace App\Http\Controllers;

use Google\Api\Service;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use \Kreait\Firebase\Database;
use Google\Cloud\Firestore\FirestoreClient;


class FirebaseController extends Controller
{
    public function index()
    {
        $firestore = new FirestoreClient([
            'projectId' => 'project-sinarindo',
        ]);
        $collectionReference = $firestore->collection('students');

        $documentReference = $collectionReference->document('UX5NBN9UZQrSBawArG5F');
        $snapshot = $documentReference->snapshot();


         // Get the data from the snapshot
         $data = $snapshot->data();

         // If you want to print the data as an array, you can use print_r or var_dump
         print_r($data);
 
         // Alternatively, if you want to convert the data to JSON and print it
         $jsonData = json_encode($data);
         echo $jsonData;

    }
}