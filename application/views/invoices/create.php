<div class="card">
    <div class="card-header">
        <div class="card-title">Create Invoice</div>
        <a href="<?php echo site_url('invoices'); ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Back
        </a>
    </div>

    <form action="<?php echo site_url('invoices/create'); ?>" method="post" id="invoiceForm">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
            <div class="form-group">
                <label class="form-label">Invoice Number *</label>
                <input type="text" name="invoice_number" class="form-control" value="<?php echo $invoice_number; ?>"
                    readonly>
            </div>

            <div class="form-group">
                <label class="form-label">Customer *</label>
                <select name="customer_id" class="form-control" required>
                    <option value="">Select Customer</option>
                    <?php foreach ($customers as $customer): ?>
                        <option value="<?php echo $customer->id; ?>"><?php echo $customer->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Invoice Date *</label>
                <input type="date" name="invoice_date" class="form-control" value="<?php echo date('Y-m-d'); ?>"
                    required>
            </div>

            <div class="form-group">
                <label class="form-label">Due Date</label>
                <input type="date" name="due_date" class="form-control">
            </div>
        </div>

        <div style="background-color: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="margin: 0;">Invoice Items</h3>
                <button type="button" class="btn btn-primary" onclick="addInvoiceItem()">
                    <i class="fas fa-plus" style="margin-right: 8px;"></i> Add Item
                </button>
            </div>

            <div id="invoiceItems">
                <!-- Items will be added here -->
            </div>
        </div>

        <div style="background-color: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <div style="max-width: 400px; margin-left: auto;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span>Subtotal:</span>
                    <span id="subtotal"><?php echo $currency_symbol; ?> 0.00</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span>Tax:</span>
                    <span id="tax"><?php echo $currency_symbol; ?> 0.00</span>
                </div>
                <div
                    style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: 700; padding-top: 12px; border-top: 2px solid var(--border-color);">
                    <span>Grand Total:</span>
                    <span id="grandTotal"><?php echo $currency_symbol; ?> 0.00</span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="3"></textarea>
        </div>

        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save" style="margin-right: 8px;"></i> Create Invoice
            </button>
            <a href="<?php echo site_url('invoices'); ?>" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>

<script>
    const items = <?php echo json_encode($items); ?>;
    let itemCounter = 0;

    function addInvoiceItem() {
        const container = document.getElementById('invoiceItems');
        const itemDiv = document.createElement('div');
        itemDiv.className = 'invoice-item';
        itemDiv.style.cssText = 'display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto; gap: 12px; margin-bottom: 12px; align-items: end;';
        itemDiv.id = `item-${itemCounter}`;

        itemDiv.innerHTML = `
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Item</label>
            <select name="item_id[]" class="form-control item-select" onchange="selectItem(this, ${itemCounter})">
                <option value="">Select Item</option>
                ${items.map(item => `<option value="${item.id}" data-price="${item.price}" data-tax="${item.tax_rate}" data-description="${item.description || ''}">${item.name}</option>`).join('')}
            </select>
            <input type="hidden" name="item_name[]" class="item-name">
            <input type="text" name="description[]" class="form-control item-description" placeholder="Description (Optional)" style="margin-top: 4px; font-size: 0.85rem;">
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity[]" class="form-control quantity" value="1" min="1" step="0.01" onchange="calculateTotals()">
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Price</label>
            <input type="number" name="price[]" class="form-control price" value="0" step="0.01" onchange="calculateTotals()">
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Tax %</label>
            <input type="number" name="tax_rate[]" class="form-control tax-rate" value="0" step="0.01" onchange="calculateTotals()">
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Total</label>
            <input type="text" class="form-control item-total" readonly value="₹ 0.00">
        </div>
        <button type="button" class="btn btn-danger" style="padding: 10px 14px;" onclick="removeItem(${itemCounter})">
            <i class="fas fa-trash"></i>
        </button>
    `;

        container.appendChild(itemDiv);
        itemCounter++;
    }

    function selectItem(select, index) {
        const option = select.options[select.selectedIndex];
        const itemDiv = document.getElementById(`item-${index}`);

        if (option.value) {
            itemDiv.querySelector('.price').value = option.dataset.price || 0;
            itemDiv.querySelector('.tax-rate').value = option.dataset.tax || 0;
            itemDiv.querySelector('.item-name').value = option.text;
            itemDiv.querySelector('.item-description').value = option.dataset.description || '';
        }

        calculateTotals();
    }

    function removeItem(index) {
        document.getElementById(`item-${index}`).remove();
        calculateTotals();
    }

    function calculateTotals() {
        let subtotal = 0;
        let taxTotal = 0;

        document.querySelectorAll('.invoice-item').forEach(item => {
            const quantity = parseFloat(item.querySelector('.quantity').value) || 0;
            const price = parseFloat(item.querySelector('.price').value) || 0;
            const taxRate = parseFloat(item.querySelector('.tax-rate').value) || 0;

            const lineTotal = quantity * price;
            const taxAmount = (lineTotal * taxRate) / 100;
            const total = lineTotal + taxAmount;

            item.querySelector('.item-total').value = `<?php echo $currency_symbol; ?> ${total.toFixed(2)}`;

            subtotal += lineTotal;
            taxTotal += taxAmount;
        });

        const grandTotal = subtotal + taxTotal;

        document.getElementById('subtotal').textContent = `<?php echo $currency_symbol; ?> ${subtotal.toFixed(2)}`;
        document.getElementById('tax').textContent = `<?php echo $currency_symbol; ?> ${taxTotal.toFixed(2)}`;
        document.getElementById('grandTotal').textContent = `<?php echo $currency_symbol; ?> ${grandTotal.toFixed(2)}`;
    }

    // Add first item by default
    addInvoiceItem();
</script>