<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CsvController extends Controller
{
    public function index(){
           $students = Student::paginate(10);
           return view('CsvView',compact('students'));
    }

    public function uploadCsv(Request $request){
        $request->validate(
            [
                 'csv_file' => 'required|mimes:csv|max:102400'
            ]
        );

        $file = $request->file('csv_file');
        $handle = fopen($file->path(), 'r');

        //skip the header row
        fgetcsv($handle);

        $chunksize = 25;
        while(!feof($handle))
        {
            $chunkdata = [];

            for($i = 0; $i<$chunksize; $i++)
            {
                $data = fgetcsv($handle);
                if($data === false)
                {
                    break;
                }
                $chunkdata[] = $data;
            }

           $this->uploadChunkData($chunkdata);
        }

        return redirect('/csvForm');

    }

    private function uploadChunkData($chunkData){
        foreach($chunkData as $column){
            $id = $column[0];
            $name = $column[1]."".$column[2];
            $email = $column[3];
            $dob = Carbon::createFromFormat('m/d/Y', $column[4])->format('Y-m-d');
            $address = $column[5];
            $contact = $column[6];

            Student::create([
                'id' => $id,
                'name' => $name,
                'email' => $email,
                'birthday' => $dob,
                'address' => $address,
                'contact' => $contact
            ]);
        }
    }
}
