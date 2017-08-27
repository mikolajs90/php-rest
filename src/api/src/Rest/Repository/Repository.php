<?php

namespace Rest\Repository;

use Symfony\Component\HttpFoundation\Request;

abstract class Repository
{

    protected function getQueryTake(Request $request)
    {
        return (int)$request->get('take', 10);
    }


    protected function getQuerySort(Request $request)
    {
        return array(
            'by' => htmlspecialchars($request->get('sort_by', 'id')),
            'order' => htmlspecialchars($request->get('sort_order', 'ascending')) == 'descending' ? 'DESC' : 'ASC'
        );
    }

}