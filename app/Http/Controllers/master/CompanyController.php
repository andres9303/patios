<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\CompanyRequest;
use App\Models\Master\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function index()
    {
        return view('master.company.index');
    }

    public function create()
    {
        return view('master.company.create');
    }
    
    public function store(CompanyRequest $request)
    {
        DB::beginTransaction();
        try {
            $state = $request->state ?? 0;
            $prefix = $request->prefix ?? 'FV';

            Company::create([
                'user_id' => auth()->user()->id,
                'name' => $request->name,
                'prefix' => $prefix,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'head1' => $request->head1,
                'head2' => $request->head2,
                'head3' => $request->head3,
                'foot1' => $request->foot1,
                'foot2' => $request->foot2,
                'foot3' => $request->foot3,
                'state' => $state,
            ]);

            DB::commit();
            return redirect()->route('company.index')->with('success', 'Se ha registrado el Centro de costos correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(Company $company)
    {
        return view('master.company.edit', compact('company'));
    }

    public function update(CompanyRequest $request, Company $company)
    {
        DB::beginTransaction();
        try {
            $state = $request->state ?? 0;
            $prefix = $request->prefix ?? 'FV';

            $company->update([
                'name' => $request->name,
                'prefix' => $prefix,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'head1' => $request->head1,
                'head2' => $request->head2,
                'head3' => $request->head3,
                'foot1' => $request->foot1,
                'foot2' => $request->foot2,
                'foot3' => $request->foot3,
                'state' => $state,
            ]);

            DB::commit();
            return redirect()->route('company.index')->with('success', 'Se ha actualizado la información del centro de costos correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function destroy(Company $company)
    {
        $company->state = 0;
        $company->save();

        return redirect()->route('company.index')->with('success', 'Se ha inactivado el centro de costos correctamente.');
    }
}
