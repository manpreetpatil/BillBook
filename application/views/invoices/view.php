<div class="card">
    <div class="card-header">
        <div class="card-title">Invoice <?php echo $invoice->invoice_number; ?></div>
        <div style="display: flex; gap: 8px;">
            <div style="display: flex; gap: 12px;">
                <button onclick="shareInvoice(<?php echo $invoice->id; ?>)" class="btn btn-outline">
                    <i class="fas fa-share-alt" style="margin-right: 8px;"></i> Share
                </button>
                <a href="<?php echo site_url('invoices/print_thermal/' . $invoice->id); ?>" target="_blank"
                    class="btn btn-outline">
                    <i class="fas fa-receipt" style="margin-right: 8px;"></i> Thermal Print
                </a>
                <a href="<?php echo site_url('invoices/print_invoice/' . $invoice->id); ?>" target="_blank"
                    class="btn btn-primary">
                    <i class="fas fa-print" style="margin-right: 8px;"></i> Print A4
                </a>
            </div>
            <a href="<?php echo site_url('payments/add/' . $invoice->id); ?>" class="btn btn-primary">
                <i class="fas fa-money-bill-wave" style="margin-right: 8px;"></i> Add Payment
            </a>
            <a href="<?php echo site_url('invoices'); ?>" class="btn btn-outline">
                <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Back
            </a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
        <div>
            <h3 style="margin-bottom: 12px;">Customer Details</h3>
            <div style="background-color: #f8fafc; padding: 16px; border-radius: 8px;">
                <div style="margin-bottom: 8px;"><strong><?php echo $invoice->customer_name; ?></strong></div>
                <div style="color: var(--text-light); font-size: 0.9rem;">
                    <?php if ($invoice->email): ?>
                        <div><i class="fas fa-envelope" style="width: 20px;"></i> <?php echo $invoice->email; ?></div>
                    <?php endif; ?>
                    <?php if ($invoice->phone): ?>
                        <div><i class="fas fa-phone" style="width: 20px;"></i> <?php echo $invoice->phone; ?></div>
                    <?php endif; ?>
                    <?php if ($invoice->address): ?>
                        <div><i class="fas fa-map-marker-alt" style="width: 20px;"></i>
                            <?php echo nl2br($invoice->address); ?></div>
                    <?php endif; ?>
                    <?php if ($invoice->gstin): ?>
                        <div><i class="fas fa-file-alt" style="width: 20px;"></i> GSTIN: <?php echo $invoice->gstin; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div>
            <h3 style="margin-bottom: 12px;">Invoice Details</h3>
            <div style="background-color: #f8fafc; padding: 16px; border-radius: 8px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <div style="color: var(--text-light); font-size: 0.85rem;">Invoice Date</div>
                        <div style="font-weight: 600;"><?php echo date('d M Y', strtotime($invoice->invoice_date)); ?>
                        </div>
                    </div>
                    <div>
                        <div style="color: var(--text-light); font-size: 0.85rem;">Due Date</div>
                        <div style="font-weight: 600;">
                            <?php echo $invoice->due_date ? date('d M Y', strtotime($invoice->due_date)) : 'N/A'; ?>
                        </div>
                    </div>
                    <div>
                        <div style="color: var(--text-light); font-size: 0.85rem;">Status</div>
                        <div>
                            <?php
                            $badge_class = 'badge-info';
                            if ($invoice->status == 'Paid')
                                $badge_class = 'badge-success';
                            elseif ($invoice->status == 'Cancelled')
                                $badge_class = 'badge-danger';
                            elseif ($invoice->status == 'Partial')
                                $badge_class = 'badge-warning';
                            ?>
                            <span class="badge <?php echo $badge_class; ?>"><?php echo $invoice->status; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h3 style="margin-bottom: 12px;">Items</h3>
    <div class="table-container" style="margin-bottom: 24px;">
        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Tax Rate</th>
                    <th>Tax Amount</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoice_items as $item): ?>
                    <tr>
                        <td>
                            <div><?php echo $item->item_name; ?></div>
                            <?php if (!empty($item->description)): ?>
                                <div class="text-muted small"><?php echo $item->description; ?></div>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $item->quantity; ?></td>
                        <td><?php echo $currency_symbol; ?>     <?php echo number_format($item->price, 2); ?></td>
                        <td><?php echo $item->tax_rate; ?>%</td>
                        <td><?php echo $currency_symbol; ?>     <?php echo number_format($item->tax_amount, 2); ?></td>
                        <td class="text-right"><?php echo $currency_symbol; ?>     <?php echo number_format($item->total, 2); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div style="max-width: 400px; margin-left: auto; background-color: #f8fafc; padding: 20px; border-radius: 8px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
            <span>Subtotal:</span>
            <span><?php echo $currency_symbol; ?> <?php echo number_format($invoice->subtotal, 2); ?></span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
            <span>Tax:</span>
            <span><?php echo $currency_symbol; ?> <?php echo number_format($invoice->tax_total, 2); ?></span>
        </div>
        <div
            style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: 700; padding-top: 12px; border-top: 2px solid var(--border-color);">
            <span>Grand Total:</span>
            <span><?php echo $currency_symbol; ?> <?php echo number_format($invoice->grand_total, 2); ?></span>
        </div>
    </div>

    <?php if ($invoice->notes): ?>
        <div style="margin-top: 24px;">
            <h3 style="margin-bottom: 12px;">Notes</h3>
            <div style="background-color: #f8fafc; padding: 16px; border-radius: 8px;">
                <?php echo nl2br($invoice->notes); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($payments) && count($payments) > 0): ?>
        <div style="margin-top: 24px;">
            <h3 style="margin-bottom: 12px;">Payment History</h3>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Transaction ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_paid = 0;
                        foreach ($payments as $payment):
                            $total_paid += $payment->amount;
                            ?>
                            <tr>
                                <td><?php echo date('d M Y', strtotime($payment->payment_date)); ?></td>
                                <td><?php echo $currency_symbol; ?>         <?php echo number_format($payment->amount, 2); ?></td>
                                <td><?php echo $payment->payment_method; ?></td>
                                <td><?php echo $payment->transaction_id ?: 'N/A'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr style="font-weight: 700; background-color: #f8fafc;">
                            <td>Total Paid</td>
                            <td><?php echo $currency_symbol; ?>     <?php echo number_format($total_paid, 2); ?></td>
                            <td colspan="2">Balance: <?php echo $currency_symbol; ?>
                                <?php echo number_format($invoice->grand_total - $total_paid, 2); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Share Modal -->
<div id="shareModal" class="modal"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 1000;">
    <div style="background: white; padding: 24px; border-radius: 12px; width: 400px; max-width: 90%;">
        <h3 style="margin-top: 0;">Share Invoice</h3>

        <div style="margin-bottom: 16px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Public Link</label>
            <div style="display: flex; gap: 8px;">
                <input type="text" id="shareLink" readonly class="form-control" style="flex: 1;">
                <button onclick="copyLink()" class="btn btn-outline" title="Copy"><i class="fas fa-copy"></i></button>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 24px;">
            <a id="whatsappBtn" href="#" target="_blank" class="btn"
                style="background: #25D366; color: white; text-align: center;">
                <i class="fab fa-whatsapp"></i> WhatsApp
            </a>
            <a id="emailBtn" href="#" class="btn" style="background: #EA4335; color: white; text-align: center;">
                <i class="fas fa-envelope"></i> Email
            </a>
        </div>

        <div style="text-align: right;">
            <button onclick="closeShareModal()" class="btn btn-outline">Close</button>
        </div>
    </div>
</div>

<script>
    function shareInvoice(id) {
        fetch('<?php echo site_url("invoices/share/"); ?>' + id)
            .then(response => response.json())
            .then(data => {
                document.getElementById('shareLink').value = data.share_link;
                document.getElementById('whatsappBtn').href = data.whatsapp_link;
                document.getElementById('emailBtn').href = 'mailto:?subject=' + encodeURIComponent(data.email_subject) + '&body=' + encodeURIComponent(data.email_body);

                document.getElementById('shareModal').style.display = 'flex';
            });
    }

    function closeShareModal() {
        document.getElementById('shareModal').style.display = 'none';
    }

    function copyLink() {
        var copyText = document.getElementById("shareLink");
        copyText.select();
        document.execCommand("copy");
        alert("Link copied to clipboard!");
    }

    // Close modal on outside click
    window.onclick = function (event) {
        var modal = document.getElementById('shareModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>