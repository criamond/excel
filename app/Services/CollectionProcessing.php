<?php

namespace App\Services;

class CollectionProcessing
{
    protected $collection;
    public function setCollection($collection)
    {
        $this->collection=$collection;
    }
    public function process()
    {
        $collection=$this->collection->map(function($collection)
        {
            $collection['unixtime']=strtotime($collection[2]);
            return $collection;
        });

        $collection=$collection->sortByDesc('unixtime');

        $i=0;
        foreach ($collection as $elem){
            if ($elem->get(0) && $elem->get(1)) {
                $arr_out[0][$i] = $elem->get(0);
                $arr_out[1][$i] = $elem->get(1);
            }
            $i++;
        }
        return $arr_out;
    }

}
