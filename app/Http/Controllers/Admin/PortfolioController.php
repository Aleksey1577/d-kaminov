<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PortfolioItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    public function index()
    {
        $items = PortfolioItem::query()->orderBy('position')->get();
        return view('admin.portfolio.index', compact('items'));
    }

    public function create()
    {
        return view('admin.portfolio.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['position'] = $data['position'] ?? 1;

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('portfolio', 'public');
            $data['image_url'] = Storage::url($path);
        }

        PortfolioItem::create($data);

        return redirect()->route('admin.portfolio.index')->with('success', 'Карточка добавлена');
    }

    public function edit(PortfolioItem $portfolioItem)
    {
        return view('admin.portfolio.edit', compact('portfolioItem'));
    }

    public function update(Request $request, PortfolioItem $portfolioItem)
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['position'] = $data['position'] ?? 1;

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('portfolio', 'public');
            $data['image_url'] = Storage::url($path);
        } elseif ($request->boolean('remove_image')) {
            $data['image_url'] = null;
        }

        $portfolioItem->update($data);

        return redirect()->route('admin.portfolio.index')->with('success', 'Карточка обновлена');
    }

    public function destroy(PortfolioItem $portfolioItem)
    {
        $portfolioItem->delete();
        return redirect()->route('admin.portfolio.index')->with('success', 'Карточка удалена');
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:5000'],
            'image_url' => ['nullable', 'string', 'max:1024', 'regex:/^(\\/(?!\\/)|https?:\\/\\/)/i'],
            'image_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'position' => ['nullable', 'integer', 'min:1'],
        ]);
    }
}
