<?php

namespace App\Http\Controllers;

use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Support\HtmlString;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\Writer\ValidationException;
use Nette\Utils\Strings;

class FirebaseController extends Controller
{
    public function index()
    {
        
        $firestore = new FirestoreClient([
            'projectId' => 'project-sinarindo',
        ]);
        $collectionReference = $firestore->collection('students');

        // Mengambil semua data pada collection
        $query = $collectionReference->orderBy('timestamps', 'desc');
        $documents = $collectionReference->documents();

        // Initialize an empty array to hold the data
        $data = [];

        // Loop through each document to extract the data
        foreach ($documents as $doc) {

            
            // ambil semua data
            $documentData = $doc->data();
            $documentId = $doc->id();

            /// ambil value dari firestore
            $name = $documentData['name'] ?? null;
            $nim = $documentData['nim'] ?? null;
            $angkatan = $documentData['angkatan'] ?? null;
            $timestamps = $documentData['timestamps'] ?? null;
            $image = $documentData['image'] ?? null;
            $latitude = $documentData['latitude'] ?? null;
            $longitude = $documentData['longitude'] ?? null;
            $qrCodeUrl = $this->generateQrCode($documentId);
            

            // generate url langsung dari lat, lang
            $googleMapsUrl = sprintf('https://www.google.com/maps?q=%f,%f', $latitude, $longitude);


            // Generate QR code untuk setiap document ID dan tambahkan ke dalam array data
            
            


            // Add the extracted data to the $data array
            $data[] = [
                'name' => $name,
                'nim' => $nim,
                'angkatan' => $angkatan,
                'timestamps' => $timestamps,
                'image' => $image,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'googleMapsUrl' => $googleMapsUrl,
                'qrCodeUrl' => $qrCodeUrl, // Tambahkan URL QR code ke dalam data
                'id'=>$documentId
            ];

        }
        
        // Pass the data to the view
        return view('pages.students', compact('data'));
    }

    public function generateQrCode(String $documentId)
    {
        // Menggunakan document ID sebagai data yang akan di-generate ke dalam QR code


        $writer = new PngWriter();
        $data=$documentId;
        $qrCode = QrCode::create($data)->setEncoding(new Encoding('UTF-8'))->setForegroundColor(new Color(0, 0, 0))
        ->setBackgroundColor(new Color(255, 255, 255));

        $result = $writer->write($qrCode);

        header('Content-Type: '.$result->getMimeType());
        $result->getString();
        

        // $writer->validateResult($result, $documentId);

        // Simpan gambar QR code ke dalam storage, misalnya folder "qrcodes" dalam storage/app
        // $filename = $documentId . '.png';
        // Storage::disk('local')->put('qrcodes/' . $filename, $qrCode);
        return view('pages.students', compact('result'));

    }
}
