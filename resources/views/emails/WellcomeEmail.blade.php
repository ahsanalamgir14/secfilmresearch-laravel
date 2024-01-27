<div class="bg-light p-5 rounded">
    <h1>Hello {{ $user->name }}</h1>
        <div class="alert alert-success" role="alert">
            Your registration has been successful.
            <br>
            Welcome to our research program. to watch the film, please log in at
            <br>
            Here is the link:
            <a href="url">{{ config('dental.videoLink') }}</a>
            Your User Identifier Number: {{ $user->participant->code }}

        </div>
    <br>
</div>
