<?php use Illuminate\View\ComponentSlot; ?>
@props([
    'heading' => new ComponentSlot(),
    'info' => false,
    'success' => false,
    'warning' => false,
    'danger' => false,
    'dismissible' => false,
])
<?php
if (!($heading instanceof ComponentSlot)) {
    $heading = new ComponentSlot((string)$heading);
}
?>
<div {{ $attributes->class([
  'alert',
  'alert-info' => $info,
  'alert-success' => $success,
  'alert-warning' => $warning,
  'alert-danger' => $danger,
  'alert-dismissible fade show' => $dismissible,
]) }}>
    <div class="alert-content">
        @if($heading->isNotEmpty())
            <p {{ $heading->attributes->class(['alert-heading']) }}>
                {{ $heading }}
            </p>
        @endif

        {{ $slot }}
    </div>
    @if($dismissible)
        <x-btn-close data-bs-dismiss="alert"/>
    @endif
</div>
