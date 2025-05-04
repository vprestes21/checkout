<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        return view('checkout.show', compact('product'));
    }
    
    public function purchase(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        
        $validated = $request->validate([
            'buyer_name' => 'required|string|max:255',
            'buyer_email' => 'required|email',
            'payment_method' => 'required|string|in:pix,card',
        ]);
        
        // Verificar se o método de pagamento está habilitado para este produto
        if (!in_array($validated['payment_method'], $product->payment_methods)) {
            return back()->withErrors(['payment_method' => 'Este método de pagamento não está disponível']);
        }
        
        // Criar comprador ou usar existente
        $buyer = User::firstOrCreate(
            ['email' => $validated['buyer_email']],
            [
                'name' => $validated['buyer_name'],
                'password' => bcrypt(uniqid())
            ]
        );
        
        // Criar o pedido
        $order = Order::create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'status' => 'pending',
            'payment_method' => $validated['payment_method'],
            'amount' => $product->price,
            'buyer_name' => $validated['buyer_name'],
            'buyer_email' => $validated['buyer_email'],
        ]);
        
        if ($validated['payment_method'] == 'pix') {
            // Gera QR Code do PIX (aqui seria integração com API)
            $pixCode = "00020101021226800014br.gov.bcb.pix2558invoice-" . $order->id . "@checkout.com.br5204000053039865802BR5914CHECKOUT LTDA6008SAO PAULO62070503***63041D57";
            return view('checkout.pix', compact('pixCode', 'product', 'order'));
        } else {
            // Formulário de cartão
            return view('checkout.card', compact('product', 'order'));
        }
    }
    
    public function processCard(Request $request, Order $order)
    {
        $validated = $request->validate([
            'card_number' => 'required|string|size:16',
            'card_name' => 'required|string',
            'card_expiry' => 'required|string|size:5',
            'card_cvv' => 'required|string|size:3',
        ]);
        
        // Simular aprovação (em produção, integrar com gateway de pagamento)
        $order->transaction_id = 'card_'.uniqid();
        $order->markAsApproved();
        
        return view('checkout.success', compact('order'));
    }
    
    public function confirmPix(Order $order)
    {
        // Simular confirmação de PIX (em produção, seria via webhook)
        $order->transaction_id = 'pix_'.uniqid();
        $order->markAsApproved();
        return view('checkout.success', compact('order'));
    }
    
    public function webhook(Request $request)
    {
        // Endpoint para receber notificações de pagamento
        $payload = $request->all();
        
        // Em produção, validar assinatura do webhook
        
        if (isset($payload['reference_id'])) {
            $order = Order::find($payload['reference_id']);
            
            if ($order) {
                if ($payload['status'] === 'paid') {
                    $order->markAsApproved();
                } elseif ($payload['status'] === 'refused') {
                    $order->markAsRejected();
                }
                
                return response()->json(['success' => true]);
            }
        }
        
        return response()->json(['success' => false], 404);
    }
}
