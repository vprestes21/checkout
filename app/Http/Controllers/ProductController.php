<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $products = auth()->user()->products;
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:1',
            'logo_url' => 'nullable|url',
            'logo_width' => 'nullable|integer|min:50|max:800',
            'logo_height' => 'nullable|integer|min:50|max:800',
            'payment_methods' => 'required|array',
            'template' => 'required|string|in:modern,classic,minimal',
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
        ]);
        
        $product = auth()->user()->products()->create($validated);
        $product->generateSlug();

        return redirect()->route('products.index')->with('success', 'Produto criado com sucesso!');
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:1',
            'logo_url' => 'nullable|url',
            'logo_width' => 'nullable|integer|min:50|max:800',
            'logo_height' => 'nullable|integer|min:50|max:800',
            'payment_methods' => 'required|array',
            'template' => 'required|string|in:modern,classic,minimal',
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produto excluído com sucesso!');
    }

    public function dashboard()
    {
        $user = auth()->user();
        $products = $user->products;
        $totalSales = 0;
        $approvedSales = 0;
        $rejectedSales = 0;
        
        foreach ($products as $product) {
            $totalSales += $product->orders->count();
            $approvedSales += $product->orders->where('status', 'approved')->count();
            $rejectedSales += $product->orders->where('status', 'rejected')->count();
        }
        
        $wallet = $user->wallet ?? $user->wallet()->create(['balance' => 0]);
        
        return view('dashboard', compact('products', 'totalSales', 'approvedSales', 'rejectedSales', 'wallet'));
    }
}
