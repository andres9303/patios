<div class="text-gray-500">
    <div class="mt-4">
        {!! html()->label('Nombre', 'name')->class('block font-medium text-sm text-gray-700') !!}
        {!! html()->text('name', old('name', isset($user) ? $user->name : null))
            ->class('block mt-1 w-full'.($errors->has('name') ? ' is-invalid' : ''))
            ->placeholder('Nombre del usuario') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Usuario', 'username')->class('block font-medium text-sm text-gray-700') !!}
        {!! html()->text('username', old('username', isset($user) ? $user->username : null))
            ->class('block mt-1 w-full'.($errors->has('username') ? ' is-invalid' : ''))
            ->placeholder('Inicio de sesión') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Correo electrónico', 'email')->class('block font-medium text-sm text-gray-700') !!}
        {!! html()->email('email', old('email', isset($user) ? $user->email : null))
            ->class('block mt-1 w-full'.($errors->has('email') ? ' is-invalid' : ''))
            ->placeholder('usuario@mail.com') !!}
    </div>