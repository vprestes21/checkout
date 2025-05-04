<?php
// This is a partial view to be included in the checkout page
// It handles the customer information form fields based on product configuration

$requireEmail = $checkout_config['require_email'] ?? true;
$requirePhone = $checkout_config['require_phone'] ?? false;
$requireAddress = $checkout_config['require_address'] ?? false;
$requireCpf = $checkout_config['require_cpf'] ?? false;
$showTermsCheckbox = $checkout_config['show_terms_checkbox'] ?? false;
$termsUrl = $checkout_config['terms_url'] ?? '#';
?>

<div class="customer-info-form-container">
    <h3 class="customer-form-title">Informações do Comprador</h3>
    
    <div class="form-group">
        <label for="customer_name" class="form-label">Nome completo*</label>
        <input type="text" name="customer_name" id="customer_name" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label for="customer_email" class="form-label">
            Email<?= $requireEmail ? '*' : '' ?>
        </label>
        <input type="email" name="customer_email" id="customer_email" class="form-control" <?= $requireEmail ? 'required' : '' ?>>
    </div>
    
    <?php if ($requirePhone): ?>
    <div class="form-group">
        <label for="customer_phone" class="form-label">Telefone*</label>
        <input type="tel" name="customer_phone" id="customer_phone" class="form-control" 
               placeholder="(XX) XXXXX-XXXX" required>
    </div>
    <?php endif; ?>
    
    <?php if ($requireCpf): ?>
    <div class="form-group">
        <label for="customer_cpf" class="form-label">CPF*</label>
        <input type="text" name="customer_cpf" id="customer_cpf" class="form-control" 
               placeholder="XXX.XXX.XXX-XX" required>
    </div>
    <?php endif; ?>
    
    <?php if ($requireAddress): ?>
    <div class="address-fields">
        <div class="form-group">
            <label for="customer_zipcode" class="form-label">CEP*</label>
            <input type="text" name="customer_zipcode" id="customer_zipcode" class="form-control" 
                   placeholder="XXXXX-XXX" required>
        </div>
        
        <div class="form-row">
            <div class="form-group col">
                <label for="customer_address" class="form-label">Endereço*</label>
                <input type="text" name="customer_address" id="customer_address" class="form-control" required>
            </div>
            <div class="form-group col-md-3">
                <label for="customer_number" class="form-label">Número*</label>
                <input type="text" name="customer_number" id="customer_number" class="form-control" required>
            </div>
        </div>
        
        <div class="form-group">
            <label for="customer_complement" class="form-label">Complemento</label>
            <input type="text" name="customer_complement" id="customer_complement" class="form-control">
        </div>
        
        <div class="form-row">
            <div class="form-group col">
                <label for="customer_neighborhood" class="form-label">Bairro*</label>
                <input type="text" name="customer_neighborhood" id="customer_neighborhood" class="form-control" required>
            </div>
            <div class="form-group col">
                <label for="customer_city" class="form-label">Cidade*</label>
                <input type="text" name="customer_city" id="customer_city" class="form-control" required>
            </div>
            <div class="form-group col-md-2">
                <label for="customer_state" class="form-label">Estado*</label>
                <select name="customer_state" id="customer_state" class="form-control" required>
                    <option value="">UF</option>
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
    
    <?php if ($showTermsCheckbox): ?>
    <div class="form-group">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="agree_terms" name="agree_terms" required>
            <label class="form-check-label" for="agree_terms">
                Concordo com os <a href="<?= htmlspecialchars($termsUrl) ?>" target="_blank">Termos e Condições</a>
            </label>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Hidden fields for UTM data will be added via JavaScript -->
</div>

<script>
    <?php if ($requirePhone): ?>
    // Format phone number
    document.getElementById('customer_phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 11) value = value.substring(0, 11);
        
        if (value.length > 2) {
            value = '(' + value.substring(0, 2) + ') ' + value.substring(2);
        }
        
        if (value.length > 10) {
            value = value.substring(0, 10) + '-' + value.substring(10);
        }
        
        e.target.value = value;
    });
    <?php endif; ?>
    
    <?php if ($requireCpf): ?>
    // Format CPF
    document.getElementById('customer_cpf').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 11) value = value.substring(0, 11);
        
        if (value.length > 3) {
            value = value.substring(0, 3) + '.' + value.substring(3);
        }
        
        if (value.length > 7) {
            value = value.substring(0, 7) + '.' + value.substring(7);
        }
        
        if (value.length > 11) {
            value = value.substring(0, 11) + '-' + value.substring(11);
        }
        
        e.target.value = value;
    });
    <?php endif; ?>
    
    <?php if ($requireAddress): ?>
    // CEP auto-complete
    document.getElementById('customer_zipcode').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 8) value = value.substring(0, 8);
        
        if (value.length > 5) {
            value = value.substring(0, 5) + '-' + value.substring(5);
        }
        
        e.target.value = value;
        
        // If we have 8 digits, fetch address data
        if (value.replace(/\D/g, '').length === 8) {
            fetchAddressFromCep(value.replace(/\D/g, ''));
        }
    });
    
    function fetchAddressFromCep(cep) {
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                if (!data.erro) {
                    document.getElementById('customer_address').value = data.logradouro;
                    document.getElementById('customer_neighborhood').value = data.bairro;
                    document.getElementById('customer_city').value = data.localidade;
                    document.getElementById('customer_state').value = data.uf;
                    document.getElementById('customer_number').focus();
                }
            })
            .catch(error => console.error('Erro ao buscar CEP:', error));
    }
    <?php endif; ?>
</script>
