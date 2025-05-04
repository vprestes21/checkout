<?php
// Customer information component for checkout
// Uses product configuration to determine which fields to display

// Default values if not provided
$requireEmail = isset($product['require_email']) ? (bool)$product['require_email'] : true;
$requirePhone = isset($product['require_phone']) ? (bool)$product['require_phone'] : false;
$requireAddress = isset($product['require_address']) ? (bool)$product['require_address'] : false;
$requireCpf = isset($product['require_cpf']) ? (bool)$product['require_cpf'] : false;
$formClass = $formClass ?? '';
$theme = $theme ?? 'modern';
?>

<div class="customer-info-section <?= $theme ?>-customer-info">
    <h3 class="customer-section-title">Informações do Comprador</h3>
    
    <div class="customer-fields">
        <!-- Name field (always required) -->
        <div class="form-group mb-3">
            <label for="customer_name" class="form-label">Nome completo *</label>
            <input type="text" id="customer_name" name="customer_name" class="form-control <?= $formClass ?>" required>
        </div>
        
        <!-- Email field -->
        <div class="form-group mb-3">
            <label for="customer_email" class="form-label">
                Email <?= $requireEmail ? '*' : '' ?>
            </label>
            <input type="email" id="customer_email" name="customer_email" class="form-control <?= $formClass ?>" 
                   <?= $requireEmail ? 'required' : '' ?>>
        </div>
        
        <?php if ($requirePhone): ?>
        <!-- Phone field -->
        <div class="form-group mb-3">
            <label for="customer_phone" class="form-label">Telefone *</label>
            <input type="tel" id="customer_phone" name="customer_phone" class="form-control <?= $formClass ?>" 
                   placeholder="(00) 00000-0000" required>
        </div>
        <?php endif; ?>
        
        <?php if ($requireCpf): ?>
        <!-- CPF field -->
        <div class="form-group mb-3">
            <label for="customer_cpf" class="form-label">CPF *</label>
            <input type="text" id="customer_cpf" name="customer_cpf" class="form-control <?= $formClass ?>" 
                   placeholder="000.000.000-00" required>
        </div>
        <?php endif; ?>
        
        <?php if ($requireAddress): ?>
        <!-- Address fields -->
        <div class="address-fields mt-4">
            <h4 class="customer-section-subtitle">Endereço</h4>
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="customer_zipcode" class="form-label">CEP *</label>
                    <input type="text" id="customer_zipcode" name="customer_zipcode" class="form-control <?= $formClass ?>" 
                           placeholder="00000-000" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-8">
                    <label for="customer_street" class="form-label">Endereço *</label>
                    <input type="text" id="customer_street" name="customer_street" class="form-control <?= $formClass ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="customer_number" class="form-label">Número *</label>
                    <input type="text" id="customer_number" name="customer_number" class="form-control <?= $formClass ?>" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="customer_complement" class="form-label">Complemento</label>
                    <input type="text" id="customer_complement" name="customer_complement" class="form-control <?= $formClass ?>">
                </div>
                <div class="col-md-6">
                    <label for="customer_neighborhood" class="form-label">Bairro *</label>
                    <input type="text" id="customer_neighborhood" name="customer_neighborhood" class="form-control <?= $formClass ?>" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-8">
                    <label for="customer_city" class="form-label">Cidade *</label>
                    <input type="text" id="customer_city" name="customer_city" class="form-control <?= $formClass ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="customer_state" class="form-label">Estado *</label>
                    <select id="customer_state" name="customer_state" class="form-select <?= $formClass ?>" required>
                        <option value="">Selecione</option>
                        <option value="AC">AC</option>
                        <option value="AL">AL</option>
                        <option value="AP">AP</option>
                        <option value="AM">AM</option>
                        <option value="BA">BA</option>
                        <option value="CE">CE</option>
                        <option value="DF">DF</option>
                        <option value="ES">ES</option>
                        <option value="GO">GO</option>
                        <option value="MA">MA</option>
                        <option value="MT">MT</option>
                        <option value="MS">MS</option>
                        <option value="MG">MG</option>
                        <option value="PA">PA</option>
                        <option value="PB">PB</option>
                        <option value="PR">PR</option>
                        <option value="PE">PE</option>
                        <option value="PI">PI</option>
                        <option value="RJ">RJ</option>
                        <option value="RN">RN</option>
                        <option value="RS">RS</option>
                        <option value="RO">RO</option>
                        <option value="RR">RR</option>
                        <option value="SC">SC</option>
                        <option value="SP">SP</option>
                        <option value="SE">SE</option>
                        <option value="TO">TO</option>
                    </select>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($requirePhone): ?>
        // Format phone number as (XX) XXXXX-XXXX
        const phoneInput = document.getElementById('customer_phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 11) value = value.substring(0, 11);
                
                let formattedValue = '';
                if (value.length > 0) {
                    formattedValue = '(' + value.substring(0, 2);
                    if (value.length > 2) {
                        formattedValue += ') ' + value.substring(2, 7);
                        if (value.length > 7) {
                            formattedValue += '-' + value.substring(7, 11);
                        }
                    }
                }
                
                e.target.value = formattedValue;
            });
        }
        <?php endif; ?>
        
        <?php if ($requireCpf): ?>
        // Format CPF as XXX.XXX.XXX-XX
        const cpfInput = document.getElementById('customer_cpf');
        if (cpfInput) {
            cpfInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 11) value = value.substring(0, 11);
                
                let formattedValue = '';
                if (value.length > 0) {
                    formattedValue = value.substring(0, 3);
                    if (value.length > 3) {
                        formattedValue += '.' + value.substring(3, 6);
                        if (value.length > 6) {
                            formattedValue += '.' + value.substring(6, 9);
                            if (value.length > 9) {
                                formattedValue += '-' + value.substring(9, 11);
                            }
                        }
                    }
                }
                
                e.target.value = formattedValue;
            });
        }
        <?php endif; ?>
        
        <?php if ($requireAddress): ?>
        // Format and auto-fill address from CEP
        const cepInput = document.getElementById('customer_zipcode');
        if (cepInput) {
            cepInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 8) value = value.substring(0, 8);
                
                // Format as XXXXX-XXX
                if (value.length > 5) {
                    value = value.substring(0, 5) + '-' + value.substring(5);
                }
                
                e.target.value = value;
                
                // If we have a complete CEP, try to auto-fill address
                if (value.replace(/\D/g, '').length === 8) {
                    fetchAddressFromCEP(value.replace(/\D/g, ''));
                }
            });
            
            function fetchAddressFromCEP(cep) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById('customer_street').value = data.logradouro;
                            document.getElementById('customer_neighborhood').value = data.bairro;
                            document.getElementById('customer_city').value = data.localidade;
                            document.getElementById('customer_state').value = data.uf;
                            
                            // Focus on number field after auto-fill
                            document.getElementById('customer_number').focus();
                        }
                    })
                    .catch(error => console.error('Error fetching CEP:', error));
            }
        }
        <?php endif; ?>
    });
</script>
