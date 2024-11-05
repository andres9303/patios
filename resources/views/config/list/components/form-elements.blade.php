<div class="text-gray-500">
    <div class="mt-4">
        {!! html()->label('Nombre del item', 'name') !!}
        {!! html()->text('name', $list->name ?? '')->class('block mt-1 w-full'.($errors->has('name') ? ' is-invalid' : ''))->placeholder('Nombre del item') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Texto', 'text') !!}
        {!! html()->text('text', $list->text ?? '')->class('block mt-1 w-full'.($errors->has('text') ? ' is-invalid' : ''))->placeholder('Texto del item') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Orden', 'order') !!}
        {!! html()->number('order', $list->order ?? '')->class('block mt-1 w-full'.($errors->has('order') ? ' is-invalid' : ''))->placeholder('Orden del item') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Factor', 'factor') !!}
        {!! html()->number('factor', $list->factor ?? '')->class('block mt-1 w-full'.($errors->has('factor') ? ' is-invalid' : ''))->placeholder('Factor del item')->attribute('step', '0.01') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Lista', 'catalog_id') !!}
        {!! html()->select('catalog_id', $catalogs->pluck('name', 'id'), $list->catalog_id ?? '')->class('block mt-1 w-full') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Item base', 'item_id') !!}
        {!! html()->select('item_id', $items->pluck('name', 'id'), $list->item_id ?? '')->class('block mt-1 w-full') !!}
    </div>
</div>
