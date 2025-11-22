<?php

namespace App\Http\Controllers;

use App\Services\SeoService;
use App\Http\Traits\CommonDataTrait;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    use CommonDataTrait;

    public function delivery(SeoService $seo)
    {
        $categories = $this->getCategories();
        $seoData = $seo->getPage('delivery');
        return view('delivery', compact('categories', 'seoData'));
    }

    public function montage(SeoService $seo)
    {
        $categories = $this->getCategories();
        $seoData = $seo->getPage('montage');
        return view('montage', compact('categories', 'seoData'));
    }

    public function portfolio(SeoService $seo)
    {
        $categories = $this->getCategories();
        $seoData = $seo->getPage('portfolio');
        return view('portfolio', compact('categories', 'seoData'));
    }

    public function contacts(SeoService $seo)
    {
        $categories = $this->getCategories();
        $seoData = $seo->getPage('contacts');
        return view('contacts', compact('categories', 'seoData'));
    }

    public function privacyPolicy()
    {
        $categories = $this->getCategories();
        return view('privacy-policy', compact('categories'));
    }

    public function callback(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'nullable|string|max:1000',
        ]);

        // Здесь можно добавить логику, например, сохранение в БД или отправку email
        // Пример: \App\Models\CallbackRequest::create($data);

        return redirect()->back()->with('success', 'Ваш запрос отправлен!');
    }
}