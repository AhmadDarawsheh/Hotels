<x-mail::message>

Dear {{ $employee->name  ?? 'Admin' }},

A new reservation has been made:

- **Reservation ID**: {{ $reservation->id }}
- **Customer Name**: {{ $user->name }}
- **Room**: {{ $reservation->room->name }}
- **Start Date**: {{ $reservation->start_date }}
- **End Date**: {{ $reservation->end_date}}

Please review the details.


Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
