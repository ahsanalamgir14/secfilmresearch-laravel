<div class="bg-light p-5 rounded">
    <h1>Alert</h1>
        <div class="alert alert-success" role="alert">
        Hi {{ $user->name }},
        <br>
        We wanted to remind you that you didn't watch the video yesterday.
        <br>
        It would be great if you could watch it today.
        <br>
        Thank you!
        <br>
        here is the link:
        <a href="url">{{ config('dental.videoLink') }}</a>
</div>
