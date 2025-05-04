/**
 * CheckoutPro Form Helper Functions
 * Provides utilities for better form handling
 */

document.addEventListener('DOMContentLoaded', function() {
    // Fix for checkbox handling - when a checkbox with a hidden field counterpart is checked,
    // we need to make the hidden field ineffective
    const checkboxesWithHiddenFields = document.querySelectorAll('input[type="checkbox"][name]');
    
    checkboxesWithHiddenFields.forEach(checkbox => {
        const name = checkbox.getAttribute('name');
        const hiddenField = document.querySelector(`input[type="hidden"][name="${name}"]`);
        
        if (hiddenField) {
            // When the checkbox is checked, disable the hidden field so it doesn't override
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    hiddenField.disabled = true;
                } else {
                    hiddenField.disabled = false;
                }
            });
            
            // Set initial state
            if (checkbox.checked) {
                hiddenField.disabled = true;
            }
        }
    });
});

/**
 * Helper to set proper state for checkboxes with hidden counterparts on form submit
 * @param {string} formId The ID of the form to process
 */
function prepareFormForSubmit(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    
    form.addEventListener('submit', function() {
        // Re-enable all disabled fields for submission
        const disabledFields = form.querySelectorAll('input:disabled');
        disabledFields.forEach(field => {
            field.disabled = false;
        });
    });
}
