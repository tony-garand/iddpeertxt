<?php

namespace peertxt\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    public function parent_company()
    {
        return $this->belongsTo(Company::class, 'parent_company_id');
    }
    public function child_companies()
    {
        return $this->hasMany(Company::class, 'parent_company_id');
    }

    public function getIds()
    {
        $ids = [];
        $ids[] = $this->id;
        if($this->child_companies){
            $ids = array_merge($ids, $this->child_companies->pluck('id')->toArray());
        }
        return $ids;
    }

    public static function getCompanyIds($company_id)
    {
        $company = Company::with('child_companies')->where('id', $company_id)->first();
        if($company){
            return $company->getIds();
        }
        return [];
    }
}
