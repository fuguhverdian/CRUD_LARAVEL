<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    function index() {
        $product = Product::all();

        return view('products.index', [
            'product' => $product
        ]);
    }
     function cari(Request $request)
    {
        $search = $request->input('search');

        $products = Product::where('name', 'LIKE', "%{$search}%")->get();

        if ($products->isEmpty()) {
        return view('products.cari', [
            'products' => $products,
            'search' => $search,
            'error' => "Produk dengan kata kunci '{$search}' tidak ditemukan."
        ]);
    }
        return view('products.cari', [
            'products' => $products,
            'search'   => $search
        ]);
    }

     function add() {
        return view('products.add');
    }

    function save(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'description'  => 'required|string',
            'image' => 'image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);


        $product = new Product();
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        
        // save a imgae
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->image = $path;
        }

        $product->save();

        return redirect('/')->with('success', 'Produk berhasil disimpan.');
    }

     function edit($id) {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|string',
            'description'  => 'required|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'

        ]);

        $product = Product::findOrFail($id);
        $product->name = $request->input('name');
        $product->description = $request->input('description');

        if ($request->hasFile('image')) {

        // hapus file lama jika ada
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // upload gambar baru
        $path = $request->file('image')->store('products', 'public');
        $product->image = $path;
        }

        $product->update();

        return redirect('/')->with('success', 'Produk berhasil diedit.');
    }
    function delete(Request $request, $id) {
        $product = Product::findOrFail($id);
        
        if ($product->image && Storage::disk('public')->exists($product->image)) {
        Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect('/')->with('success', 'Produk berhasil dihapus.');
    }
}
