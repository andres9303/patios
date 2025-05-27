<?php

namespace App\Http\Controllers\space;

use App\Http\Controllers\Controller;
use App\Http\Requests\Space\TemplateRequest;
use App\Models\Master\Space;
use App\Models\Space\Template;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TemplateController extends Controller
{
    public function index()
    {
        return view('space.template.index');
    }

    public function create()
    {
        $spaces = Space::where('company_id', Auth::user()->current_company_id)->get();

        return view('space.template.create', compact('spaces'));
    }

    public function store(TemplateRequest $request)
    {
        DB::beginTransaction();
        try {
            Template::create([
                'name' => $request->name,
                'description' => $request->description,
                'space_id' => $request->space_id,
                'company_id' => Auth::user()->current_company_id,
                'state' => $request->state,
                'user_id' => Auth::id(),
            ]);
            DB::commit();
            return redirect()->route('template.index')->with('success', 'Se ha registrado la plantilla correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$th->getMessage());
        }
    }

    public function show(Template $template)
    {
        $sortedFields = $template->fields()
                                        ->with('typeField')
                                        ->where('state', 1)
                                        ->orderBy('order', 'asc')
                                        ->orderBy('id', 'asc')
                                        ->get();

        return view('space.template.show', compact('template', 'sortedFields'));
    }

    public function edit(Template $template)
    {
        $spaces = Space::where('company_id', Auth::user()->current_company_id)->get();
        
        return view('space.template.edit', compact('template', 'spaces'));
    }

    public function update(TemplateRequest $request, Template $template)
    {
        DB::beginTransaction();
        try {
            $template->update([
                'name' => $request->name,
                'description' => $request->description,
                'space_id' => $request->space_id,
                'state' => $request->state,
                'user_id' => Auth::id(),
            ]);
            DB::commit();
            return redirect()->route('template.index')->with('success', 'Se ha actualizado la plantilla correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$th->getMessage());
        }
    }

    public function destroy(Template $template)
    {
        DB::beginTransaction();
        try {
            $template->update([
                'state' => 0,
            ]);
            DB::commit();
            return redirect()->route('template.index')->with('success', 'Se ha eliminado la plantilla correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$th->getMessage());
        }
    }
}
