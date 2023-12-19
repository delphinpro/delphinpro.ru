<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2020-2023.
 */

namespace App\Orchid\Fields;

use Orchid\Screen\Field;

/**
 * Class TinyMCE
 * @method TinyMCE name(string $value = null)
 * @method TinyMCE value($value = true)
 * @method TinyMCE help(string $value = null)
 * @method TinyMCE title(string $value = null)
 */
class TinyMCE extends Field
{
    protected $view = 'orchid.fields.tinymce';

    /**
     * All attributes that are available to the field.
     */
    protected $attributes = [
        'value'               => null,
        'class'               => 'textarea-tinymce',
        'data-tinymce-target' => 'textarea',
    ];

    /**
     * Attributes available for a particular tag.
     */
    protected $inlineAttributes = [
        'accesskey',
        'autofocus',
        'cols',
        'disabled',
        'form',
        'maxlength',
        'name',
        'placeholder',
        'readonly',
        'required',
        'rows',
        'tabindex',
        'wrap',
    ];

    public function savingUrl(string $url): self
    {
        $this->set('data-saving-url', $url);

        return $this;
    }
}
