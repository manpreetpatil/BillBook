<div class="card">
    <div class="card-header">
        <div class="card-title">GSTR-3B Summary</div>
        <a href="<?php echo site_url('reports/gst_report'); ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Back to GST Report
        </a>
    </div>

    <form method="get" action="<?php echo site_url('reports/gstr3b_report'); ?>" style="margin-bottom: 24px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 12px; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="<?php echo $start_date; ?>">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" value="<?php echo $end_date; ?>">
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter" style="margin-right: 8px;"></i> Filter
            </button>
        </div>
    </form>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h3 style="margin-bottom: 0;">3.1 Details of Outward Supplies and inward supplies liable to reverse charge</h3>
        <a href="<?php echo site_url('reports/gstr3b_report_csv?start_date=' . $start_date . '&end_date=' . $end_date); ?>"
            class="btn btn-primary btn-sm">
            <i class="fas fa-download" style="margin-right: 8px;"></i> Download CSV
        </a>
    </div>
    <div class="table-container" style="margin-bottom: 32px;">
        <table class="table">
            <thead>
                <tr>
                    <th>Nature of Supplies</th>
                    <th>Total Taxable Value</th>
                    <th>Integrated Tax</th>
                    <th>Central Tax</th>
                    <th>State/UT Tax</th>
                    <th>Cess</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>(a) Outward taxable supplies (other than zero rated, nil rated and exempted)</td>
                    <td><?php echo $currency_symbol; ?>
                        <?php echo number_format($outward_supplies->taxable_value, 2); ?>
                    </td>
                    <td><?php echo $currency_symbol; ?> <?php echo number_format($outward_supplies->igst_amount, 2); ?>
                    </td>
                    <td><?php echo $currency_symbol; ?> <?php echo number_format($outward_supplies->cgst_amount, 2); ?>
                    </td>
                    <td><?php echo $currency_symbol; ?> <?php echo number_format($outward_supplies->sgst_amount, 2); ?>
                    </td>
                    <td><?php echo $currency_symbol; ?> 0.00</td>
                </tr>
                <tr>
                    <td>(b) Outward taxable supplies (zero rated)</td>
                    <td><?php echo $currency_symbol; ?> 0.00</td>
                    <td><?php echo $currency_symbol; ?> 0.00</td>
                    <td>-</td>
                    <td>-</td>
                    <td><?php echo $currency_symbol; ?> 0.00</td>
                </tr>
                <tr>
                    <td>(c) Other outward supplies (Nil rated, exempted)</td>
                    <td><?php echo $currency_symbol; ?> 0.00</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>(d) Inward supplies (liable to reverse charge)</td>
                    <td><?php echo $currency_symbol; ?> 0.00</td>
                    <td><?php echo $currency_symbol; ?> 0.00</td>
                    <td><?php echo $currency_symbol; ?> 0.00</td>
                    <td><?php echo $currency_symbol; ?> 0.00</td>
                    <td><?php echo $currency_symbol; ?> 0.00</td>
                </tr>
                <tr>
                    <td>(e) Non-GST outward supplies</td>
                    <td><?php echo $currency_symbol; ?> 0.00</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>
    </div>

    <h3 style="margin-bottom: 16px;">4. Eligible ITC</h3>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Details</th>
                    <th>Integrated Tax</th>
                    <th>Central Tax</th>
                    <th>State/UT Tax</th>
                    <th>Cess</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>(A) ITC Available (whether in full or part)</td>
                    <td><?php echo $currency_symbol; ?> <?php echo number_format($itc->igst_amount, 2); ?></td>
                    <td><?php echo $currency_symbol; ?> <?php echo number_format($itc->cgst_amount, 2); ?></td>
                    <td><?php echo $currency_symbol; ?> <?php echo number_format($itc->sgst_amount, 2); ?></td>
                    <td><?php echo $currency_symbol; ?> <?php echo number_format($itc->cess_amount, 2); ?></td>
                </tr>
                <tr>
                    <td>(B) ITC Reversed</td>
                    <td><?php echo $currency_symbol; ?> 0.00</td>
                    <td><?php echo $currency_symbol; ?> 0.00</td>
                    <td><?php echo $currency_symbol; ?> 0.00</td>
                    <td><?php echo $currency_symbol; ?> 0.00</td>
                </tr>
                <tr>
                    <td>(C) Net ITC Available (A) - (B)</td>
                    <td><?php echo $currency_symbol; ?> <?php echo number_format($itc->igst_amount, 2); ?></td>
                    <td><?php echo $currency_symbol; ?> <?php echo number_format($itc->cgst_amount, 2); ?></td>
                    <td><?php echo $currency_symbol; ?> <?php echo number_format($itc->sgst_amount, 2); ?></td>
                    <td><?php echo $currency_symbol; ?> <?php echo number_format($itc->cess_amount, 2); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>