<x-mail::message>

  <p>Hello,</p>

  <p>You have been assigned a new task:</p>

  <h3>{{ $task->title }}</h3>
  <p>{{ $task->description }}</p>

  <p>Due Date: {{ $task->due_date }}</p>

  Thanks,<br>
  {{ config('app.name') }}
</x-mail::message>
