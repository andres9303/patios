<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\PersonRequest;
use App\Models\Master\Person;
use Illuminate\Support\Facades\DB;

class PersonController extends Controller
{
    public function index()
    {
        return view('master.person.index');
    }

    public function create()
    {
        return view('master.person.create');
    }

    public function store(PersonRequest $request)
    {
        DB::beginTransaction();
        try {
            $state = $request->state ?? 0;
            $isClient = $request->isClient ?? 0;
            $isSupplier = $request->isSupplier ?? 0;
            $isOperator = $request->isOperator ?? 0;
            $isEmployee = $request->isEmployee ?? 0;

            $person = Person::create([
                'identification' => $request->identification,
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'whatsapp' => $request->whatsapp,
                'telegram' => $request->telegram,
                'text' => $request->text,
                'birth' => $request->birth,
                'isClient' => $isClient,
                'isSupplier' => $isSupplier,
                'isOperator' => $isOperator,
                'isEmployee' => $isEmployee,
                'state' => $state,
            ]);

            $person->companies()->sync($request->companies);

            DB::commit();
            return redirect()->route('person.index')->with('success', 'Se ha registrado el tercero correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(Person $person)
    {
        return view('master.person.edit', compact('person'));
    }

    public function update(PersonRequest $request, Person $person)
    {
        DB::beginTransaction();
        try {
            $state = $request->state ?? 0;
            $isClient = $request->isClient ?? 0;
            $isSupplier = $request->isSupplier ?? 0;
            $isOperator = $request->isOperator ?? 0;
            $isEmployee = $request->isEmployee ?? 0;

            $person->update([
                'identification' => $request->identification,
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'whatsapp' => $request->whatsapp,
                'telegram' => $request->telegram,
                'text' => $request->text,
                'birth' => $request->birth,
                'isClient' => $isClient,
                'isSupplier' => $isSupplier,
                'isOperator' => $isOperator,
                'isEmployee' => $isEmployee,
                'state' => $state,
            ]);

            $person->companies()->sync($request->companies);

            DB::commit();
            return redirect()->route('person.index')->with('success', 'Se ha actualizado el tercero correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function destroy(Person $person)
    {
        $person->state = 0;
        $person->save();

        return redirect()->route('person.index')->with('success', 'Se ha eliminado el tercero correctamente.');
    }
}
