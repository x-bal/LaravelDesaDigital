<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Warga;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DevController extends Controller
{
    public function getcetaksurat(Request $request)
    {
        $data = [];
        $resources = Warga::orWhere('nama_warga', 'like', '%' . $request->q . '%')->orWhere('nik', 'like', '%' . $request->q . '%')->get();
        foreach ($resources as $resource) {
            $data[] = ['id' => $resource->id, 'text' => $resource->nama_warga . ' - ' . $resource->nik];
        }
        return response()->json($data);
    }
    public function showcetaksurat($id)
    {
        $resource = Warga::findOrFail($id);
        $response = [
            'resource' => $resource,
            'umur' =>  Carbon::now()->format('Y') - Carbon::parse($resource->tanggal_lahir)->format('Y')
        ];
        return response()->json($response);
    }
    public function getfindfamily($id)
    {
        $warga = Warga::find($id);
        $resource = Warga::where('kk',$warga->kk)->get();
        return response()->json($resource);
    }
    public function dev()
    {
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(public_path('template/template.docx'));

        $templateProcessor->setValues([
            'number' => '212/SKD/VII/2019',
            'name' => 'Alfa',
            'birthplace' => 'Bandung',
            'birthdate' => '4 Mei 1991',
            'gender' => 'Laki-Laki',
            'religion' => 'Islam',
            'address' => 'Jln. ABC no 12',
            'date' => date('Y-m-d'),
        ]);
        $templateProcessor->setImageValue('CompanyLogo', public_path('qsindoflatbaru.jpg'));
        header("Content-Disposition: attachment; filename=template.docx");

        $templateProcessor->saveAs('php://output');
    }
}
