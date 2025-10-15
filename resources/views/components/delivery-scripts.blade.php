   <script {{ $attributes }}>
        $(function () {
            window.dt = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{!! request()->fullUrl() !!}",
                language: {
                    loadingRecords: '&nbsp;',
                    processing: '<div class="spinner spinner-primary spinner-lg mr-15"></div> Processing...'
                },
                columns: [
                    {
                        data: 'created_at', name: 'created_at',
                        render: function (data, type, row, meta) {
                            return moment(data).format('DD-MM-YYYY HH:mm');
                        }
                    },
                    {data: 'order.order_number', name: 'order.order_number'},
                    {data: 'order.customer.name', name: 'order.customer.name'},
                    {data: 'items_count', name: 'items_count', orderable: false, searchable: false},
                    {
                        data: 'order.total_amount',
                        name: 'order.total_amount',
                        render: function (data, type, row, meta) {
                            return Number(data).toLocaleString("en-US", {
                                style: 'currency',
                                currency: 'RWF'
                            })
                        }
                    },
                    {
                        data: 'status', name: 'status',
                        render: function (data, type, row, meta) {
                            return `<span class="badge bg-${row.status_color}-subtle text-${row.status_color} rounded-pill">${data}</span>`
                        }
                    },
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            $(document).on('click', '.js-details', function (e) {
                e.preventDefault();
                let $btn = $(this);
                const url = $btn.data('url');
                let htmlValue = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
                $btn.prop('disabled', true)
                    .html(htmlValue);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (data) {
                        $('#results').html(data);
                        $('#myModal').modal('show');
                    },
                    complete: function () {
                        $btn.prop('disabled', false)
                            .html('Details');
                    }
                });

            });

            $(document).on('submit','#updateDeliveryStatusForm',function (e) {
                e.preventDefault();
                let $form = $(this);
                let $submitBtn = $form.find('button[type="submit"]');
                let originalBtnText = $submitBtn.html();

                // Disable button and show loader
                $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

                $.ajax({
                    url: $form.attr('action'),
                    type: $form.attr('method'),
                    data: $form.serialize(),
                    dataType: 'json', // Expect a JSON response from the server
                    success: function (response) {
                        // Use SweetAlert for a better user experience
                        Swal.fire({
                            text: response.message || "Delivery status updated successfully!",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                $('#myModal').modal('hide'); // Hide the modal on success
                                window.dt.ajax.reload(null, false); // Reload the datatable without resetting pagination
                            }
                        });
                    },
                    error: function (xhr) {
                        // Default error message
                        let errorMessage = "An unexpected error occurred. Please try again.";
                        // Check for a custom error message from the server
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            text: errorMessage,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-danger"
                            }
                        });
                    },
                    complete: function () {
                        // Re-enable the button and restore its original text
                        $submitBtn.prop('disabled', false).html(originalBtnText);
                    }
                });
            });


            $(document).on('click','.js-returns', function (e) {
                e.preventDefault();
                let $btn = $(this);
                const url = $btn.attr('href');
                let htmlValue = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
                $btn.prop('disabled', true)
                    .html(htmlValue);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (data) {
                        $('#delivery_results').html(data);
                        $('#returnModal').modal('show');
                    },
                    complete: function () {
                        $btn.prop('disabled', false)
                            .html('Details');
                    }
                });

            });
            $(document).on('input', '.js-item-returns', function () {
                var $this = $(this);
                const $row = $this.closest('tr');
                const $deliveredCell = $row.find('.js-delivered');

                // Get the original ordered quantity from the second column in the row
                const orderedQty = parseInt($row.find('td:nth-child(2)').text().trim(), 10);

                // Get the current value from the returns input
                let returnsQty = parseInt($this.val(), 10);

                // If the input is empty or not a number, treat it as 0
                if (isNaN(returnsQty)) {
                    returnsQty = 0;
                }

                // Calculate the new delivered quantity
                const newDeliveredQty = orderedQty - returnsQty;

                // Update the "Delivered" cell, ensuring it doesn't go below 0
                if (newDeliveredQty >= 0) {
                    $deliveredCell.text(newDeliveredQty);
                } else {
                    // If returns exceed ordered, cap delivered at 0
                    $deliveredCell.text(0);
                }
            });
            $(document).on('submit','#processReturnForm',function (e) {
                e.preventDefault();
                let $form = $(this);
                let $submitBtn = $form.find('button[type="submit"]');
                let originalBtnText = $submitBtn.html();

                // Disable button and show loader
                $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');

                $.ajax({
                    url: $form.attr('action'),
                    type: 'POST', // Explicitly set to POST for form submission
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        Swal.fire({
                            text: response.message || "Return processed successfully!",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                $('#returnModal').modal('hide'); // Hide the return modal
                                window.dt.ajax.reload(null, false); // Reload the datatable
                            }
                        });
                    },
                    error: function (xhr) {
                        let errorMessage = "An unexpected error occurred. Please try again.";
                        // Check for a custom error message from the server (e.g., validation errors)
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            text: errorMessage,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-danger"
                            }
                        });
                    },
                    complete: function () {
                        // Re-enable the button and restore its original text
                        $submitBtn.prop('disabled', false).html(originalBtnText);
                    }
                });
            });
        });
    </script>
