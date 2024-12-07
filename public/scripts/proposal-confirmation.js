$(document).ready(function() {
    $('#buyNowBtn').click(function(e) {
        e.preventDefault();

        $.ajax({
            url: "{{ url('buy-now') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                enqId: "{{ $enqId }}",
                user_id: user_data.id
            },
            success: function(response) {
                if (response.status === "success") {
                    // Redirect to confirmation or the specified return link
                    window.location.href = "{{ url('confirmation') }}";
                } else {
                    $('#errorMessage').html(response.message);
                    $('#errorModal').modal('show');
                }
            },
            error: function(xhr) {
                var errorMsg = "An unexpected error occurred. Please try again.";

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                
                $('#errorMessage').html(errorMsg);
                $('#errorModal').modal('show');
            }
        });
    });
});
