<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // Метод для обработки общей логики получения данных
    private function handleCategoryData(Request $request, Category $category = null)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . ($category ? $category->id : ''),
        ]);

        $data['slug'] = Str::slug($data['name']);
        return $data;
    }

    public function index()
    {
        $categories = Category::paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $this->handleCategoryData($request);
        Category::create($data);
        return redirect()->route('admin.categories')->with('success', 'Категория создана.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->handleCategoryData($request, $category);
        $category->update($data);
        return redirect()->route('admin.categories')->with('success', 'Категория обновлена.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories')->with('success', 'Категория удалена.');
    }
}
