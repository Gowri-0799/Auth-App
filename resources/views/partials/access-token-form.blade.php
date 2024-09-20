<h3>Generate Access Token</h3>

@if(session('accessToken'))
    <p>Generated Access Token: <strong>{{ session('accessToken') }}</strong></p>
@endif

<!-- Form to generate access token -->
<form id="accessTokenForm" method="POST">
    @csrf
    <button type="submit" class="btn btn-primary">Generate Access Token</button>
</form>

<!-- Display response message here -->
<div id="tokenResponse"></div>

<script>
    // Attach an event listener to the form submission
    $('#accessTokenForm').submit(function (e) {
        e.preventDefault(); // Prevent default form submission

        // Send the AJAX request
        $.ajax({
            url: "{{ route('generate.access.token') }}", // The route for generating token
            method: "POST",
            data: $(this).serialize(), // Send form data
            success: function (response) {
                // Display the generated access token
                $('#tokenResponse').html('<p>Generated Access Token: <strong>' + response + '</strong></p>');
            },
            error: function (xhr, status, error) {
                // Handle errors and display error message
                $('#tokenResponse').html('<p>Error generating access token. Please try again.</p>');
            }
        });
    });
</script>
