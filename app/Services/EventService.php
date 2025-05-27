<?php

namespace App\Services;

use App\Models\Config\Variable;
use App\Models\Space\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventService
{
    public function create(string $type, int $spaceId, $date, $text = null, $location = null, $menu_id = null, $doc_id = null, $mvto_id = null)
    {
        DB::beginTransaction();
        try {
            $var = Variable::where('cod', $type)->first();

            Event::create([
                'title' => $var->name,
                'text' => $text ?? $var->name,
                'company_id' => Auth::user()->current_company_id,
                'item_id' => $var->concept,
                'space_id' => $spaceId,
                'menu_id' => $menu_id,
                'doc_id' => $doc_id,
                'mvto_id' => $mvto_id,
                'date' => $date,
                'location' => $location,
                'state' => 1,
                'user_id' => Auth::id(),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            throw $e;
            DB::rollBack();
        }
    }
}