<div class="text-gray-500">
    <div class="mt-4">
        {!! html()->label('Nombre de grupo', 'name') !!}
        {!! html()->text('name', isset($role) ? $role->name : old('name'))
            ->class('block mt-1 w-full' . ($errors->has('name') ? ' is-invalid' : ''))
            ->placeholder('Nombre del grupo') !!}
    </div>
</div>
