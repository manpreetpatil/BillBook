<div class="card">
    <div class="card-header">
        <div class="card-title">Expenses</div>
        <div style="display: flex; gap: 12px;">
            <a href="<?php echo site_url('expenses/categories'); ?>" class="btn btn-outline">
                <i class="fas fa-tags" style="margin-right: 8px;"></i> Categories
            </a>
            <a href="<?php echo site_url('expenses/create'); ?>" class="btn btn-primary">
                <i class="fas fa-plus" style="margin-right: 8px;"></i> Record Expense
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div style="background-color: #f8fafc; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
        <form action="<?php echo site_url('expenses'); ?>" method="get"
            style="display: flex; gap: 16px; align-items: end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-control">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat->id; ?>" <?php echo ($filters['category_id'] == $cat->id) ? 'selected' : ''; ?>>
                            <?php echo $cat->name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="flex: 1; min-width: 150px;">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="<?php echo $filters['start_date']; ?>">
            </div>
            <div style="flex: 1; min-width: 150px;">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" value="<?php echo $filters['end_date']; ?>">
            </div>
            <button type="submit" class="btn btn-primary" style="height: 42px;">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="<?php echo site_url('expenses'); ?>" class="btn btn-outline" style="height: 42px;">Reset</a>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Reference</th>
                    <th class="text-right">Amount</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($expenses)): ?>
                    <?php foreach ($expenses as $expense): ?>
                        <tr>
                            <td><?php echo date('d M Y', strtotime($expense->expense_date)); ?></td>
                            <td><span class="badge badge-info"><?php echo $expense->category_name; ?></span></td>
                            <td><?php echo $expense->description; ?></td>
                            <td><?php echo $expense->reference_no; ?></td>
                            <td class="text-right" style="font-weight: 600;">
                                <?php echo $currency_symbol; ?>         <?php echo number_format($expense->amount, 2); ?>
                            </td>
                            <td class="text-right">
                                <a href="<?php echo site_url('expenses/delete/' . $expense->id); ?>" class="btn btn-danger"
                                    style="padding: 4px 8px; font-size: 0.8rem;"
                                    onclick="return confirm('Are you sure you want to delete this expense?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No expenses found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>