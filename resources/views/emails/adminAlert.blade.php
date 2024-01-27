<div class="bg-light p-5 rounded">
    <h1>Alert</h1>
        <div class="alert alert-success" role="alert">
        Hi
        The participant {{ $participant->code }} hasn't watched the video for 48 hours.
        <br>
        This is his current information.
        <br>
        Identifier given by system: {{ $participant->code }}
        <br>
        Age Group: {{ $participant->age_group }}
        <br>
        Gender: {{ $participant->gender }}
        <br>
        Score: {{ $participant->score }}
        <br>
</div>
