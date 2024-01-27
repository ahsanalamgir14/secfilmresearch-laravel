<div class="bg-light p-5 rounded">
    <h1>a prticipant finish his tratement</h1>
        <div class="alert alert-success" role="alert">
            The participant {{ $participant->code }} has successfully completed their treatment and responded to the questionnaire.
            <br>
            the link to access the outcome.
            <a href="url">{{ config('dental.resultLink') }}</a>
        </div>
</div>
