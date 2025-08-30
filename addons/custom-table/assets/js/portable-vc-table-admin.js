(function($) {
    function generateCells(rowCount, colCount, container) {
        const total = rowCount * colCount;
        const paramGroup = container.find('[data-param_type="param_group"][data-param_name="table_data"]');
        const inputArea = paramGroup.find('.vc_param_group-list');
        const template = inputArea.find('.vc_param_group-clone').first().html();

        if (!template) return;

        inputArea.empty();

        for (let i = 0; i < total; i++) {
            const row = $(`<div class="vc_param_group-row vc_param_group-clone">${template}</div>`);
            row.find('input[name*="[cell]"]').val(`Cell ${i + 1}`);
            inputArea.append(row);
        }
    }

    $(document).on('change', '.wpb_vc_param_value[name="rows"], .wpb_vc_param_value[name="cols"]', function () {
        const container = $(this).closest('.vc_edit_form_elements');
        const rows = parseInt(container.find('input[name="rows"]').val()) || 0;
        const cols = parseInt(container.find('input[name="cols"]').val()) || 0;

        if (rows > 0 && cols > 0) {
            generateCells(rows, cols, container);
        }
    });
})(jQuery);
