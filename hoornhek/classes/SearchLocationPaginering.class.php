<?php
$locatie_id = isset($_GET['locatie_id']) ? $_GET['locatie_id'] : null;

class SearchLocationPagination {
    public static function generatePagination($total_rows, $records_per_page, $page_url, $page, $search_query, $locatie_id) {
        $total_pages = ceil($total_rows / $records_per_page);

        $pagination = '<div class="search-location-pagination">';

        // Previous page
        if ($page > 1) {
            $prev_page = $page - 1;
            $pagination .= "<a href='{$page_url}?search={$search_query}&locatie_id={$locatie_id}&page={$prev_page}'>&laquo;</a>";
        }

        // Numbered pages
        for ($i = 1; $i <= $total_pages; $i++) {
            $pagination .= "<a href='{$page_url}?search={$search_query}&locatie_id={$locatie_id}&page={$i}'";
            if ($i == $page) $pagination .= " class='curPage'";
            $pagination .= ">" . $i . "</a>";
        }

        // Next page
        if ($page < $total_pages) {
            $next_page = $page + 1;
            $pagination .= "<a href='{$page_url}?search={$search_query}&locatie_id={$locatie_id}&page={$next_page}'>&raquo;</a>";
        }
        $pagination .= '</div>';

        return $pagination;
    }
}

?>
