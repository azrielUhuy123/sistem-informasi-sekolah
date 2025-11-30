<?php

if (!function_exists('sortLink')) {
    function sortLink($column, $label) {
        $isActive = request('sort') == $column;
        $nextOrder = request('order') == 'asc' ? 'desc' : 'asc';
        $arrow = $isActive ? (request('order') == 'asc' ? '▲' : '▼') : '';

        $query = array_merge(request()->all(), [
            'sort' => $column,
            'order' => $nextOrder
        ]);

        return '<a href="?'.http_build_query($query).'" class="text-white text-decoration-none">'
                .$label.' '.$arrow.'</a>';
    }
}
