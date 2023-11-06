<?php

namespace App\Http\Controllers;
use Excel;
use App\Imports\StudentImport;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\Student;


use Illuminate\Http\Request;

class ImportController extends Controller
{
    private $file;


    public function index()
    {
        return view('import_form');
    }



    public function processImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx', // Adjust validation rules as needed
        ]);


        $filePath = $request->file('file')->getRealPath();
        $extension = $request->file('file')->getClientOriginalExtension();
        $file = $filePath.'.'.$extension;

        if (in_array($extension, ['xlsx'])) {
            $rows = Excel::toArray([], $filePath, null, \Maatwebsite\Excel\Excel::XLSX);
        } elseif (in_array($extension, ['csv'])) {
            $rows = Excel::toArray([], $filePath, null, \Maatwebsite\Excel\Excel::CSV);
        } else {
            return 'Unsupported file format. Please upload an Excel (xlsx) or CSV file.';
        }

        // Ensure that you have at least one row in the Excel sheet
        if (empty($rows) || empty($rows[0])) {
            return 'No data found in the uploaded file.';
        }

        // $columns will contain the column fields from the first sheet of the Excel file
        $columnFields = array_shift($rows);


        // Now you have the column fields, and you can use them as needed
        $headingRow = array_shift($columnFields);

        $firstColumn = $columnFields[1];

        $tableName = new Student;
        $columnNames = $tableName->getColumnNames();


        // $filePath = $request->file('file')->store('temp');
        return ['status'=> 200,'message'=> 'file uploaded', 'data' => ['headingRow'=>$headingRow,'firstRow'=>$firstColumn,'columnNames'=>$columnNames,'filePath'=>$file] ];
        // return response()->json(['headingRow'=>$headingRow,'firstRow'=>$firstColumn,'columnNames'=>$columnNames]);




        // Excel::import(new StudentImport, $request->file);
        // return  'Excel Data Imported successfully.';


    }


    public function processStore(Request $request){



        $headingRow = $request->input('headingRow');
        $columnNames = explode(',', $headingRow);
        $length = count($columnNames);
        $columnMapping = [];



        for ($i = 0; $i < $length; $i++) {
            // Check if a user-selected column name is provided for this index
            if ($request->has('column'.$i)) {
                // Get the user-selected column name for this index
                $userSelectedColumn = $request->input('column'.$i);

                // Map the user-selected column to the corresponding heading
                $columnMapping[$columnNames[$i]] = $userSelectedColumn;
            }
        }

        // return $columnMapping;
        // Now $columnMapping contains the mapping of user-selected columns to headings

        Excel::import(new StudentImport($columnMapping), $request->filePath);
        return  'Excel Data Imported successfully.';



    }

}
