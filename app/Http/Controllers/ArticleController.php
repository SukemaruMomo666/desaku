<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::latest()->get();
        return view('dashboard.admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('dashboard.admin.articles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image|max:5120', // Max 5MB
            'type' => 'required|string|max:50',
        ]);

        $data = $request->only(['title', 'content', 'type']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        Article::create($data);

        return redirect()->route('admin.articles.index')->with('success', 'Informasi / Kegiatan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        return view('dashboard.admin.articles.edit', compact('article'));
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
            'type' => 'required|string|max:50',
        ]);

        $data = $request->only(['title', 'content', 'type']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        $article->update($data);

        return redirect()->route('admin.articles.index')->with('success', 'Informasi / Kegiatan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }
        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Data berhasil dihapus.');
    }

    public function toggleActive($id)
    {
        $article = Article::findOrFail($id);
        $article->update(['is_active' => !$article->is_active]);

        return redirect()->route('admin.articles.index')->with('success', 'Status visibilitas berhasil diubah.');
    }
}
