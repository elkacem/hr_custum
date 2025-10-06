{{--<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">--}}
{{--    <h2 style="color: #28a745;">Payment Confirmation</h2>--}}

{{--    <p>Dear {{ $fullname }},</p>--}}

{{--    <p>--}}
{{--        We're delighted to inform you that your payment of--}}
{{--        <strong>{{ $amount }} {{ $currency }}</strong> via--}}
{{--        <strong>{{ $method_name }}</strong> has been completed successfully.--}}
{{--    </p>--}}


{{--    <p>--}}
{{--        <strong>Transaction ID:</strong> {{ $transaction_id }}<br>--}}
{{--        <strong>Date:</strong> {{ $time }}--}}
{{--    </p>--}}

{{--    <p style="margin-top: 20px;">--}}
{{--        You can download your invoice here:--}}
{{--    </p>--}}

{{--    <p>--}}
{{--        <a href="{{ $invoice_link }}"--}}
{{--           style="display: inline-block; padding: 10px 20px; background-color: #007bff;--}}
{{--                  color: white; text-decoration: none; border-radius: 5px;">--}}
{{--            Download Invoice--}}
{{--        </a>--}}
{{--    </p>--}}

{{--    <p style="margin-top: 30px;">--}}
{{--        Thank you for choosing <strong>{{ $site_name }}</strong>.<br>--}}
{{--        If you have any questions, feel free to contact our support.--}}
{{--    </p>--}}

{{--    <p style="color: #aaa; font-size: 12px; margin-top: 40px;">--}}
{{--        This is an automated message. Please do not reply.--}}
{{--    </p>--}}
{{--</div>--}}

<!-- invoice_paid.blade.php -->
<div style="font-family: Arial, sans-serif;">
    <h2>Payment Confirmation</h2>
    <p>Dear {{ $fullname }},</p>
    <p>Your payment of <strong>{{ $amount }} {{ $currency }}</strong> via <strong>{{ $method_name }}</strong> was successful.</p>
    <p><strong>Transaction ID:</strong> {{ $transaction_id }}</p>
    <p><a href="{{ $invoice_link }}">Download Invoice</a></p>
    <p>Thank you for using {{ $site_name }}.</p>
</div>
<p style="color: #aaa; font-size: 12px; margin-top: 40px;">
    This is an automated message. Please do not reply.
</p>

