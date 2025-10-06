<span class="empty-slip-message">
    <span class="d-flex justify-content-center align-items-center">
        <img src="{{ getImage('assets/images/empty_list.png') }}" alt="image">
    </span>
    {{ __($message) }}
</span>

@push('style')
    <style>
        .empty-slip-message {
            display: grid !important;
            place-content: center;
            height: 30vh;
            color: #cfcfcf;
            font-size: 0.8754rem !important;
            font-family: inherit;
        }

        .empty-slip-message img {
            width: 75px !important;
            margin-bottom: 0.875rem;
        }
    </style>
@endpush
