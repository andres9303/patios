<?php

namespace App\Http\Controllers\space;

use App\Http\Controllers\Controller;
use App\Http\Requests\Space\ChecklistRequest;
use App\Models\Space\Answer;
use App\Models\Space\CheckList;
use App\Models\Space\Template;
use App\Services\EventService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChecklistController extends Controller
{
    public $menuId = 604;

    public function index()
    {
        $templates = Template::where('company_id', Auth::user()->current_company_id)->where('state', 1)->get();
        return view('space.checklist.index', compact('templates'));
    }

    public function create(Template $template)
    {
        $sortedFields = $template->fields()
                            ->with('typeField')
                            ->where('state', 1)
                            ->orderBy('order', 'asc')
                            ->orderBy('id', 'asc')
                            ->get();

        return view('space.checklist.create', compact('template', 'sortedFields'));
    }

    public function store(ChecklistRequest $request, Template $template, EventService $eventService)
    {
        DB::beginTransaction();
        try {
            $checklist = CheckList::create([
                'template_id' => $template->id,
                'space_id' => $template->space_id, 
                'company_id' => Auth::user()->current_company_id,
                'user_id' => Auth::id(),
                'date' => $request->date,
                'state' => 1, 
            ]);

            $templateFields = $template->fields()->with('typeField')->get()->keyBy('id');
            $submittedAnswers = $request->input('answers', []);
            $submittedDescriptions = $request->input('descriptions', []);

            foreach ($submittedAnswers as $field_id => $value) {
                if (!$templateFields->has($field_id)) {
                    continue;
                }

                $field = $templateFields->get($field_id);
                $answerData = [
                    'check_list_id' => $checklist->id,
                    'field_id' => $field_id,
                    'description' => $submittedDescriptions[$field_id] ?? null,
                ];

                switch (strtolower($field->typeField->name)) {
                    case 'texto':
                        $answerData['value_text'] = $value;
                        break;
                    case 'entero':
                        $answerData['value_number'] = $value;
                        break;
                    case 'decimal':
                        $answerData['value_decimal'] = $value;
                        break;
                    case 'fecha':
                        $answerData['value_date'] = $value;
                        break;
                    case 'hora':
                        $answerData['value_time'] = $value;
                        break;
                    case 'fecha y hora':
                        $answerData['value_datetime'] = $value;
                        break;
                    case 'booleano':
                        $answerData['value_boolean'] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                        break;
                    default:
                        $answerData['value_text'] = $value;
                        break;
                }
                Answer::create($answerData);
            }

            $eventService->create('EVNT_ChkL', $template->space_id, Carbon::now(), 'Registra checklist: '. $template->name, null, $this->menuId, $checklist->id);

            DB::commit();

            return redirect()->route('checklist.index')->with('success', 'Checklist creado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(Template $template, CheckList $checklist)
    {
        $sortedFields = $template->fields()
                            ->with('typeField')
                            ->where('state', 1)
                            ->orderBy('order', 'asc')
                            ->orderBy('id', 'asc')
                            ->get();

        $answers = $checklist->answers()->with('field')->get();

        return view('space.checklist.edit', compact('template', 'checklist', 'sortedFields', 'answers'));
    }

    public function update(ChecklistRequest $request, Template $template, CheckList $checklist, EventService $eventService)
    {
        DB::beginTransaction();
        try {
            $checklist->update([
                'date' => $request->date,
                'state' => 1, 
            ]);

            $templateFields = $template->fields()->with('typeField')->get()->keyBy('id');
            $submittedAnswers = $request->input('answers', []);
            $submittedDescriptions = $request->input('descriptions', []);

            foreach ($submittedAnswers as $field_id => $value) {
                if (!$templateFields->has($field_id)) {
                    continue;
                }

                $field = $templateFields->get($field_id);
                $answerData = [
                    'check_list_id' => $checklist->id,
                    'field_id' => $field_id,
                    'description' => $submittedDescriptions[$field_id] ?? null,
                ];

                switch (strtolower($field->typeField->name)) {
                    case 'texto':
                        $answerData['value_text'] = $value;
                        break;
                    case 'entero':
                        $answerData['value_number'] = $value;
                        break;
                    case 'decimal':
                        $answerData['value_decimal'] = $value;
                        break;
                    case 'fecha':
                        $answerData['value_date'] = $value;
                        break;
                    case 'hora':
                        $answerData['value_time'] = $value;
                        break;
                    case 'fecha y hora':
                        $answerData['value_datetime'] = $value;
                        break;
                    case 'booleano':
                        $answerData['value_boolean'] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                        break;
                    default:
                        $answerData['value_text'] = $value;
                        break;
                }
                Answer::updateOrCreate([
                    'check_list_id' => $checklist->id,
                    'field_id' => $field_id,
                ], $answerData);
            }

            $eventService->create('EVNT_ChkL', $template->space_id, Carbon::now(), 'Modifica checklist: '. $template->name, null, $this->menuId, $checklist->id);

            DB::commit();

            return redirect()->route('checklist.index')->with('success', 'Checklist actualizado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function destroy(Template $template, CheckList $checklist, EventService $eventService)
    {
        DB::beginTransaction();
        try {
            $checklist->update([
                'state' => 0,
            ]);
            
            $eventService->create('EVNT_ChkL', $template->space_id, Carbon::now(), 'Anula checklist: '. $template->name, null, $this->menuId, $checklist->id);

            DB::commit();
            return redirect()->route('checklist.index')->with('success', 'Checklist anulado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
        
    }
}
