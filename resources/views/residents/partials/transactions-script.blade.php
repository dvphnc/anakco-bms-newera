{{-- Transactions tab behaviour (Task 1.1): history table, Record and Void dialogs. --}}
@push('scripts')
<script src="{{ asset('assets/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script>
(function () {
    const urls = {
        list:        @json(route('residents.transactions.index', $resident)),
        store:       @json(route('residents.transactions.store', $resident)),
        eligibility: @json(route('residents.eligibility', [$resident, '__P__'])),
        void:        @json(route('transactions.void', '__T__')),
    };
    let table = null, txType = '';
    const badge = document.getElementById('txCountBadge');

    function setCount(delta) {
        const n = Math.max(0, (parseInt(badge.textContent, 10) || 0) + delta);
        badge.textContent = n;
        badge.style.display = n ? '' : 'none';
    }

    // Created on first open so DataTables measures a visible table
    function ensureTable() {
        if (table) return;
        table = $('#txTable').DataTable({
            processing: true, serverSide: true, autoWidth: false, pageLength: 10,
            order: [[0, 'desc']],
            ajax: { url: urls.list, data: function (d) { d.type = txType; } },
            columns: [
                { data: 'date_col', name: 'transacted_at' },
                { data: 'item_col', name: 'description', orderable: false },
                { data: 'qty_col',  name: 'quantity', orderable: false },
                { data: 'ref_col',  name: 'reference_no', orderable: false },
                { data: 'actions',  name: 'actions', orderable: false, searchable: false },
            ],
            language: {
                processing: '<i class="fas fa-spinner fa-spin"></i> Loading…',
                emptyTable: '<div class="empty-state"><i class="fas fa-receipt"></i><p>Nothing recorded yet.</p></div>',
                zeroRecords: '<div class="empty-state"><i class="fas fa-receipt"></i><p>Nothing of this type yet.</p></div>',
            },
        });
    }
    document.getElementById('tabBtnTransactions').addEventListener('click', ensureTable);

    document.querySelectorAll('.tx-chip').forEach(function (chip) {
        chip.addEventListener('click', function () {
            document.querySelectorAll('.tx-chip').forEach(function (c) {
                c.classList.toggle('active', c === chip);
                c.setAttribute('aria-pressed', c === chip ? 'true' : 'false');
            });
            txType = chip.dataset.type;
            ensureTable();
            table.ajax.reload();
        });
    });

    const esc = function (s) { return $('<div>').text(s == null ? '' : String(s)).html(); };

    function errorText(err) {
        const data = err.response && err.response.data;
        if (data && data.errors) return Object.values(data.errors).flat().join(' ');
        return (data && data.message) || 'Something went wrong. Please try again.';
    }
    function busy(btn, on, label) {
        btn.disabled = on;
        btn.querySelector('i').className = on ? 'fas fa-spinner fa-spin' : btn.dataset.icon;
        btn.querySelector('span').textContent = on ? 'Saving…' : label;
    }

    /* ── Record dialog ───────────────────────────────────────────────── */
    const txModal = document.getElementById('txModal');
    if (txModal) {
        const form    = document.getElementById('txForm');
        const program = document.getElementById('txProgram');
        const elig    = document.getElementById('txEligibility');
        const submit  = document.getElementById('txSubmit');
        const err     = document.getElementById('txError');
        submit.dataset.icon = 'fas fa-check';
        let eligSeq = 0;

        function localNow() {
            const d = new Date();
            d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
            return d.toISOString().slice(0, 16);
        }

        window.openTxModal = function () {
            form.reset();
            err.style.display = 'none';
            document.getElementById('txWhen').value = localNow();
            document.getElementById('txWhen').max = localNow();
            onProgram();
            txModal.style.display = 'flex';
        };
        window.closeTxModal = function () { txModal.style.display = 'none'; };

        function onProgram() {
            const id = program.value;
            document.getElementById('txOneOff').style.display = id ? 'none' : '';
            elig.className = 'tx-elig';
            elig.innerHTML = '';
            submit.disabled = false;
            if (!id) return;

            const mine = ++eligSeq;
            elig.className = 'tx-elig wait';
            elig.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking eligibility…';
            submit.disabled = true;
            axios.get(urls.eligibility.replace('__P__', id)).then(function (res) {
                if (mine !== eligSeq) return;
                const data = res.data;
                if (data.eligible) {
                    elig.className = 'tx-elig ok';
                    elig.innerHTML = '<i class="fas fa-circle-check"></i><span>Eligible — ' + data.claims_used + ' of '
                        + data.max_claims + ' claim(s) used ' + (data.scope === 'household' ? 'by this household' : 'by this resident') + '.'
                        + (data.claims_left !== null ? ' Stock left for ' + data.claims_left + ' more claim(s).' : '') + '</span>';
                    submit.disabled = false;
                } else {
                    // Submit stays disabled; the server re-checks on save anyway
                    elig.className = 'tx-elig no';
                    elig.innerHTML = '<i class="fas fa-circle-xmark"></i><span>' + esc(data.reason) + '</span>';
                }
            }).catch(function () {
                if (mine !== eligSeq) return;
                elig.className = 'tx-elig wait';
                elig.textContent = 'Could not check eligibility — the server will check when you save.';
                submit.disabled = false;
            });
        }
        program.addEventListener('change', onProgram);

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            err.style.display = 'none';
            busy(submit, true, 'Save');
            const body = Object.fromEntries(new FormData(form).entries());
            if (body.assistance_program_id) { delete body.type; delete body.description; }
            Object.keys(body).forEach(function (k) { if (body[k] === '') delete body[k]; });

            axios.post(urls.store, body, { headers: { Accept: 'application/json' } })
                .then(function (res) {
                    closeTxModal();
                    bmsToast(res.data.message, 'success');
                    setCount(+1);
                    ensureTable();
                    table.ajax.reload();
                })
                .catch(function (e2) { err.textContent = errorText(e2); err.style.display = 'flex'; })
                .finally(function () { busy(submit, false, 'Save'); });
        });
    }

    /* ── Void dialog ─────────────────────────────────────────────────── */
    const voidModal  = document.getElementById('voidModal');
    const voidForm   = document.getElementById('voidForm');
    const voidSubmit = document.getElementById('voidSubmit');
    const voidErr    = document.getElementById('voidError');
    voidSubmit.dataset.icon = 'fas fa-ban';
    let voidId = null;

    window.openVoidModal = function (id, ref) {
        voidId = id;
        document.getElementById('voidRef').textContent = ref;
        voidForm.reset();
        voidErr.style.display = 'none';
        voidModal.style.display = 'flex';
        document.getElementById('voidReason').focus();
    };
    window.closeVoidModal = function () { voidModal.style.display = 'none'; };

    voidForm.addEventListener('submit', function (e) {
        e.preventDefault();
        voidErr.style.display = 'none';
        busy(voidSubmit, true, 'Void Entry');
        axios.patch(urls.void.replace('__T__', voidId),
                { reason: document.getElementById('voidReason').value },
                { headers: { Accept: 'application/json' } })
            .then(function (res) {
                closeVoidModal();
                bmsToast(res.data.message, 'success');
                setCount(-1);
                if (table) table.ajax.reload(null, false);
            })
            .catch(function (e2) { voidErr.textContent = errorText(e2); voidErr.style.display = 'flex'; })
            .finally(function () { busy(voidSubmit, false, 'Void Entry'); });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        if (txModal) closeTxModal();
        closeVoidModal();
    });

    // Open a tab named in the URL, e.g. ?tab=transactions (used after a redirect)
    const wanted = new URLSearchParams(location.search).get('tab');
    if (wanted) {
        const btn = Array.prototype.find.call(document.querySelectorAll('.tab-btn'), function (b) {
            return (b.getAttribute('onclick') || '').indexOf("'tab-" + wanted + "'") !== -1;
        });
        if (btn) btn.click();
    }
})();
</script>
@endpush
