<?php

namespace App\Http\Controllers;

use App\Models\PortfolioItem;
use App\Services\SeoService;
use App\Http\Traits\CommonDataTrait;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    use CommonDataTrait;

    public function delivery(SeoService $seo)
    {
        $categories = $this->getCategories();
        $seo->forPage('delivery')->canonical()->breadcrumb('Главная', route('home'))->breadcrumb('Доставка', route('delivery'));
        $seoData = $seo->getPage('delivery');
        return view('delivery', compact('categories', 'seoData'))->with('seo', $seo);
    }

    public function montage(SeoService $seo)
    {
        $categories = $this->getCategories();
        $seo->forPage('montage')->canonical()->breadcrumb('Главная', route('home'))->breadcrumb('Монтаж', route('montage'));
        $seoData = $seo->getPage('montage');
        return view('montage', compact('categories', 'seoData'))->with('seo', $seo);
    }

    public function portfolio(SeoService $seo)
    {
        $categories = $this->getCategories();
        $seo->forPage('portfolio')->canonical()->breadcrumb('Главная', route('home'))->breadcrumb('Портфолио', route('portfolio'));
        $seoData = $seo->getPage('portfolio');
        $portfolioItems = PortfolioItem::query()
            ->where('is_active', true)
            ->orderBy('position')
            ->get();

        return view('portfolio', compact('categories', 'seoData', 'portfolioItems'))->with('seo', $seo);
    }

    public function contacts(SeoService $seo)
    {
        $categories = $this->getCategories();
        $seo->forPage('contacts')->canonical()->breadcrumb('Главная', route('home'))->breadcrumb('Контакты', route('contacts'));
        $seoData = $seo->getPage('contacts');
        return view('contacts', compact('categories', 'seoData'))->with('seo', $seo);
    }

    public function privacyPolicy()
    {
        $categories = $this->getCategories();
        return view('privacy-policy', compact('categories'))
            ->with('breadcrumbs', [
                ['name' => 'Главная', 'url' => route('home')],
                ['name' => 'Политика конфиденциальности', 'url' => null],
            ]);
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
