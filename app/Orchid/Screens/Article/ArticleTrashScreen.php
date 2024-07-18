<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace App\Orchid\Screens\Article;

use App\Models\Article;
use App\Orchid\Helpers\ButtonDelete;
use App\Orchid\Helpers\ButtonRestore;
use App\Orchid\Helpers\Display;
use App\Orchid\Helpers\LinkBack;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ArticleTrashScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'articles' => Article::onlyTrashed()->filters()->defaultSort('deleted_at', 'desc')->paginate(),
        ];
    }

    public function name(): ?string
    {
        return 'Публикации в корзине';
    }

    public function commandBar(): iterable
    {
        return [
            LinkBack::make(),
            Button::make('Очистить корзину')
                ->type(Color::DANGER)
                ->icon('bs.trash2')
                ->method('clear')
                ->confirm('Корзина будет очищена. Продолжить?'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('articles', [
                TD::make('id'),
                TD::make('title', 'Заголовок')->sort()
                    ->render(fn(Article $article) => $this->getTitle($article)),
                TD::make('deleted_at', 'Дата удаления')->sort()
                    ->render(fn(Article $article) => Display::datetime($article->deleted_at)),
                TD::make('created_at', 'Дата создания')->sort()
                    ->render(fn(Article $article) => Display::datetime($article->created_at)),
                TD::make('actions', '')
                    ->render(function ($model) {
                        return Group::make([
                            ButtonRestore::make()
                                ->confirm('Публикация будет восстановлена из корзины. Продолжить?')
                                ->method('restore', ['id' => $model->id]),
                            ButtonDelete::make()
                                ->confirm('Публикация будет удалена безвозвратно! Продолжить?')
                                ->method('delete', ['id' => $model->id]),
                        ])->autoWidth()->toEnd();
                    })->sort(),
            ]),
        ];
    }

    public function delete(Request $request): RedirectResponse
    {
        $article = Article::withTrashed()->findOrFail($request->get('id'));
        $title = $article->title;

        if ($article->forceDelete()) {
            Toast::success(__('Публикация «:title» удалена', ['title' => $title]))->delay(3000);

            return Article::onlyTrashed()->count()
                ? redirect()->route('platform.article.trash')
                : redirect()->route('platform.article.list');
        }

        Toast::error('Не удалось удалить публикацию');

        return redirect()->route('platform.article.trash');
    }

    public function restore(Request $request): RedirectResponse
    {
        $article = Article::withTrashed()->findOrFail($request->get('id'));
        $title = $article->title;
        $restoreSuccessful = false;

        Article::withoutTimestamps(static function () use ($article, &$restoreSuccessful) {
            if ($restoreSuccessful = $article->restore()) {
                $article->update(['published' => false]);
            }
        });

        if ($restoreSuccessful) {
            Toast::success(__('Публикация «:title» восстановлена и корзины', ['title' => $article->title]))
                ->delay(3000);

            return Article::onlyTrashed()->count()
                ? redirect()->route('platform.article.trash')
                : redirect()->route('platform.article.list');
        }

        Toast::error('Не удалось восстановить публикацию');

        return redirect()->route('platform.article.trash');
    }

    public function clear(): RedirectResponse
    {
        $deleted = Article::onlyTrashed()->forceDelete();

        if ($deleted) {
            Toast::success(__('Корзина очищена'))->delay(3000);

            return redirect()->route('platform.article.list');
        }

        Toast::error('Не удалось очистить корзину');

        return redirect()->route('platform.article.trash');
    }


    private function getTitle(Article $article): string
    {
        return '<span style="font-weight:500">'.Str::limit($article->title, 50).'</span>';
    }
}
