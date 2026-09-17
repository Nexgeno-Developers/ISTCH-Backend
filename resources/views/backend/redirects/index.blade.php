@extends('backend.layouts.app')

@section('content')
<div class="page-title-head d-flex align-items-center gap-2 mb-3">
    <div class="flex-grow-1"><h4 class="fs-16 text-uppercase fw-bold mb-0">Redirect Manager</h4><small class="text-muted">301/302 redirects served immediately from the website edge. Company ID: {{ $companyId }}</small></div>
    <button id="add-redirect" class="btn btn-primary" type="button">+ New redirect</button>
</div>
<div class="card"><div class="card-body">
    <div class="alert alert-info">Use internal paths only (for example <code>/old-page</code> to <code>/new-page</code>). A rule takes effect as soon as it is saved; no frontend redeploy is required.</div>
    <form id="redirects-form" action="{{ route('redirects.update') }}" method="POST">@csrf
        <input type="hidden" name="redirects" id="redirects-json">
        <div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th>From</th><th>To</th><th>Code</th><th>Enabled</th><th>Note</th><th></th></tr></thead><tbody id="redirect-rows"></tbody></table></div>
        <button type="submit" class="btn btn-primary">Save redirects</button>
    </form>
</div></div>
<template id="redirect-row-template"><tr><td><input class="form-control rule-from" placeholder="/old-page"></td><td><input class="form-control rule-to" placeholder="/new-page"></td><td><select class="form-select rule-status"><option value="301">301 (permanent)</option><option value="302">302 (temporary)</option></select></td><td><div class="form-check form-switch"><input class="form-check-input rule-enabled" type="checkbox" checked></div></td><td><input class="form-control rule-note" maxlength="500" placeholder="Optional"></td><td><button class="btn btn-sm btn-outline-danger remove-rule" type="button">Remove</button></td></tr></template>
<script>
$(function () {
    const initialRules = @json($redirects);
    const tbody = $('#redirect-rows'); const template = document.getElementById('redirect-row-template');
    const uuid = () => crypto.randomUUID ? crypto.randomUUID() : 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => { const r=Math.random()*16|0; return (c==='x'?r:(r&3|8)).toString(16); });
    function addRule(rule = {}) { const fragment = template.content.cloneNode(true); const row = $(fragment.querySelector('tr')); row.data('id', rule.id || uuid()); row.find('.rule-from').val(rule.from || ''); row.find('.rule-to').val(rule.to || ''); row.find('.rule-status').val(String(rule.status || 301)); row.find('.rule-enabled').prop('checked', rule.enabled !== false); row.find('.rule-note').val(rule.note || ''); tbody.append(row); }
    initialRules.forEach(addRule); $('#add-redirect').on('click', () => addRule());
    tbody.on('click', '.remove-rule', function () { $(this).closest('tr').remove(); });
    $('#redirects-form').on('submit', function (e) { e.preventDefault(); const rules=[]; let invalid=false; tbody.find('tr').each(function () { const row=$(this), from=row.find('.rule-from').val().trim(), to=row.find('.rule-to').val().trim(); if (!from && !to) return; if (!from || !to) { invalid=true; return; } rules.push({id: row.data('id'), from, to, status:Number(row.find('.rule-status').val()), enabled:row.find('.rule-enabled').is(':checked'), note:row.find('.rule-note').val().trim() || null}); }); if (invalid) { toastr.error('Every redirect needs both From and To paths.'); return; } $('#redirects-json').val(JSON.stringify(rules)); ajaxSubmit(e, $(this), function () {}); });
});
</script>
@endsection
