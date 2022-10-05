<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'type',
        'value',
        'alias'
    ];

    public static function getByAlias($alias) {
		$config = Config::query()->where('alias','LIKE',$alias)->first();
		return $config;
	}

	public static function getValue($alias) {
		$item = Config::getByAlias($alias);
		if ($item)
			return $item->get('Value');
		
		return null;
	}
}
