<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\PddiktiExport;
use App\Exports\SiakadExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function downloadPddikti()
    {
        return Excel::download(new PddiktiExport, 'Data_Import_PDDIKTI_PPG.xlsx');
    }

    public function downloadSiakad()
    {
        return Excel::download(new SiakadExport, 'Data_Import_SIAKAD_PPG.xls');
    }

    public function downloadImportTemplate()
    {
        return Excel::download(new \App\Exports\LaporDiriTemplateExport, 'Template_Import_Lapor_Diri_PPG.xlsx');
    }
}

