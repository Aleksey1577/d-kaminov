<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SlideController extends Controller
{
    public function index()
    {
        $slides = Slide::orderBy('position')->get();
        return view('admin.slides.index', compact('slides'));
    }

    public function create()
    {
        $categories = Product::query()
            ->whereNotNull('kategoriya')
            ->distinct()
            ->orderBy('kategoriya')
            ->pluck('kategoriya');
        return view('admin.slides.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['title'] = $data['title'] ?? '';
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('slides', 'public');
            $data['image_url'] = Storage::url($path);
        }
        Slide::create($data);

        return redirect()->route('admin.slides.index')->with('success', 'Слайд добавлен');
    }

    public function edit(Slide $slide)
    {
        $categories = Product::query()
            ->whereNotNull('kategoriya')
            ->distinct()
            ->orderBy('kategoriya')
            ->pluck('kategoriya');
        return view('admin.slides.edit', compact('slide', 'categories'));
    }

    public function update(Request $request, Slide $slide)
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['title'] = $data['title'] ?? '';
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('slides', 'public');
            $data['image_url'] = Storage::url($path);
        } elseif ($request->boolean('remove_image')) {
            $data['image_url'] = null;
        }
        $slide->update($data);

        return redirect()->route('admin.slides.index')->with('success', 'Слайд обновлён');
    }

    public function destroy(Slide $slide)
    {
        $slide->delete();
        return redirect()->route('admin.slides.index')->with('success', 'Слайд удалён');
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'button_text' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'image_url' => ['nullable', 'string', 'max:1024'],
            'image_file' => ['nullable', 'image', 'max:5120'],
            'text_color' => ['nullable', 'in:light,dark'],
            'position' => ['nullable', 'integer', 'min:1'],
        ]);
    }
}
