<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2024.
 */

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\LinkCategory;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index()
    {
        if (!Link::count('id')) {
            return view('pages.links.empty');
        }

        return view('pages.links.index');
    }

    public function categories(Request $request)
    {
        $search = trim($request->get('search', ''));

        return LinkCategory::query()
            ->when($search !== '', fn($q) => $q->where('title', 'like', "%$search%"))
            ->paginate(50);
    }

    public function links(Request $request)
    {
        $search = trim($request->get('search', ''));

        return Link::with('categories')
            ->when($search !== '', fn($q) => $q->where('title', 'like', "%$search%"))
            ->orderBy('title')
            ->paginate(30);
    }
}
