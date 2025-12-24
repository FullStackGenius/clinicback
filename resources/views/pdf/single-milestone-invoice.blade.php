<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Milestone Invoice</title>

    <style>
        @page {
            size: A4;
            margin: 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .header-left {
            display: table-cell;
            width: 60%;
        }

        .header-right {
            display: table-cell;
            width: 40%;
            text-align: right;
        }

        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #1f2937;
        }

        .invoice-title {
            font-size: 20px;
            font-weight: bold;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 8px;
            border: 1px solid #ddd;
        }

        th {
            background: #f3f4f6;
            font-weight: bold;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .total-table td {
            border: none;
            padding: 6px;
        }

        .total-table .label {
            text-align: right;
            font-weight: bold;
        }

        .grand-total {
            font-size: 16px;
            font-weight: bold;
            color: #111827;
        }

        .footer {
            position: fixed;
            bottom: 30px;
            left: 30px;
            right: 30px;
            font-size: 10px;
            text-align: center;
            color: #666;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header">
        <div class="header-left">
            <div class="company-name"> {{ config('app.name') }}</div>
            <div>Professional Payment Invoice</div>
        </div>

        <div class="header-right">
            <div class="invoice-title">INVOICE</div>
            <div>Transaction ID: <strong> {{ $milestonePaymentDetail->transfer_id }}</strong></div>
            <div>Date: {{ $milestonePaymentDetail->created_at }}</div>
        </div>
    </div>

    <!-- PROJECT & MILESTONE -->
    <div class="section">
        <div class="section-title">Project & Milestone Details</div>

        <table>
            <tr>
                <th>Project Name</th>
                <td> {{ ucfirst($milestonePaymentDetail->milestone->contract->project->title) }}</td>
            </tr>
            <tr>
                <th>Milestone Name</th>
                <td> {{ ucfirst($milestonePaymentDetail->milestone->title) }}</td>
            </tr>
            <tr>
                <th>Milestone Description</th>
                <td> {{ ucfirst($milestonePaymentDetail->milestone->description) }} </td>
            </tr>
        </table>
    </div>

    <!-- PARTIES -->
    <div class="section">
        <div class="section-title">Payment Parties</div>

        <table>
            <tr>
                <th>Client Name</th>
                <td>{{ ucfirst($milestonePaymentDetail->milestone->contract->client->name) }}
                    {{ $milestonePaymentDetail->milestone->contract->client->last_name }}</td>
                <th>Freelancer Name</th>
                <td> {{ ucfirst($milestonePaymentDetail->milestone->contract->freelancer->name) }}
                    {{ $milestonePaymentDetail->milestone->contract->freelancer->last_name }}</td>
            </tr>
            <tr>
                <th>Transferred By</th>
                <td> {{ ucfirst($milestonePaymentDetail->milestone->contract->client->name) }}
                    {{ $milestonePaymentDetail->milestone->contract->client->last_name }}</td>
                <th>Transferred To</th>
                <td> {{ ucfirst($milestonePaymentDetail->milestone->contract->freelancer->name) }}
                    {{ $milestonePaymentDetail->milestone->contract->freelancer->last_name }}</td>
            </tr>
        </table>
    </div>

    <!-- PAYMENT DETAILS -->
    <div class="section">
        <div class="section-title">Payment Breakdown</div>

        <table>
            <tr>
                <th>Currency</th>
                <td> {{ strtoupper($milestonePaymentDetail->currency) }}</td>
                <th>Milestone Amount</th>
                <td class="text-right"> {{ $milestonePaymentDetail->actual_milestone_amount }}</td>
            </tr>
            <tr>
                <th>Platform Fee</th>
                <td class="text-right"> {{ strtoupper($milestonePaymentDetail->currency) }}
                    {{ $milestonePaymentDetail->platform_fee_charges }}</td>
                <th>Actual Amount</th>
                <td class="text-right"> {{ strtoupper($milestonePaymentDetail->currency) }}
                    {{ $milestonePaymentDetail->amount }}</td>
            </tr>
        </table>
    </div>

    <!-- TOTAL -->
    <div class="section">
        <table class="total-table" width="100%">
            <tr>
                <td width="70%"></td>
                <td class="label">Total Amount</td>
                <td class="text-right grand-total">
                    {{ strtoupper($milestonePaymentDetail->currency) }}
                    {{ $milestonePaymentDetail->actual_milestone_amount }}
                </td>
            </tr>
        </table>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        © {{ date('Y') }} {{ config('app.name') }} — This is a system generated invoice.
    </div>

</body>

</html>
