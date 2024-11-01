<?php
class Pagination {
    public static function generatePagination($total_rows, $records_per_page, $page_url, $page) {
        $total_pages = ceil($total_rows / $records_per_page);

        $pagination = '<div class="pagination">';

        // Vorige pagina
        if ($page > 1) {
            $prev_page = $page - 1;
            $pagination .= "<a href='{$page_url}?page={$prev_page}'>&laquo;</a>";
        }

        // Alle pagina's in nummers
        for ($i = 1; $i <= $total_pages; $i++) {
            $pagination .= "<a href='{$page_url}?page=" . $i . "'";
            if ($i == $page) $pagination .= " class='curPage'";
            $pagination .= ">" . $i . "</a>";
        }

        // Volgende pagina
        if ($page < $total_pages) {
            $next_page = $page + 1;
            $pagination .= "<a href='{$page_url}?page={$next_page}'>&raquo;</a>";
        }

        $pagination .= '</div>';

        return $pagination;
    }
}

?>
