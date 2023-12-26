<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Orchid\Screens\Article;

use App\Models\Article;
use App\Models\Tag;
use App\Orchid\Fields\TinyMCE;
use App\Orchid\Helpers\ButtonDelete;
use App\Orchid\Helpers\ButtonSave;
use App\Orchid\Helpers\Display;
use App\Orchid\Helpers\LinkBack;
use App\Orchid\Helpers\LinkPreview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ArticleEditScreen extends Screen
{
    protected Article $article;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Article $article): iterable
    {
        $article->load('tags');
        $this->article = $article;

        return [
            'article' => $article,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->article->title ?? 'Новая публикация';
    }


    public function description(): ?string
    {
        return $this->article->exists
            ? 'Редактирование публикации'." (ID={$this->article->id})"
            : null;
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        $commands = [
            LinkBack::make(),
        ];

        if ($this->article->exists) {
            $commands[] = LinkPreview::make(route('article.show', $this->article));
        }

        $commands[] = ButtonSave::make();
        $commands[] = ButtonDelete::make()->confirm('Вы хотите удалить публикацию?')->canSee($this->article->exists);

        return $commands;
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function layout(): iterable
    {
        $createdAt = $updatedAt = '–';
        if ($this->article->exists) {
            $createdAt = new HtmlString(__(':date <small>:tz</small>', [
                'date' => Display::datetime($this->article->created_at),
                'tz'   => $this->article->created_at->timezone,
            ]));
            $updatedAt = new HtmlString(__(':date <small>:tz</small>', [
                'date' => Display::datetime($this->article->updated_at),
                'tz'   => $this->article->updated_at->timezone,
            ]));
        }

        $tinymceField = TinyMCE::make('article.content')->title('Содержание');
        if ($this->article->exists) {
            $tinymceField = $tinymceField->savingUrl(route('platform.article.saveContent', $this->article));
        }

        return [
            Layout::modal('TAG_MODAL', [
                Layout::rows([
                    Input::make('tag.name')->title('Название тега')->required(),
                    TextArea::make('tag.description')->title('Описание тега'),
                ]),
            ])
                ->title('Добавить новый тег')
                ->applyButton('Сохранить')
                ->closeButton('Отмена'),

            Layout::split([
                Layout::rows([
                    Input::make('article.title')->title('Заголовок')->required(),
                    TextArea::make('article.summary')->title('Краткое вступление для вывода в списках')->rows(4),
                    $tinymceField,

                    Relation::make('article.tags.')->title('Теги для статьи')
                        ->fromModel(Tag::class, 'name')
                        ->multiple()
                        ->chunk(50),
                    ModalToggle::make('Добавить новый тег')
                        ->modal('TAG_MODAL')
                        ->icon('bs.plus-lg')
                        ->type(Color::PRIMARY)
                        ->method('saveTag'),
                ]),
                Layout::rows([
                    Switcher::make('article.published')->placeholder('Опубликовать')->sendTrueOrFalse(),
                    Cropper::make('article.cover_id')->title('Обложка')->targetId()->width(1200)->height(630)->group('article_cover'),
                    Input::make('article.keywords')->title('Ключевые слова'),
                    TextArea::make('article.description')->title('Описание')->rows(4),
                    Label::make('')->title('Дата и время создания')->value($createdAt)->canSee($this->article->exists),
                    CheckBox::make('update_time')->placeholder('Обновить время создания')->value(false)->canSee($this->article->exists),
                    Label::make('')->title('Дата и время обновления')->value($updatedAt)->canSee($this->article->exists),
                    Label::make('')->title('Пользователь')->value($this->article->user?->name.' ('.$this->article->user_id.')')->canSee($this->article->exists),
                ]),
            ])->ratio('60/40'),
        ];
    }

    public function save(Article $article, Request $request): RedirectResponse
    {
        $newArticle = !$article->exists;

        $validated = $request->validate([
            'article.title'       => 'required|string',
            'article.summary'     => 'nullable|string',
            'article.content'     => 'nullable|string',
            'article.published'   => 'required|bool',
            'article.cover_id'    => 'nullable|int',
            'article.keywords'    => 'nullable|string|max:255',
            'article.description' => 'nullable|string|max:255',
        ]);

        if ($newArticle) {
            $article->user_id = $request->user()->id;
        }

        $article->fill($validated['article'])->save();

        if (!$newArticle && $request->input('update_time')) {
            $article->created_at = now();
            $article->save();
        }

        $tags = $request->input('article.tags') ?? [];
        $article->tags()->sync($tags);

        Toast::success('Сохранено')->delay(3000);

        return redirect()->route('platform.article.edit', $article);
    }

    public function delete(Article $article, Request $request): RedirectResponse
    {
        $title = $article->title;

        if ($article->delete()) {
            $article->update(['published' => false]);
            Toast::success(__('Публикация «:title» перемещена в корзину', ['title' => $title]))->delay(3000);

            return redirect()->route('platform.article.list');
        }

        Toast::error('Не удалось удалить публикацию')->disableAutoHide();

        return redirect()->route('platform.article.edit', $article);
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
}
