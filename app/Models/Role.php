<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use App\Libraries\jqGrid;
use App\Libraries\Datatable;

class Role extends BaseModel
{
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
    }

    protected $table = 'Role';
    protected $primaryKey = 'ID';
    protected $guarded = [];
    const CREATED_AT = 'CreatedDate';
    const UPDATED_AT = 'LastModifiedDate';

    public function menu()
    {
        return $this->belongsToMany('App\Models\Menu', 'RoleMenu', 'RoleID', 'MenuID')->withPivot('Actions');
    }
    
    public function tkwilayah()
    {
        return $this->belongsToMany('App\Models\TingkatWilayah', 'RoleTkWilayah', 'RoleID', 'TkWilayahID')->orderBy('ID','ASC');
    }

    public function getDataPaging()
    {
        $collection = $this;
        $data = new DataTable($collection, ['searchFields'=>['RoleName']]);
        $roles = $data->get();
        return $roles;
    }

    public function getDataList()
    {
        $roles = $this->select('ID', 'RoleName')->orderBy('ID')->get();
        return $roles;
    }

    public function postCreateRole()
    {
        $request = request()->all();
        $vrules = [
            'RoleName' => 'required|unique:'.$this->table,
        ];

        $vmessages = [
            'RoleName.required' => 'Nama Hak Akses harus diisi.',
            'RoleName.unique' => 'Nama Hak Akses sudah ada.',
        ];
        try {
            $validator = Validator::make($request, $vrules, $vmessages);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                $this->jsonResponse->message = $error;
                return $this->jsonResponse->get();
            }
            $request['ID'] = \DB::raw("nextval('\"Roles_ID_seq\"')");
            $request['CreatedBy'] = currentUser('UserName');
            $request['LastModifiedBy'] = currentUser('UserName');
            $role = $this->create($request);

            $this->jsonResponse->status = true;
            $this->jsonResponse->message = 'Data berhasil disimpan';
        } catch (\Exception $e) {
            $this->jsonResponse->message = getExceptionMessage($e);
        }
        return $this->jsonResponse->get();
    }

    public function postUpdateRole($id)
    {
        $request = request()->all();
        $vrules = [
            'RoleName' => 'required|unique:'.$this->table.',RoleName,'.$id.','.$this->primaryKey,
        ];

        $vmessages = [
            'RoleName.required' => 'Nama Hak Akses harus diisi.',
            'RoleName.unique' => 'Nama Hak Akses sudah ada.',
        ];

        try {
            $validator = Validator::make($request, $vrules, $vmessages);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                $this->jsonResponse->message = $error;
                return $this->jsonResponse->get();
            }
            $request['LastModifiedBy'] = currentUser('UserName');
            $user = $this->find($id);
            $user->fill($request);
            $row = $user->save();

            $this->jsonResponse->status = true;
            $this->jsonResponse->message = 'Data berhasil disimpan';
        } catch (\Exception $e) {
            $this->jsonResponse->message = getExceptionMessage($e);
        }
        return $this->jsonResponse->get();
    }
    
    public function getRoleByID($id)
    {
        $roles = $this->select('ID', 'RoleName')->where('ID', '>', $id)->orderBy('ID')->get();
        return $roles;
    }
    
    public function getRoleByLevel($lvl)
    {
        $roles = $this->select('ID', 'RoleName')->where('Level', '>', $lvl)->orderBy('Level')->get();
        return $roles;
    }
    
    public function updateMenu($id)
    {
        $checked = request()->input('checked');
        $remid = \DB::table('RoleMenu')->select('ID')->where('RoleID', $id)->whereNotIn('MenuID', $checked)->pluck('ID');
        \DB::table('RoleMenu')->whereIN('ID', $remid)->delete();
        foreach ($checked as $menuid) {
            if ($menuid!=0) {
                $exist =  \DB::table('RoleMenu')->where(['RoleID'=>$id, 'MenuID'=>$menuid])->first();
                if ($exist) {
                    \DB::table('RoleMenu')->where('ID', $exist->ID)->update([
                        'LastModifiedBy' => currentUser('UserName'),
                        'LastModified' => date('Y-m-d H:i:s'),
                    ]);
                } else {
                    \DB::table('RoleMenu')->insert([
                        'ID' => \DB::table('RoleMenu')->max('ID')+1,
                        'RoleID' => $id,
                        'MenuID' => $menuid,
                        'CreatedBy' => currentUser('UserName'),
                        'CreatedDate' => date('Y-m-d H:i:s'),
                        'LastModifiedBy' => currentUser('UserName'),
                        'LastModified' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }
        $this->jsonResponse->status = true;
        $this->jsonResponse->message = 'Data berhasil disimpan';
        return $this->jsonResponse->get();
    }
    
}
