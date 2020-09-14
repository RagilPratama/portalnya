<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use App\Libraries\DataTable;

class TingkatWilayah extends BaseModel
{
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
    }

    protected $table = 'TingkatWilayah';
    protected $primaryKey = 'ID';
    protected $fillable = ['ID', 'TingkatWilayah'];
    const CREATED_AT = 'CreatedDate';
    const UPDATED_AT = 'LastModifiedDate';

    public function getDataList()
    {
        $rows = $this->select('ID', 'TingkatWilayah')->orderBy('ID')->get();
        return $rows;
    }

    public function getDataListByID()
    {
        $mapRoleTingkatWilayah = [
            2 => [1],
            3 => [3],
            4 => [4],
            5 => [5,6]
        ];
        $roles = Role::where('Level','>',currentUser('RoleLevel'))->orderBy('Level')->pluck('ID');
        $tk = [];
        foreach ($mapRoleTingkatWilayah as $idx=>$item) {
            foreach ($roles as $role) {
                if ($idx == $role) $tk = array_merge($tk,$item);
            }
        }
        $rows = $this->select('ID', 'TingkatWilayah')->whereIn('ID', $tk)->orderBy('ID')->get();
        return $rows;
    }

    public function getDataListByRoleID($id)
    {
        $mapRoleTingkatWilayah = [
            2 => [1],
            3 => [3],
            4 => [4],
            5 => [6],
            6 => [3]
        ];
        $tk = [];
        foreach ($mapRoleTingkatWilayah as $idx=>$item) {
            if ($idx == $id) $tk = array_merge($tk,$item);
        }
        $rows = $this->select('ID', 'TingkatWilayah')->whereIn('ID', $tk)->orderBy('ID')->get();
        return $rows;
    }

    public static function dataList()
    {
        $_self = new self();
        return $_self->getDataList();
    }
}
