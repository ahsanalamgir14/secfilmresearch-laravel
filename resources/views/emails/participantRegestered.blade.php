<div class="bg-light p-5 rounded">
    <h1>New Registration</h1>
        <div class="alert alert-success" role="alert">
            A new participant has been registered for treatment.
        </div>
    Name: {{ $user->name }}
    <br>
    Email: {{ $user->email }}
    <br>
    Identifier given by system: {{ $user->participant->code }}
    <br>
    Age Group: {{ $user->participant->age_group }}
    <br>
    Gender: {{ $user->participant->gender }}
    <br>
    Score: {{ $user->participant->score }}
    <br>
</div>
