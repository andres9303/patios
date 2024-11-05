<div class="text-gray-500">
    <div class="mt-4">
        {!! html()->label('Código del formulario', 'code') !!}
        {!! html()->text('code', $menu->code ?? '')
            ->class('block mt-1 w-full' . ($errors->has('code') ? ' is-invalid' : ''))
            ->placeholder('Código del formulario, máximo 4 caractéres') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Nombre del formulario', 'text') !!}
        {!! html()->text('name', $menu->name ?? '')
            ->class('block mt-1 w-full' . ($errors->has('name') ? ' is-invalid' : ''))
            ->placeholder('Nombre del formulario') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Ruta del formulario', 'route') !!}
        {!! html()->text('route', $menu->route ?? '')
            ->class('block mt-1 w-full' . ($errors->has('route') ? ' is-invalid' : ''))
            ->placeholder('Ruta del formulario') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Menú activo del formulario', 'active') !!}
        {!! html()->text('active', $menu->active ?? '')
            ->class('block mt-1 w-full' . ($errors->has('active') ? ' is-invalid' : ''))
            ->placeholder('Menú activo del formulario') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Icono del formulario', 'icon') !!}
        {!! html()->text('icon', $menu->icon ?? '')
            ->class('block mt-1 w-full' . ($errors->has('icon') ? ' is-invalid' : ''))
            ->placeholder('Icono del formulario') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Menú padre', 'menu_id') !!}
        {!! html()->select('menu_id', $menus->pluck('name', 'id'), $menu->menu_id ?? null)
            ->class('block mt-1 w-full') !!}
    </div>
</div>

