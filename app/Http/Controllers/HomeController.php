<?php

namespace App\Http\Controllers;

use App\Services\SeoService;
use App\Http\Traits\CommonDataTrait;
use App\Models\Slide;

class HomeController extends Controller
{
    use CommonDataTrait;

    public function index(\App\Services\SeoService $seo)
    {
        $seo->forPage('home')->canonical();
        $seoData         = $seo->getPage('home');
        $categories      = $this->getCategories();
        $cartQuantity    = $this->getCartQuantity();
        $compareCount    = $this->getUserCompareCount();
        $favoritesCount = $this->getUserFavoritesCount();
        $slides = Slide::where('is_active', true)
            ->orderBy('position')
            ->get();

        return view('home', compact(
            'seoData',
            'categories',
            'cartQuantity',
            'compareCount',
            'favoritesCount',
            'slides',
        ))->with('seo', $seo);
    }
}
