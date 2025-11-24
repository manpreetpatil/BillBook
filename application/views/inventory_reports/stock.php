<div class="card">
    <div class="card-header">
        <div class="card-title">Stock Report</div>
        <div>
            <button class="btn btn-outline" onclick="window.print()">
                <i class="fas fa-print" style="margin-right: 8px;"></i> Print
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th class="text-right">Current Stock</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Stock Value</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)): ?>
                    <?php
                    $total_value = 0;
                    foreach ($items as $item):
                        $stock_value = $item->current_stock * $item->price;
                        $total_value += $stock_value;
                        ?>
                        <tr>
                            <td><?php echo $item->name; ?></td>
                            <td class="text-right"><?php echo number_format($item->current_stock, 2); ?></td>
                            <td class="text-right"><?php echo $currency_symbol; ?>         <?php echo number_format($item->price, 2); ?>
                            </td>
                            <td class="text-right"><?php echo $currency_symbol; ?>         <?php echo number_format($stock_value, 2); ?>
                            </td>
                            <td>
                                <?php if ($item->current_stock <= $item->low_stock_alert): ?>
                                    <span class="badge badge-danger">Low Stock</span>
                                <?php else: ?>
                                    <span class="badge badge-success">In Stock</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No items found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right" style="font-weight: 700;">Total Stock Value</td>
                    <td class="text-right" style="font-weight: 700;"><?php echo $currency_symbol; ?>
                        <?php echo number_format($total_value, 2); ?></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>