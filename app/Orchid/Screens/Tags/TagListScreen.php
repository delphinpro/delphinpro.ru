<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Orchid\Screens\Tags;

use App\Models\Tag;
use App\Orchid\Helpers\ButtonDelete;
use App\Orchid\Helpers\LinkPreview;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class TagListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'entries' => Tag::filters()->defaultSort('name')->paginate(100),
        ];
    }

    public function name(): ?string { return 'Список тегов'; }

    public function commandBar(): iterable
    {
        return [
            LinkPreview::make(route('article.tags')),
            ModalToggle::make('Добавить тег')
                ->modal('TAG_MODAL')
                ->icon('bs.plus-lg')
                ->type(Color::PRIMARY)
                ->method('saveTag')
                ->asyncParameters([
                    'tag' => null,
                ]),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::modal('TAG_MODAL', [
                Layout::rows([
                    Input::make('tag.name')->title('Название тега')->required(),
                    TextArea::make('tag.description')->title('Описание тега'),
                ]),
            ])
                ->title('Добавить новый тег')
                ->applyButton('Сохранить')
                ->closeButton('Отмена')
                ->async('asyncGetTag'),

            Layout::table('entries', [
                TD::make('id')->width(1)->alignRight()
                    ->render(fn(Tag $tag) => "<code class='text-nowrap text-black'>$tag->id</code>"),
                TD::make('name', 'Тег')->sort()
                    ->render(fn(Tag $tag) => ModalToggle::make($tag->name)
                        ->modalTitle('Редактировать тег')
                        ->modal('TAG_MODAL')
                        ->icon('bs.pencil-fill')
                        ->method('updateTag')
                        ->asyncParameters([
                            'id' => $tag->id,
                        ])),
                TD::make('actions', '')->alignRight()->width(400)
                    ->render(fn(Tag $tag) => ButtonDelete::make('Удалить')
                        ->method('deleteTag')
                        ->confirm('Вы уверены, что хотите удалить этот тег?')
                        ->parameters([
                            'id' => $tag->id,
                        ])),
            ]),
        ];
    }

    public function asyncGetTag(Request $request): array
    {
        $tag = Tag::find($request->get('id'));

        return compact('tag');
    }

    public function saveTag(Request $request): void
    {
        $request->validate([
            'tag.name'        => 'required|unique:tags,name',
            'tag.description' => 'nullable',
        ]);
        $input = $request->input('tag');
        $input['name'] = Str::lower($input['name']);
        $tag = Tag::create($input);
        Toast::success('Тег добавлен: '.$tag->name);
    }

    public function updateTag(Request $request): void
    {
        $tag = Tag::find($request->get('id'));
        $input = $request->input('tag');
        $input['name'] = Str::lower($input['name']);
        $tag->update($input);
        Toast::success('Тег сохранён: '.$tag->name);
    }

    public function deleteTag(Request $request): void
    {
        $tag = Tag::findOrFail($request->get('id'));
        $name = $tag->name;
        $tag->delete();
        Toast::success('Тег удалён: '.$name);
    }
}
