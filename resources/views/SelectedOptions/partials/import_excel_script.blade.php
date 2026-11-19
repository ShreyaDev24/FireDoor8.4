<script>
$(function () {
    var importModule = @json($selectedImportModule);
    var importUrl = @json(route('selected-options.importPrices'));
    var importToken = @json(csrf_token());

    var stored = sessionStorage.getItem('selectedImportMessage');
    if (stored) {
        sessionStorage.removeItem('selectedImportMessage');
        showImportAlert('success', escapeHtml(stored));
    }

    $('#importSelectedExcel').on('click', function () {
        $('#importSelectedExcelFile').val('');
        $('#importSelectedExcelFile').trigger('click');
    });

    $('#importSelectedExcelFile').on('change', function () {
        var file = this.files && this.files[0];
        if (!file) {
            return;
        }

        var name = (file.name || '').toLowerCase();
        if (!name.endsWith('.xlsx') && !name.endsWith('.xls')) {
            showImportAlert('danger', 'Only Excel files (.xlsx, .xls) can be imported.');
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            showImportAlert('danger', 'The Excel file must be 5 MB or smaller.');
            return;
        }

        var data = new FormData();
        data.append('file', file);
        data.append('module', importModule);
        data.append('_token', importToken);

        var button = $('#importSelectedExcel');
        button.prop('disabled', true);

        $.ajax({
            url: importUrl,
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: function (response) {
                sessionStorage.setItem('selectedImportMessage', response.message || 'Prices updated.');
                window.location.reload();
            },
            error: function (xhr) {
                button.prop('disabled', false);
                showImportAlert('danger', importErrorHtml(xhr));
            }
        });
    });

    function importErrorHtml(xhr) {
        var body = xhr.responseJSON || {};
        var message = body.message || 'The Excel file could not be imported.';
        var html = '<strong>' + escapeHtml(message) + '</strong>';
        var lines = [];

        if (Array.isArray(body.errors)) {
            body.errors.forEach(function (error) {
                if (!error) {
                    return;
                }
                if (typeof error === 'string') {
                    lines.push(error);
                    return;
                }
                var prefix = error.row ? 'Row ' + error.row + ': ' : '';
                lines.push(prefix + (error.reason || ''));
            });
        } else if (body.errors && typeof body.errors === 'object') {
            Object.keys(body.errors).forEach(function (key) {
                var messages = body.errors[key];
                if (!Array.isArray(messages)) {
                    messages = [messages];
                }
                messages.forEach(function (item) {
                    lines.push(item);
                });
            });
        }

        if (lines.length) {
            html += '<ul class="mb-0 mt-2">';
            lines.slice(0, 30).forEach(function (line) {
                html += '<li>' + escapeHtml(line) + '</li>';
            });
            if (lines.length > 30) {
                html += '<li>' + escapeHtml('And ' + (lines.length - 30) + ' more.') + '</li>';
            }
            html += '</ul>';
        }

        return html;
    }

    function showImportAlert(type, html) {
        $('#importSelectedExcelAlert').remove();
        var alert = $('<div id="importSelectedExcelAlert" class="alert alert-' + type + '"></div>').html(html);
        var host = $('.app-main__inner').first();
        if (host.length) {
            host.prepend(alert);
        }
    }

    function escapeHtml(value) {
        return $('<div>').text(value == null ? '' : String(value)).html();
    }
});
</script>
