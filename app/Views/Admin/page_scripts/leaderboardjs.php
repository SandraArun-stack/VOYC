<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#leaderboardList').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: "<?= base_url('admin/leaderboard/ajaxList'); ?>",
        type: "POST"
    },
    columns: [
        { data: "sl_no" },
        { data: "date" },
        { data: "game_name" },
        { data: "winners" },
        { data: "turns" },
        { data: "action", orderable: false }
    ]
});


    // Delete Action
    $(document).on('click', '.delete', function() {
        if (!confirm("Delete this record?")) return;

        $.post("<?= base_url('admin/leaderboard/delete'); ?>", { id: $(this).data("id") }, function(response) {
            table.ajax.reload();
        }, 'json');
    });

    // Block Action
    $(document).on('click', '.block', function() {
        $.post("<?= base_url('admin/leaderboard/block'); ?>", { id: $(this).data("id") }, function(response) {
            table.ajax.reload();
        }, 'json');
    });
});


    // Delete
    $(document).on('click', '.delete', function() {
        if (!confirm("Delete this record?")) return;

        $.post("<?= base_url('admin/leaderboard/delete'); ?>", {
            id: $(this).data("id")
        }, function(response) {
            table.ajax.reload();
        }, 'json');
    });

    // Block
    $(document).on('click', '.block', function() {
        $.post("<?= base_url('admin/leaderboard/block'); ?>", {
            id: $(this).data("id")
        }, function(response) {
            table.ajax.reload();
        }, 'json');
    });
});
</script>
