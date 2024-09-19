<x-mail::message>

Dear {{ $user->name }},

Thank you for making a reservation. Here are the details:

- **Reservation ID**: {{ $reservation->id }}
- **Room**: {{ $reservation->room->name }}
- **Start Date**: {{ $reservation->start_date }}
- **End Date**: {{ $reservation->end_date }}

Thank you for choosing us!

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
