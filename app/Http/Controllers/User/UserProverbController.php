<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Proverb;
use App\Models\Category;
use App\Models\ProverbTranslation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class UserProverbController extends Controller
{
    /**
     * Display a listing of proverbs for users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $proverbs = Proverb::withTranslation()
        //     ->translatedIn(app()->getLocale())
        //     ->get();

        $categories = Category::all();
        $proverbs = Proverb::translated()->paginate(2);

        return view('user.proverbs.index', compact('proverbs', 'categories'));
    }

    /**
     * Display the specified proverb for users.
     *
     * @param  \App\Models\Proverb  $proverb
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categories = Category::all();
        $proverb = Proverb::findOrFail($id);

        return view('user.proverbs.show', compact('proverb', 'categories'));
    }

    public function proverbsByCategory($id)
    {
        // Retrieve the selected category by its ID.
        $category = Category::findOrFail($id);

        $categories = Category::all();
        $proverbs = Proverb::translated();

        // Retrieve related proverbs for the selected category.
        $relatedProverbs = $category->proverbs()->paginate(2);

        return view('user.proverbs.by_category', [
            'category' => $category,
            'relatedProverbs' => $relatedProverbs,
            'categories' => $categories,
            'proverbs' => $proverbs,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $categories = Category::all();

        // Perform a search on the Proverb model using the 'content' attribute.
        $proverbs = Proverb::translated()
            ->where('content', 'like', '%' . $query . '%')
            ->paginate(2);

        return view('user.proverbs.search', compact('proverbs', 'categories'));
    }
}