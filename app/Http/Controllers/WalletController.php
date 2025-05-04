<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Transfer;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $user = auth()->user();
        $wallet = $user->wallet ?? $user->wallet()->create(['balance' => 0]);
        $transfers = $wallet->transfers()->latest()->get();
        
        return view('wallet.index', compact('wallet', 'transfers'));
    }
    
    public function transfer(Request $request)
    {
        $user = auth()->user();
        $wallet = $user->wallet;
        
        if (!$wallet) {
            return redirect()->route('wallet.index')->withErrors(['wallet' => 'Carteira não encontrada']);
        }
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'pix_key' => 'required|string|max:255',
        ]);
        
        try {
            $transfer = $wallet->withdraw($validated['amount'], $validated['pix_key']);
            return redirect()->route('wallet.index')->with('success', 'Transferência de R$ ' . number_format($validated['amount'], 2, ',', '.') . ' realizada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('wallet.index')->withErrors(['amount' => $e->getMessage()]);
        }
    }
}
