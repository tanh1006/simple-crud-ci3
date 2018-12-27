<html>
    <head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
        <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
    </head>
    <body>
        <table id="customer-table">
            <thead>
                <th>Name</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Update</th>
                <th>Delete</th>
            </thead>
        </table>

        <div id="form-modal" class="modal">
            <input type="hidden" id="customer-id" />
            <input id="customer-name" type="text" placeholder="Name" />
            <input id="customer-email" type="email" placeholder="Email" />
            <button type="submit">Save</button>
        </div>

        <!-- Link to open the modal -->
        <a id="create-link" href="#form-modal" rel="modal:open">New</a>
    
        <script type="text/javascript">
            $(document).ready(function () {
                var table = $('#customer-table');
                var ajaxSettings = {
                    iDisplayLength: 10,
                    serverSide: true,
                    bFilter: false,
                    "order": [[ 2, "desc" ]],
                    "aoColumnDefs": [
                        { 'bSortable': false, 'aTargets': [ 3, 4 ] }
                    ],
                    ajax: {
                        url: '<?php echo base_url('customer/all') ?>',
                        type: "GET"
                    },
                    columns: [
                        {data: "name"},
                        {data: "email"},
                        {data: "created_at"},
                        {data: "update_col"},
                        {data: "delete_col"},
                    ]
                };
                
                var initTable = function () {
                    if(table.fnDestroy !== 'undefined') {
                        table.fnDestroy();
                        table.dataTable(ajaxSettings);
                    }
                };

                var resetModal = function () {
                    $('#form-modal').find('input').each(function () {
                        $(this).val('');
                    });
                };

                $('#create-link').on('click', function () {
                    resetModal();
                });

                if(table.length) {
                    table.dataTable(ajaxSettings);
                }

                $(document).on('click', '.update-link', function (e) {
                    var link = $(this);
                    $('#customer-id').val(link.data('id'));
                    $('#customer-name').val(link.data('name'));
                    $('#customer-email').val(link.data('email'));
                });

                $(document).on('click', '.delete-link', function (e) {
                    e.preventDefault();

                    if(!confirm('Are you sure?')) {
                        return;
                    }

                    var id = $(this).data('id');

                    $.ajax({
                        url: '<?php echo base_url('customer/remove') ?>',
                        method: 'POST',
                        data: {
                            id : id
                        },
                        success: function (result) {
                            result = JSON.parse(result);

                            if(result.error) {
                                alert(result.msg);
                                return;
                            }

                            initTable();
                            alert('Success');
                        }
                    });
                });

                $('#form-modal button[type="submit"]').on('click', function () {
                    $.ajax({
                        url: '<?php echo base_url('customer/store') ?>',
                        method: 'POST',
                        data: {
                            id: $('#customer-id').val(),
                            name: $('#customer-name').val(),
                            email: $('#customer-email').val()
                        },
                        success: function (result) {
                            result = JSON.parse(result);

                            if(result.error) {
                                alert(result.msg);
                                return;
                            }

                            initTable();
                            alert('Success');
                        }
                    });
                })
            });
        </script>
    </body>
</html>