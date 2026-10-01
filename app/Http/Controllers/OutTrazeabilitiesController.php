<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Varity;
use App\Traceability;
use App\Out_trazeabilities;
use App\Certifies;

class OutTrazeabilitiesController extends Controller
{
   public function index()
    {
        $rows = Out_trazeabilities::with('varities')->orderBy('id','desc')->get();

        return view('outtraceability.index', compact('rows'));
    }

    public function create()
    {
        #$varity = Varity::where('name', '!=', '')
        #        ->orWhere('code', '!=', '')
        #        ->pluck('name', 'id');
        $varity = \App\Models\Varity::where('name', '!=', '')->orderBy('name')->get();

        return view('outtraceability.create', compact('varity'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'agreement_year' => 'required|integer|min:1900|max:2100',
            'country'        => 'required|string|max:100',
            'client'         => 'required|string|max:150',
            'varities'       => 'required|array|min:1',
            'varities.*'     => 'integer',
            'import_permit'  => 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png,docx,doc',
        ]);

        // subir archivo igual que antes...
        $publicPath = null;
        if ($request->hasFile('import_permit')) {
            $file = $request->file('import_permit');
            $dir = public_path('uploads/out_trazeabilities/import_permits');
            if (!is_dir($dir)) @mkdir($dir, 0775, true);

            $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move($dir, $filename);
            $publicPath = 'uploads/out_trazeabilities/import_permits/'.$filename;
        }

        $out = \App\Out_trazeabilities::create([
            'agreement_year' => $request->agreement_year,
            'country'        => $request->country,
            'client'         => $request->client,
            'import_permit'  => $publicPath,
        ]);

        $out->varities()->sync($request->varities);

        return back()->with('success', 'Trazabilidad exterior creada correctamente.');
    }

    public function edit($id)
    {
        $row = \App\Out_trazeabilities::with('varities')->findOrFail($id);
        $varity = \App\Models\Varity::where('name', '!=', '')->orderBy('name')->get();

        // ids seleccionados
        $selected = $row->varities->pluck('id')->toArray();

        return view('outtraceability.edit', compact('row','varity','selected'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'agreement_year' => 'required|integer|min:1900|max:2100',
            'country'        => 'required|string|max:100',
            'client'         => 'required|string|max:150',
            'varities'       => 'required|array|min:1',
            'varities.*'     => 'integer',
            'import_permit'  => 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png,docx,doc',
        ]);

        $row = \App\Out_trazeabilities::findOrFail($id);

        $data = [
            'agreement_year' => $request->agreement_year,
            'country'        => $request->country,
            'client'         => $request->client,
        ];

        if ($request->hasFile('import_permit')) {
            $file = $request->file('import_permit');
            $dir = public_path('uploads/out_trazeabilities/import_permits');
            if (!is_dir($dir)) @mkdir($dir, 0775, true);

            $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move($dir, $filename);
            $data['import_permit'] = 'uploads/out_trazeabilities/import_permits/'.$filename;
        }

        $row->update($data);

        $row->varities()->sync($request->varities);

        return back()->with('success', 'Trazabilidad exterior actualizada correctamente.');
    }

    public function destroy($id)
    {
        $row = Out_trazeabilities::findOrFail($id);

        // (Opcional) borrar el archivo físico
        // if (!empty($row->import_permit)) {
        //     $path = public_path($row->import_permit);
        //     if (file_exists($path)) @unlink($path);
        // }

        $row->delete();

        return back()->with('success', 'Trazabilidad (externa) eliminada correctamente.');
    }
}