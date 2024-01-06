<div class="d-flex justify-content-between align-items-center">
    <div data-hook="form-sub_title">
        <span class="form-subtitle"></span>
    </div>
    <div class="section align-items-center justify-content-end <?= !empty($section) && !empty($step) ? 'hide' : 'd-flex'; ?>">
        <div class="btn-group flex-wrap d-flex gap-2" role="group" aria-label="Foundation steps" data-hook="form-nav_step_container">
            <span  class="step-span active" data-step="1">1</span>
        </div>
    </div>
</div>