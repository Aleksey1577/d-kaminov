<?php

namespace App\Http\Controllers;

use App\Services\SeoService;
use App\Http\Traits\CommonDataTrait;

class HomeController extends Controller
{
    use CommonDataTrait;

    public function index(\App\Services\SeoService $seo)
    {
        $seoData        = $seo->getPage('home');
        $categories    = $this->getCategories();
        $cartQuantity  = $this->getCartQuantity();
        $compareCount  = $this->getUserCompareCount();
        $favoritesCount = $this->getUserFavoritesCount();

        return view('home', compact(
            'seoData', 
            'categories',
            'cartQuantity',
            'compareCount',
            'favoritesCount'
        ))->with('seo', $seo);
    }
}
