<?php

class Pagination
{
    public static function createPaginationLinks($currentPage, $totalPages)
    {
        $links = '';

        if ($currentPage > 1) {
            $links .= "<a href='?page=1' class='page-btn'>First</a>";
        }

        for ($i = 1; $i <= $totalPages; $i++) {
            $class = ($i == $currentPage) ? 'active' : '';
            $links .= "<a href='?page=$i' class='page-btn $class'>$i</a>";
        }

        if ($currentPage < $totalPages) {
            $links .= "<a href='?page=$totalPages' class='page-btn'>Last</a>";
        }

        return $links;
    }
}
