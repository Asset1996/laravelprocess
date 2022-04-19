<?php 
namespace App\Helpers;

class Paginator{

    /**
     * Возвраащает массив с данными о пагинации.
     *
     * @param LengthAwarePaginator $obj
     * @return array
     */
    public static function get(\Illuminate\Pagination\LengthAwarePaginator $obj):array
    {
        return [
            'has_pages' => (bool) $obj->hasPages(),
            'total' => $obj->total(),
            'per_page' => $obj->perPage(),
            'current_page' => $obj->currentPage(),
            'current_count' => $obj->count(),
            'path_list' => $obj->linkCollection()->toArray(),
        ];;
    }
}
?>