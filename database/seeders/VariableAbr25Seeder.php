<?php

namespace Database\Seeders;

use App\Models\Config\Variable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VariableAbr25Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vrbEvt = Variable::create([
            'cod' => 'EVNT',
            'name' => 'Evento reservados al sistema',
            'text' => 'ID del eventos reservados al sistema',
            'concept' => 1,
            'variable_id' => null,
        ]);

        $variables = [
            ['cod' => 'EVNT_InPj', 'name' => 'Evento Inicio Proyecto', 'text' => 'ID del evento de inicio de proyecto', 'concept' => 317, 'variable_id' => $vrbEvt->id],
            ['cod' => 'EVNT_AnPj', 'name' => 'Evento Anula Proyecto', 'text' => 'ID del evento de anulación de proyecto', 'concept' => 318, 'variable_id' => $vrbEvt->id],
            ['cod' => 'EVNT_FiPj', 'name' => 'Evento Fin Proyecto', 'text' => 'ID del evento de fin de proyecto', 'concept' => 319, 'variable_id' => $vrbEvt->id],
            ['cod' => 'EVNT_Cost', 'name' => 'Evento Costo', 'text' => 'ID del evento de costo', 'concept' => 320, 'variable_id' => $vrbEvt->id],
            ['cod' => 'EVNT_Ingr', 'name' => 'Evento Ingreso', 'text' => 'ID del evento de ingreso', 'concept' => 321, 'variable_id' => $vrbEvt->id],
            ['cod' => 'EVNT_ChkL', 'name' => 'Evento CheckList', 'text' => 'ID del evento de checklist', 'concept' => 322, 'variable_id' => $vrbEvt->id],
            ['cod' => 'PrgDP', 'name' => 'Días Programación Proximo', 'text' => 'Número de días de programación proximos a vencer', 'concept' => 25, 'variable_id' => null],
        ];

        DB::table('variables')->insert($variables);
    }
}
