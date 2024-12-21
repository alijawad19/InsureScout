$(document).ready(function() {
    $('#buyNowBtn').click(function(e) {
        e.preventDefault();

        $.ajax({
            url: `/generate-proposal/${providerId}`,
            method: "POST",
            data: {
                enqId: enqId,
                sum_insured: sumInsured,
                tenure: tenure,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function(response) {
                if (response.payment_url) {
                    window.location.href = response.payment_url;
                } else {
                    $('#errorMessage').html("Payment URL is missing in the response.");
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
