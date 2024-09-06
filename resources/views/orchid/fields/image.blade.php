<div class="form-group preview-image">
    <label class="form-label">{{ $title }}</label>
    <input class="form-control" name="{{ $name }}" value="{{ $value }}">
    <div class="preview-image__image mt-1" style="background-color: {{ $color ?? 'transparent' }}">
        <img src="{{ $value ?: '/static/no-image.png' }}"
            alt="" {!! ($height ?? null) ? 'style="max-height:' . $height . 'px"': '' !!}>
    </div>
</div>
