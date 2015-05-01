<?php

use Jenssegers\Model\Model;

class ModelFillableStub extends Model
{
    protected $fillable = ['a', 'b', 'c', 'fooBar', 'ooBarFay', 'sampleProp', 'amplePropsay'];

    // d is not fillable

    protected $guarded = ['e'];

    protected $remap = [
        'ooBarFay' => 'fooBar',
        'amplePropsay' => 'sampleProp',
    ];
}
