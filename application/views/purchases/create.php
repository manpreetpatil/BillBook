<div class="card">
    <div class="card-header">
        <div class="card-title">Create Purchase Order</div>
        <a href="<?php echo site_url('purchases'); ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Back
        </a>
    </div>

    <form action="<?php echo site_url('purchases/create'); ?>" method="post" id="purchaseForm">
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 24px;">
            <div class="form-group">
                <label class="form-label">Supplier *</label>
                <select name="supplier_id" class="form-control" required>
                    <option value="">Select Supplier</option>
                    <?php foreach ($suppliers as $supplier): ?>
                        <option value="<?php echo $supplier->id; ?>"><?php echo $supplier->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Purchase Date *</label>
                <input type="date" name="purchase_date" class="form-control" value="<?php echo date('Y-m-d'); ?>"
                    required>
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="Ordered">Ordered</option>
                    <option value="Received">Received (Updates Stock)</option>
                </select>
            </div>
        </div>

        <div style="background-color: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="margin: 0;">Items</h3>
                <button type="button" class="btn btn-primary" onclick="addPurchaseItem()">
                    <i class="fas fa-plus" style="margin-right: 8px;"></i> Add Item
                </button>
            </div>

            <div id="purchaseItems">
                <!-- Items will be added here -->
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="3"></textarea>
        </div>

        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save" style="margin-right: 8px;"></i> Create Purchase Order
            </button>
            <a href="<?php echo site_url('purchases'); ?>" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>

<script>
    const items = <?php echo json_encode($items); ?>;
    let itemCounter = 0;

    function addPurchaseItem() {
        const container = document.getElementById('purchaseItems');
        const itemDiv = document.createElement('div');
        itemDiv.className = 'purchase-item';
        itemDiv.style.cssText = 'display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto; gap: 12px; margin-bottom: 12px; align-items: end; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;';
        itemDiv.id = `item-${itemCounter}`;

        itemDiv.innerHTML = `
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Item</label>
            <select name="item_id[]" class="form-control item-select" required>
                <option value="">Select Item</option>
                ${items.map(item => `<option value="${item.id}">${item.name}</option>`).join('')}
            </select>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity[]" class="form-control" value="1" min="1" step="0.01" required>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Unit Price</label>
            <input type="number" name="unit_price[]" class="form-control" value="0" step="0.01" required>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Batch Number</label>
            <input type="text" name="batch_number[]" class="form-control" placeholder="Batch #">
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Expiry Date</label>
            <input type="date" name="expiry_date[]" class="form-control">
        </div>
        <button type="button" class="btn btn-danger" style="padding: 10px 14px;" onclick="removeItem(${itemCounter})">
            <i class="fas fa-trash"></i>
        </button>
    `;

        container.appendChild(itemDiv);
        itemCounter++;
    }

    function removeItem(index) {
        document.getElementById(`item-${index}`).remove();
    }

    // Add first item by default
    addPurchaseItem();
</script>