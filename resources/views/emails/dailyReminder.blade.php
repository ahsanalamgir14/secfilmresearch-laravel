<div class="bg-light p-5 rounded">
    <h1>Daily Reminder</h1>
        <div class="alert alert-success" role="alert">
            Hi {{ $user->name }}:
            <br>
            Dont't forget to watch the video for today.
            <br>
            here is the link:
            <a href="url">{{ config('dental.videoLink') }}</a>
        </div>
</div>
