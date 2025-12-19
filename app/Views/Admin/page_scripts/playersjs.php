<script>
    $(document).ready(function () {
        var table = $('#playersTable').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            responsive: true,
            scrollX: false,

            ajax: {
                url: "<?= base_url('admin/players/ajaxList'); ?>",
                type: "POST"
            },

            columns: [
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                    searchable: false
                },
                { data: 'player_created_at' },
                { data: 'customer_name' },
                {
                    data: 'game_name',
                    render: function (data) {
                        if (!data) return '';
                        return data
                            .toLowerCase()
                            .replace(/\b\w/g, function (char) { return char.toUpperCase(); });
                    }
                },

                { data: 'player_score' },
                {
                    data: 'player_winning_status',
                    render: function (status) {

                        if (!status) return `<span class="player_status badge bg-dark ">Unknown</span>`;

                        switch (status.toString().toLowerCase()) {

                            case 'queued':
                            case '0':
                                return `<span class="player_status badge bg-secondary">Queued</span>`;

                            case 'winner':
                            case '1':
                                return `<span class="player_status badge bg-warning">Winner</span>`;

                            case 'losser':
                            case 'loser':
                            case '2':
                                return `<span class="player_status badge bg-danger">Loser</span>`;

                            default:
                                return `<span class="player_status badge bg-dark">Unknown</span>`;
                        }
                    }
                }

            ],

            columnDefs: [
                { targets: [5], orderable: false, searchable: false }
            ]
        });

    });

</script>