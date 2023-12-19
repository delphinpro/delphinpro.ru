@component($typeForm, get_defined_vars())
    <div
        id="tinymce-wrapper-{{ $id }}"
        data-controller="tinymce"
        data-turbo-temporary
    >
        <textarea {{ $attributes }}>{{ $value ?? '' }}</textarea>
    </div>
@endcomponent
