<script>
$(document).ready(function() {
    // alert('hello');
    const itemsPerPage = 16;
    const $cards = $(".product__card");
    const totalItems = $cards.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const $pagination = $(".pagination__option");

    function showPage(page) {
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;

        $cards.hide().slice(start, end).fadeIn(400);

        $pagination.find("a").removeClass("active");
        $pagination.find(`[data-page="${page}"]`).addClass("active");
    }

    // Generate pagination links
    for (let i = 1; i <= totalPages; i++) {
        $pagination.append(`<a href="#" data-page="${i}">${i}</a>`);
    }

    // Add next button if needed
    if (totalPages > 1) {
        $pagination.append(`<a href="#" class="next"><i class="fa fa-angle-right"></i></a>`);
    }

    // Click event for page numbers
    $pagination.on("click", "a[data-page]", function(e) {
        e.preventDefault();
        const page = $(this).data("page");
        showPage(page);
    });

    // Click event for next button
    $pagination.on("click", ".next", function(e) {
        e.preventDefault();
        const currentPage = parseInt($pagination.find("a.active").data("page"));
        if (currentPage < totalPages) {
            showPage(currentPage + 1);
        }
    });

    // Initialize first page
    showPage(1);
});
</script>
