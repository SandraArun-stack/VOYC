<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Token & Discount Management</h5>
                        <p class="m-b-0">Welcome to VOYC</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('admin/dashboard'); ?>"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">Token&Discount management</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="pcoded-inner-content">
        <div class="card">
            <div class="card-block">

                <table class="table table-hover" id="tokenList">
                    <thead>
    <tr>
        <th>Sl.No.</th>

        <th>User Name</th>

        <th>
            Token Per Day 
            <span style="font-size: 11px; color: #6c757d;">(per day reset)</span>
        </th>

        <th>
            Bonus Token
            <span style="font-size: 11px; color: #6c757d;">(per day reset)</span>
        </th>

        <th>Purchased Token</th>

        <th>Action</th>
    </tr>
</thead>

                </table>

            </div>
        </div>
    </div>
</div>

<script>
$(function() {
    $('#tokenList').DataTable({
        ajax: '<?= base_url("admin/token/list") ?>'
    });
});
</script>
