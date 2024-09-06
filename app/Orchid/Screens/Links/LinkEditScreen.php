<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2024.
 */

namespace App\Orchid\Screens\Links;

use App\Models\Link;
use App\Models\LinkCategory;
use App\Orchid\Helpers\ButtonDelete;
use App\Orchid\Helpers\ButtonSave;
use App\Orchid\Helpers\LinkBack;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\ViewField;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class LinkEditScreen extends Screen
{
    protected Link $link;

    public function query(Link $link): iterable
    {
        $link->load('categories');
        $this->link = $link;

        return [
            'link' => $link,
        ];
    }

    public function name(): ?string
    {
        return $this->link->title ?? 'Новая ссылка';
    }


    public function description(): ?string
    {
        return $this->link->exists
            ? 'Редактирование ссылки'." (ID={$this->link->id})"
            : null;
    }

    public function commandBar(): iterable
    {
        return [
            LinkBack::make()->href(route('platform.link.list')),
            ButtonSave::make(),
            ButtonDelete::make()->confirm('Вы хотите удалить ссылку?')->canSee($this->link->exists),
        ];
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function layout(): iterable
    {
        return [
            Layout::split([
                Layout::rows([
                    Input::make('link.title')->title('Заголовок')->required(),
                    Input::make('link.url')->title('URL')->required(),

                    Relation::make('link.categories.')->title('Категории для статьи')
                        ->required()
                        ->fromModel(LinkCategory::class, 'title')
                        ->multiple()
                        ->chunk(50),

                    Input::make('new_cover')->title('Логотип')->type('file'),
                    ViewField::make('link.cover')->title('Ссылка')
                        ->view('orchid.fields.image')
                        ->height(100)
                        ->color($this->link->background),

                    Group::make([
                        Input::make('link.background')->title('Цвет подложки')->type('color')->horizontal(),
                        CheckBox::make('dont_use_background')->placeholder('Не использовать цвет')
                            ->value($this->link->background === null),
                    ]),
                ]),
                Layout::rows([
                    Switcher::make('link.published')->placeholder('Опубликовать')->sendTrueOrFalse(),
                ]),
            ])->ratio('60/40'),
        ];
    }

    public function save(Link $link, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'link.title'        => 'required|string',
            'link.url'          => 'required|string',
            'link.categories'   => 'required|array',
            'link.categories.*' => 'required|int|exists:link_categories,id',
            'link.cover'        => 'nullable|string',
            'link.background'   => 'nullable|string',
            'link.published'    => 'required|bool',
        ]);

        if ($request->has('dont_use_background')) {
            $validated['link']['background'] = null;
        }

        $link->fill($validated['link'])->save();

        $cats = $request->input('link.categories') ?? [];
        $link->categories()->sync($cats);

        if ($request->hasFile('new_cover')) {
            /** @var \Illuminate\Http\UploadedFile $file */
            $file = $request->file('new_cover');

            $storedFilename = $file->storeAs(
                path: 'links/'.substr(md5(date('Y/m')), 0, 8),
                name: substr(md5_file($file->getRealPath()), 0, 8).'.'.$file->getClientOriginalExtension(),
                options: ['disk' => 'public'],
            );

            $link->update(['cover' => Storage::url($storedFilename)]);
        }

        Toast::success('Сохранено')->delay(3000);

        return redirect()->route('platform.link.edit', $link);
    }

    public function delete(Link $link): RedirectResponse
    {
        $title = $link->title;

        if ($link->delete()) {
            Toast::success(__('Ссылка «:title» удалена', ['title' => $title]))->delay(3000);

            return redirect()->route('platform.link.list');
        }

        Toast::error('Не удалось удалить ссылку')->disableAutoHide();

        return redirect()->route('platform.link.edit', $link);
    }
}
