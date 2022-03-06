<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>@lang('default.invoice')</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <style type="text/css">
        html {
            overflow: auto;
            cursor: default;
            background: #fff;
            font: 16px/1 'Open Sans', sans-serif;
        }

        body {
            margin: 0;
            color: #000;
            background: #FFF;
            box-sizing: border-box;
        }

        header {
            position: relative;
        }

        header:after {
            clear: both;
            content: "";
            display: table;
        }

        header h1 {
            font-size: 40px;
            background: #fff;
            text-transform: uppercase;
        }

        table {
            font-size: 85%;
            border-spacing: 0;
            border-collapse: collapse;
        }

        table.inventory,
        table.balance {
            background-color: #f9f9f9;
        }

        table.inventory th {
            color: #fff;
            background: #000;
        }

        th, td {
            text-align: left;
        }

        table.balance {
            width: 30%;
            float: right;
        }

        table.balance:after {
            clear: both;
            content: "";
        }

        table.balance th,
        table.balance td {
            background-color: #f9f9f9
        }

        table.balance td {
            text-align: right;
        }

        table.inventory {
            width: 100%;
            clear: both;
        }

        table.inventory th {
            font-size: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<header>
    <div class="company-info" style="width: 50%; float: left;">
        <h1 style="margin-bottom: 0;">@lang('default.invoice')</h1>
        <p style="margin-bottom: 0;">{{$companyName}}</p>
        <p>{{$companyAddress}}</p>
    </div>
    <div class="company-logo" style="max-width: 200px; float: right;">
        <?php $image_path = public_path() . '/uploads/invoice/' . $invoiceLogo;  ?>
        <img src="{{$image_path }}" style="width: 100%; max-width: 300px; height: auto;" alt="">
    </div>
</header>

<main>
    <section style="margin-bottom: 25px;">
        <address contenteditable style="width: 50%; float: left; font-style: normal; margin-bottom: 10px;">
            <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 5px;">@lang('default.invoice_to')</h3>
            <span style="font-size: 16px; margin-bottom: 5px;">{{ $name }}</span> <br>
            <span style="font-size: 16px; margin-bottom: 0;">{{ $email }}</span> <br>
            <span style="font-size: 16px; margin-bottom: 0;">{{ $phone }}</span> <br>
        </address>

        <div style="width: 50%; float: left;">
            <p style="text-align: right;">
                <span style="font-weight: bold; font-size: 15px;">@lang('default.invoice_id'):</span>
                <span style="font-weight: lighter; font-size: 14px;">{{ $invoiceId }}</span>
            </p>
            <p style="text-align: right;">
                <span style="font-weight: bold; font-size: 15px;">@lang('default.date'):</span>
                <span style="font-weight: lighter; font-size: 14px;">{{ $invoiceCreatedAt }}</span>
            </p>
        </div>
    </section>

    <section>
        <div class="invoice-table-wrapper" style="margin-top: 10px;">
            <table class="inventory">
                <thead style="border: 0;">
                <tr style="border: 0;">
                    <th style="text-align: left; border-right: 2px solid #fff; padding: 0 10px;" colspan="2">
                        <p>@lang('default.service')</p></th>
                    <th style="text-align: right; border-right: 2px solid #fff; padding: 0 10px;">
                        <p>@lang('default.unit_price')</p></th>
                    <th style="text-align: right; border-right: 2px solid #fff; padding: 0 10px;">
                        <p>@lang('default.quantity')</p></th>
                    <th style="text-align: right; padding: 0 10px;"><p>@lang('default.price')</p></th>
                </tr>
                </thead>
                <tbody>
                @foreach($invoiceItemData as $invoiceItem)
                    <tr>
                        <td style="font-size: 15px; padding: 0 10px;" colspan="2">
                            <p>
                                {{$invoiceItem->service_title}}
                                <br>
                                {{$invoiceItem->booking_date}} {{$invoiceItem->booking_time}}
                            </p>
                        </td>
                        <td style="font-size: 15px; text-align: right; padding: 5px 10px;">
                            <p>
                                {{$invoiceItem->unit_price}}
                            </p>
                        </td>
                        <td style="font-size: 15px; text-align: right; padding: 5px 10px;">
                            <p>
                                {{$invoiceItem->quantity}}
                            </p>
                        </td>
                        <td style="font-size: 15px; text-align: right; padding: 5px 10px;">
                            <p>
                                {{$invoiceItem->total}}
                            </p>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <table class="balance">
                <tr>
                    <th style="text-align: left; font-size: 15px; padding: 5px 10px;">
                        <span contenteditable>@lang('default.total')</span>
                    </th>
                    <td style="text-align: right; font-size: 15px; padding: 5px 10px;">
                        <span>{{ $total }}</span>
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left; font-size: 15px; padding: 5px 10px;">
                        <span contenteditable>@lang('default.amount_paid')</span>
                    </th>
                    <td style="text-align: right; font-size: 15px; padding: 5px 10px;">
                        <span>{{ $paid }}</span>
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left; font-size: 15px; padding: 5px 10px;">
                        <span contenteditable>@lang('default.balance_due')</span>
                    </th>
                    <td style="text-align: right; font-size: 15px; padding: 5px 10px;">
                        <span>{{ $due }}</span>
                    </td>
                </tr>
            </table>
        </div>
    </section>
</main>
</body>
</html>