$(document).ready(function () {
    $('#downloadPolicyBtn').click(function (e) {
        e.preventDefault();

        var button = $(this);
        var defaultText = '<i class="fas fa-download me-2"></i> Download Policy PDF';
        var loadingText = '<i class="fas fa-spinner fa-spin"></i> Policy PDF Downloading...';
        button.html(loadingText).prop('disabled', true);

        $.ajax({
            url: `/download-policy/${enqId}`,
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success === true) {
                    window.open(response.url, '_blank');
                } else {
                    $('#errorMessage').html(response.message || "An unexpected error occurred.");
                    $('#errorModal').modal('show');
                }
            },
            error: function (xhr) {
                var errorMsg = "An unexpected error occurred. Please try again.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                $('#errorMessage').html(errorMsg);
                $('#errorModal').modal('show');
            },
            complete: function () {
                button.html(defaultText).prop('disabled', false);
            }
        });
    });
});
