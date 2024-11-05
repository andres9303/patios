<div class="text-gray-500">    
    <div class="mt-4">
        {!! html()->label('Código de la variable', 'cod') !!}
        {!! html()->text('cod', isset($variable) ? $variable->cod : null)
            ->class('block mt-1 w-full' . ($errors->has('cod') ? ' is-invalid' : ''))
            ->placeholder('Código de la variable') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Nombre de la variable', 'name') !!}
        {!! html()->text('name', isset($variable) ? $variable->name : null)
            ->class('block mt-1 w-full' . ($errors->has('name') ? ' is-invalid' : ''))
            ->placeholder('Nombre de la variable') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Detalle de la variable', 'text') !!}
        {!! html()->text('text', isset($variable) ? $variable->text : null)
            ->class('block mt-1 w-full' . ($errors->has('text') ? ' is-invalid' : ''))
            ->placeholder('Detalle de la variable') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Concepto', 'concept') !!}
        {!! html()->number('concept', isset($variable) ? $variable->concept : null)
            ->class('block mt-1 w-full' . ($errors->has('concept') ? ' is-invalid' : ''))
            ->placeholder('Concepto de la variable') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Factor', 'value') !!}
        {!! html()->number('value', isset($variable) ? $variable->value : null)
            ->class('block mt-1 w-full' . ($errors->has('value') ? ' is-invalid' : ''))
            ->placeholder('Factor de la variable')
            ->attribute('step', '0.0001') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Variable Base', 'variable_id') !!}
        {!! html()->select('variable_id', $variables->pluck('name', 'id'), isset($variable) ? $variable->variable_id : null)
            ->class('block mt-1 w-full') !!}
    </div>
</div>