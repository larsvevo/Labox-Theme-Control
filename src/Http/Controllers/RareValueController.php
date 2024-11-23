<?php

namespace Atom\Theme\Http\Controllers;

use Atom\Core\Models\WebsiteRareValue;
use Atom\Core\Models\WebsiteRareValueCategory;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class RareValueController extends Controller
{
    /**
     * Handle an incoming request.
     */
    public function __invoke(Request $request): View
    {

        $categories = WebsiteRareValueCategory::with([
            'rareValues' => fn ($query) => $query->where('name', 'like', "%{$request->query('search')}%"),
        ])
            ->when($request->has('category_id'), fn (Builder $query) => $query->where('id', $request->query('category_id')))
            ->get();


        $furnitureIcons = WebsiteRareValue::query()
            ->where('name', 'like', "%{$request->query('search')}%")
            ->select('id', 'name', 'furniture_icon')
            ->get();

        return view('rare-values', compact('categories', 'furnitureIcons'));
    }
}
