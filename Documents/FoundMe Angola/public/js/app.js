/**
 * FundMe Angola — Vanilla JavaScript Master Interactive Controller
 */

document.addEventListener('DOMContentLoaded', function () {
    initPresetDonationButtons();
    initMultiStepForm();
    initDynamicFundPlanItems();
    initImagePreviews();
    initCopyLinks();
});

/**
 * 1. Preset Donation Amounts Selector
 */
function initPresetDonationButtons() {
    const presetButtons = document.querySelectorAll('.btn-preset-amount');
    const customAmountInput = document.getElementById('donation_amount_input');

    if (presetButtons.length > 0 && customAmountInput) {
        presetButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                presetButtons.forEach(b => b.classList.remove('active', 'btn-success'));
                presetButtons.forEach(b => b.classList.add('btn-outline-secondary'));

                this.classList.remove('btn-outline-secondary');
                this.classList.add('active', 'btn-success');

                const val = this.getAttribute('data-value');
                customAmountInput.value = val;
            });
        });

        customAmountInput.addEventListener('input', function () {
            presetButtons.forEach(b => b.classList.remove('active', 'btn-success'));
            presetButtons.forEach(b => b.classList.add('btn-outline-secondary'));
        });
    }
}

/**
 * 2. Multi-step Campaign Form Wizard
 */
function initMultiStepForm() {
    const form = document.getElementById('multiStepCampaignForm');
    if (!form) return;

    const steps = document.querySelectorAll('.wizard-step');
    const stepIndicators = document.querySelectorAll('.step-indicator-item');
    let currentStep = 0;

    function showStep(stepIndex) {
        steps.forEach((step, idx) => {
            step.style.display = (idx === stepIndex) ? 'block' : 'none';
        });

        stepIndicators.forEach((ind, idx) => {
            if (idx === stepIndex) {
                ind.classList.add('active', 'fw-bold', 'text-primary');
            } else if (idx < stepIndex) {
                ind.classList.add('completed', 'text-success');
                ind.classList.remove('active');
            } else {
                ind.classList.remove('active', 'completed', 'fw-bold');
            }
        });

        window.scrollTo({ top: 150, behavior: 'smooth' });
    }

    const nextButtons = document.querySelectorAll('.btn-next-step');
    const prevButtons = document.querySelectorAll('.btn-prev-step');

    nextButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            if (validateCurrentStep(currentStep)) {
                if (currentStep < steps.length - 1) {
                    currentStep++;
                    showStep(currentStep);
                }
            }
        });
    });

    prevButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            if (currentStep > 0) {
                currentStep--;
                showStep(currentStep);
            }
        });
    });

    function validateCurrentStep(stepIdx) {
        const activeStep = steps[stepIdx];
        const requiredInputs = activeStep.querySelectorAll('[required]');
        let isValid = true;

        requiredInputs.forEach(input => {
            if (!input.checkValidity()) {
                input.reportValidity();
                isValid = false;
            }
        });

        return isValid;
    }

    showStep(currentStep);
}

/**
 * 3. Dynamic Fund Plan Breakdown Items
 */
function initDynamicFundPlanItems() {
    const container = document.getElementById('fundPlanItemsContainer');
    const addBtn = document.getElementById('btnAddFundItem');

    if (container && addBtn) {
        addBtn.addEventListener('click', function () {
            const newItemHtml = `
                <div class="row g-2 mb-2 fund-plan-row">
                    <div class="col-md-7">
                        <input type="text" name="fund_item_name[]" class="form-control" placeholder="Ex: Internamento, Medicamentos, Exames" required>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="number" name="fund_item_amount[]" class="form-control fund-amount-input" placeholder="Valor em Kz" step="500" min="0" required>
                            <span class="input-group-text">Kz</span>
                        </div>
                    </div>
                    <div class="col-md-1 d-flex align-items-center">
                        <button type="button" class="btn btn-outline-danger btn-sm btn-remove-fund-item w-100"><i class="bi bi-trash"></i>✕</button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', newItemHtml);
            updateTotalFundGoal();
        });

        container.addEventListener('click', function (e) {
            if (e.target.classList.contains('btn-remove-fund-item') || e.target.parentElement.classList.contains('btn-remove-fund-item')) {
                const row = e.target.closest('.fund-plan-row');
                if (row) {
                    row.remove();
                    updateTotalFundGoal();
                }
            }
        });

        container.addEventListener('input', function (e) {
            if (e.target.classList.contains('fund-amount-input')) {
                updateTotalFundGoal();
            }
        });
    }

    function updateTotalFundGoal() {
        const inputs = document.querySelectorAll('.fund-amount-input');
        const targetAmountInput = document.getElementById('target_amount_input');
        let total = 0;

        inputs.forEach(input => {
            const val = parseFloat(input.value) || 0;
            total += val;
        });

        if (targetAmountInput && total > 0) {
            targetAmountInput.value = total;
        }
    }
}

/**
 * 4. Image Live Preview
 */
function initImagePreviews() {
    const fileInputs = document.querySelectorAll('.image-preview-input');
    fileInputs.forEach(input => {
        input.addEventListener('change', function () {
            const previewId = this.getAttribute('data-preview-target');
            const previewElement = document.getElementById(previewId);

            if (previewElement && this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewElement.src = e.target.result;
                    previewElement.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
}

/**
 * 5. Copy Link to Clipboard
 */
function initCopyLinks() {
    const copyBtns = document.querySelectorAll('.btn-copy-link');
    copyBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const textToCopy = this.getAttribute('data-link') || window.location.href;
            navigator.clipboard.writeText(textToCopy).then(() => {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="bi bi-check2"></i> Link Copiado!';
                this.classList.add('btn-success');
                this.classList.remove('btn-outline-primary');

                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.classList.remove('btn-success');
                    this.classList.add('btn-outline-primary');
                }, 2500);
            });
        });
    });
}
