<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2024.
 */

namespace App\Orchid\Screens\Links;

use App\Models\LinkCategory;
use App\Orchid\Helpers\ButtonDelete;
use App\Orchid\Helpers\ButtonSave;
use App\Orchid\Helpers\LinkBack;
use App\Orchid\Helpers\LinkPreview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CategoryEditScreen extends Screen
{
    protected LinkCategory $category;

    public function query(LinkCategory $category): iterable
    {
        $category->load('links');
        $this->category = $category;

        return [
            'category' => $category,
        ];
    }

    public function name(): ?string
    {
        return $this->category->title ?? 'Новая категория ссылок';
    }


    public function description(): ?string
    {
        return $this->category->exists
            ? 'Редактирование категории ссылок'." (ID={$this->category->id})"
            : null;
    }

    public function commandBar(): iterable
    {
        return [
            LinkPreview::make(route('link.index')),
            LinkBack::make()->href(route('platform.link-category.list')),
            ButtonSave::make(),
            ButtonDelete::make()->confirm('Вы хотите удалить категорию?')->canSee($this->category->exists),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('category.title')->title('Заголовок')->required(),
            ]),
        ];
    }

    public function save(LinkCategory $category, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category.title' => 'required|string',
        ]);

        $category->fill($validated['category'])->save();

        Toast::success('Сохранено')->delay(3000);

        return redirect()->route('platform.link-category.edit', $category);
    }

    public function delete(LinkCategory $category): RedirectResponse
    {
        $title = $category->title;

        if ($category->delete()) {
            Toast::success(__('Категория «:title» удалена', ['title' => $title]))->delay(3000);

            return redirect()->route('platform.link-category.list');
        }

        Toast::error('Не удалось удалить категорию')->disableAutoHide();

        return redirect()->route('platform.link-category.edit', $category);
    }
}
