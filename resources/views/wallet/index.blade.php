@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1>Minha Carteira</h1>

    <div class="row mt-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Saldo Disponível</h5>
                    <h2 class="text-primary">R$ {{ number_format($wallet->balance, 2, ',', '.') }}</h2>
                    
                    <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#withdrawModal">
                        Transferir via PIX
                    </button>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Informações</h5>
                    <p class="mb-1">
                        <small>
                            Os valores ficam disponíveis após 14 dias da aprovação da venda.
                            Transferências via PIX são processadas em até 1 dia útil.
                        </small>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Histórico de Transferências</h5>
                </div>
                <div class="card-body">
                    @if($transfers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Valor</th>
                                    <th>Chave PIX</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transfers as $transfer)
                                <tr>
                                    <td>{{ $transfer->created_at->format('d/m/Y H:i') }}</td>
                                    <td>R$ {{ number_format($transfer->amount, 2, ',', '.') }}</td>
                                    <td>{{ $transfer->pix_key }}</td>
                                    <td>
                                        @if($transfer->status == 'completed')
                                        <span class="badge bg-success">Concluído</span>
                                        @elseif($transfer->status == 'pending')
                                        <span class="badge bg-warning text-dark">Pendente</span>
                                        @else
                                        <span class="badge bg-danger">Falhou</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-center py-4">
                        Você ainda não realizou nenhuma transferência.
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de transferência -->
<div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="withdrawModalLabel">Transferir para PIX</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('wallet.transfer') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="amount" class="form-label">Valor</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" class="form-control" id="amount" name="amount" min="1" step="0.01" max="{{ $wallet->balance }}" required>
                        </div>
                        <div class="form-text">Saldo disponível: R$ {{ number_format($wallet->balance, 2, ',', '.') }}</div>
                        
                        @error('amount')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="pix_key" class="form-label">Chave PIX</label>
                        <input type="text" class="form-control" id="pix_key" name="pix_key" required>
                        <div class="form-text">CPF, e-mail, telefone ou chave aleatória</div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Transferir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
