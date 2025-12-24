<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Project Payment Details</title>
    {{-- <title>Contract Detail</title> --}}
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 13px;
            color: #333;
        }

        .container {
            width: 100%;
            padding: 20px;
        }

        .header {
            border-bottom: 2px solid #635bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #635bff;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            background: #f4f6fb;
            padding: 8px;
            font-weight: bold;
            border-left: 4px solid #635bff;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td {
            padding: 8px;
            border: 1px solid #e0e0e0;
        }

        table td.label {
            background: #fafafa;
            font-weight: bold;
            width: 30%;
        }

        .amount {
            font-size: 18px;
            font-weight: bold;
            color: #2e7d32;
        }

        .footer {
            text-align: center;
            font-size: 11px;
            color: #777;
            margin-top: 40px;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="header">
            {{-- <h1>Payment Confirmation</h1> --}}
            <h1>Contract Detail</h1>

            {{-- <p>{ company_name }</p> --}}
        </div>

        <div class="section">
            <div class="section-title">Project Information</div>
            <table>
                <tr>
                    <td class="label">Project Name</td>
                    <td>{{ $contract->project->title }}</td>
                </tr>
                <tr>
                    <td class="label">Project Description</td>
                    <td>{!! $contract->project->description !!}</td>
                </tr>
                {{-- <tr>
                <td class="label">Project ID</td>
                <td> {{ $contract->project->id }}</td>
            </tr>
            <tr>
                <td class="label">Contract ID</td>
                <td>{{ $contract->id }}</td>
            </tr> --}}
            </table>
        </div>

        <div class="section">
            <div class="section-title">Milestones</div>
            <table>
                <tr>
                    <td class="label">Sno.</td>
                    <td class="label">Tasks</td>
                    <td class="label">Milestone amount</td>
                    <td class="label">Description</td>
                </tr>
                @php $snomile = 1; @endphp
                @foreach ($milestones as $milestone)
                    <tr>
                        <td>{{ $snomile }}</td>
                        <td>{{ $milestone->title }}</td>
                        <td>${{ $milestone->amount }}</td>
                        <td>{{ $milestone->description }}</td>
                    </tr>
                    @php $snomile++; @endphp
                @endforeach
                {{-- <tr>
                <td class="label">Amount Paid</td>
                <td class="amount">{{ $contractPayment->amount  }}</td>
            </tr>
            <tr>
                <td class="label">Status</td>
                <td>{{ $contractPayment->status  }}</td>
            </tr>
            <tr>
                <td class="label">Paid On</td>
                <td>{{ $contractPayment->paid_at  }}</td>
            </tr> --}}
            </table>
        </div>

        <div class="section">
            <div class="section-title">Client Details</div>
            <table>
                <tr>
                    <td class="label">Client Name</td>
                    <td>{{ $contract->client->name }} {{ $contract->client->last_name }}</td>
                </tr>
                <tr>
                    <td class="label">Client Email</td>
                    <td>{{ $contract->client->email }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Freelancer Details</div>
            <table>
                <tr>
                    <td class="label">Freelancer Name</td>
                    <td>{{ $contract->freelancer->name }} {{ $contract->freelancer->last_name }}</td>
                </tr>
                <tr>
                    <td class="label">Freelancer Email</td>
                    <td>{{ $contract->freelancer->email }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Payment Details</div>
            <table>
                {{-- <tr>
                <td class="label">Payment ID</td>
                <td>{{ $contractPayment->id  }}</td>
            </tr> --}}
                <tr>
                    <td class="label">Stripe Payment Intent</td>
                    <td>{{ $contractPayment->payment_intent_id }}</td>
                </tr>
                <tr>
                    <td class="label">Amount Paid</td>
                    <td class="amount">{{ $contractPayment->amount }}</td>
                </tr>
                <tr>
                    <td class="label">Status</td>
                    <td>{{ $contractPayment->status }}</td>
                </tr>
                <tr>
                    <td class="label">Paid On</td>
                    <td>{{ $contractPayment->paid_at }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            This is a system-generated document. No signature required.<br>
            © {{ \Carbon\Carbon::now()->year }}
            {{ config('app.name') }}

        </div>

    </div>

</body>

</html>
