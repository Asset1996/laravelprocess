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
        $currentPage = $obj->currentPage();
        $path_list = $obj->linkCollection()->toArray();

        foreach($path_list as $key=>$path){
            if($path['label'] == 'pagination.previous'){
                $path_list[$key]['label'] = 'Назад';
                $path_list[$key]['page'] = $path['url'] !== null ? $currentPage - 1 : null;
            }elseif($path['label'] == 'pagination.next'){
                $path_list[$key]['label'] = 'Вперед';
                $path_list[$key]['page'] = $path['url'] !== null ? $currentPage + 1 : null;
            }else{
                $path_list[$key]['page'] = $path['label'];
            }
        }
        return [
            'has_pages' => (bool) $obj->hasPages(),
            'total' => $obj->total(),
            'per_page' => $obj->perPage(),
            'current_page' => $currentPage,
            'current_count' => $obj->count(),
            'path_list' => $path_list
        ];;
    }
}
?>