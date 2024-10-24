// Function to get checked values from checkboxes
function getCheckedValues(selector) {
    return $(selector)
        .filter(":checked")
        .map(function () {
            return this.value;
        })
        .get(); // Returns an array of checked values
}

// Function to fetch premiums based on selected options
function fetchPremiums() {
    const sumInsuredValues = getCheckedValues(
        'input[type="checkbox"][id^="sumInsured"]'
    );
    const tenureValues = getCheckedValues(
        'input[type="checkbox"][id^="tenure"]'
    );

    // Clear previous insurer items
    $("#insurer-items").empty();

    // Loop over each provider
    activeProviders.forEach((provider) => {
        // For each provider, loop over each selected sum insured value
        sumInsuredValues.forEach((sumInsured) => {
            // For each provider, loop over each selected tenure value
            tenureValues.forEach((tenure) => {
                $.ajax({
                    url: `/premium/${provider.provider_id}`,
                    method: "POST",
                    data: {
                        sum_insured: sumInsured,
                        tenure: tenure,
                        user_data: user_data,
                    },
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    success: function (response) {
                        if (!$.isEmptyObject(response)) {
                            const insurerItem = `
                                    <div class="col-md-12 mb-4 insurer-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="col-md-4 text-center">
                                                <img src="/images/${response.insurer_logo}" class="img-fluid" alt="${response.insurer_name}">
                                                <p class="mb-0">${response.insurer_name}</p>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <p class="sum-insured mb-0">Rs. ${response.sum_insured}</p>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <button class="btn btn-primary btn-custom go-to-proposal" 
                                                    data-provider-id="${provider.provider_id}" 
                                                    data-tenure="${response.tenure}" 
                                                    data-net-premium="${response.net_premium}" 
                                                    data-gst="${response.gst}" 
                                                    data-total-premium="${response.total_premium}" 
                                                    data-sum-insured="${sumInsured}" 
                                                    type="button">
                                                    Rs. ${response.net_premium} for ${response.tenure} year(s)
                                                </button>
                                            </div>
                                        </div>
                                    </div>`;
                            $("#insurer-items").append(insurerItem);
                        }
                    },
                    error: function (xhr) {
                        console.error(
                            `Error fetching data for provider ID ${provider.provider_id} with sum insured ${sumInsured}`,
                            xhr
                        );
                    },
                });
            });
        });
    });

    // Event listener for dynamic "go-to-proposal" buttons
    $(document).on("click", ".go-to-proposal", function () {
        const providerId = $(this).data("provider-id");
        const tenure = $(this).data("tenure");
        const sumInsured = $(this).data("sum-insured");
        const netPremium = $(this).data("net-premium");
        const gst = $(this).data("gst");
        const totalPremium = $(this).data("total-premium");

        const urlParams = new URLSearchParams(window.location.search);
        const enqId = urlParams.get('enqId');
        console.log(enqId);

        // Navigate to the proposal route with the parameters
        window.location.href = `/proposal/${enqId}?provider_id=${providerId}&tenure=${tenure}&sum_insured=${sumInsured}&net_premium=${netPremium}&gst=${gst}&total_premium=${totalPremium}`;
    });
}

// Initial fetch of premiums with default checked values
$(document).ready(function () {
    fetchPremiums();
    
    // Fetch premiums again whenever a checkbox is selected
    $('input[type="checkbox"]').on("change", function () {
        fetchPremiums();
    });
});
