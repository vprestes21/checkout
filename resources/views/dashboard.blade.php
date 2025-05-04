@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1>Dashboard</h1>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Total de Vendas</h5>
                    <p class="display-4 text-primary">{{ $totalSales }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Vendas Aprovadas</h5>
                    <p class="display-4 text-success">{{ $approvedSales }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Vendas Rejeitadas</h5>
                    <p class="display-4 text-danger">{{ $rejectedSales }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Saldo Disponível</h5>
                </div>
                <div class="card-body">
                    <h2 class="text-primary">R$ {{ number_format($wallet->balance, 2, ',', '.') }}</h2>
                    <a href="{{ route('wallet.index') }}" class="btn btn-outline-primary mt-3">Ir para Carteira</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Produtos</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1">Total de produtos: <strong>{{ $products->count() }}</strong></p>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary mt-3">Gerenciar Produtos</a>
                    <a href="{{ route('products.create') }}" class="btn btn-primary mt-3">Criar Novo Produto</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Produtos Recentes</h5>
        </div>
        <div class="card-body">
            @if($products->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Preço</th>
                            <th>Vendas</th>
                            <th>Faturamento</th>
                            <th>Link</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products->take(5) as $product)
                        <tr>
                            <td>{{ $product->title }}</td>
                            <td>R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                            <td>{{ $product->getSalesCount() }}</td>
                            <td>R$ {{ number_format($product->getTotalRevenue(), 2, ',', '.') }}</td>
                            <td>
                                <a href="{{ $product->getCheckoutUrl() }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-external-link-alt"></i> Checkout
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-center py-4">
                Você ainda não possui produtos cadastrados.
                <a href="{{ route('products.create') }}">Crie seu primeiro produto</a>
            </p>
            @endif
        </div>
    </div>
</div>
@endsection
