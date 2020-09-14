<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Menu extends BaseModel
{   
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
    }
    
    protected $table = 'Menu';
    protected $primaryKey = 'ID';
    protected $fillable = ['ID', 'MenuName', 'URL', 'ParentID', 'CreatedBy', 'LastModifiedBy'];
    
    
    public function getDataList()
    {
        $array = $this->orderBy('Sequence')->get();
        // debug($array[0]);exit;
        $treemenu = [];
        $this->treeArrayMenu($treemenu, $array);
        
        $root = [];
        $root['id'] = '0';
        $root['text'] = 'Root';
        // $root['icon'] = 'far fa-folder kt-font-info';
        $root['type'] = 'sub';
        $root['state'] = ['opened'=>true];
        $root['children'] = $treemenu;
        return $root;
    }
    
    public function treeArrayMenu(&$return, $array, $pid=0, $level=0)
    {   
        if (is_array($array)) $array = collect($array);
        $filtered = $array->filter(function ($value, $key) use($pid) {
            if ($value->ParentID==$pid) return $value;
        });
        $haschildren = (count($filtered) > 0) ? true : false;
        
        foreach ($filtered as $item) {
            $node = [];
            $node['id'] = $item->ID;
            $node['text'] = $item->MenuName;
            $nextlevel = $level+1;
            $this->treeArrayMenu($node['children'], $array, $item->ID, $nextlevel);
            // $node['icon'] = empty($node['children']) ? 'far fa-circle kt-font-info' : 'far fa-folder kt-font-info';
            $selected = (empty($item->checked)) ? false : (empty($node['children']) ? true : false);
            $node['state'] = ['opened'=>true, 'selected'=>$selected];
            $node['type'] = empty($node['children']) ? 'default' : 'sub';
            $node['url'] = $item->URL;
            $node['checked'] = $item->checked;
            // $node['permission'] = $this->getPermissions($item->id);
            $node['sequence'] = $item->Sequence;
            // $node['is_active'] = $item->is_active;
            $return[]=$node;
        }
    }
    
    public function getByRole($id)
    {
        $sql = 'SELECT a.*, b."ID" as checked
FROM "Menu" a
LEFT JOIN "RoleMenu" b ON b."MenuID"=a."ID" AND b."RoleID"=?';
        $array = \DB::select($sql, [$id]);
        // debug($array[0]);exit;
        $treemenu = [];
        $this->treeArrayMenu($treemenu, $array);
        
        $root = [];
        $root['id'] = '0';
        $root['text'] = 'Root';
        // $root['icon'] = 'far fa-folder kt-font-info';
        $root['type'] = 'sub';
        $root['state'] = ['opened'=>true];
        $root['children'] = $treemenu;
        return $root;
    }
    
    public function postCreateMenu()
    {
        $request = request()->all();
        $vrules = [
            'name' => 'required',
            'route' => 'required',
        ];
        
        $vmessages = [
            'name.required' => 'Nama Menu harus diisi.',
            'route.required' => 'URL harus diisi.',
        ];
        try {
            $validator = Validator::make($request, $vrules, $vmessages);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                $this->jsonResponse->message = $error;
                return $this->jsonResponse->get();
            }
            $request['id'] = \DB::raw("nextval('menu_seq')");
            $request['parent_id'] = $request['parent_id'] ?? 0;
            $request['created_by'] = currentUser('UserName');
            $request['updated_by'] = currentUser('UserName');
            $menu = $this->create($request);
            $menu->insert = true;
            
            $this->jsonResponse->status = true;
            $this->jsonResponse->message = 'Data berhasil disimpan';
            $this->jsonResponse->data = $menu;
        } catch (\Exception $e) {
            $this->jsonResponse->message = getExceptionMessage($e);
        }
        return $this->jsonResponse->get();
    }
    
    public function postUpdateMenu($id)
    {
        $request = request()->all();
        $vrules = [
            'name' => 'required',
        ];
        
        $vmessages = [
            'name.required' => 'Nama Menu harus diisi.',
        ];
        
        try {
            $validator = Validator::make($request, $vrules, $vmessages);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                $this->jsonResponse->message = $error;
                return $this->jsonResponse->get();
            }
            $request['updated_by'] = currentUser('UserName');
            $user = $this->find($id);
            $user->fill($request);
            $menu = $user->save();

            $this->jsonResponse->status = true;
            $this->jsonResponse->message = 'Data berhasil disimpan';
            $this->jsonResponse->data = $menu;
        } catch (\Exception $e) {
            $this->jsonResponse->message = getExceptionMessage($e);
        }
        return $this->jsonResponse->get();
    }
    
    public function postDeleteMenu($id)
    {
        try {
            $menu = $this->find($id);
            if (is_null($menu)) {
                $this->jsonResponse->message = 'Data tidak ditemukan';
            } else {
                $menu->delete();
                $this->jsonResponse->status = true;
                $this->jsonResponse->message = 'Data berhasil dihapus';
            }
        } catch (\Exception $e) {
            $this->jsonResponse->message = getExceptionMessage($e);
        }
        return $this->jsonResponse->get();
    }
    
    public function getTopNav($roleid=0)
    {   
        $roleid = empty($roleid) ? 0 : $roleid;
        $menu = $this->where('IsPublic','=',1)->get();
        if ($roleid > 0) {
            $role = \App\Models\Role::find($roleid);
            if ($role) {
                $menurole = $role->menu;
                $menu = $menu->merge($menurole);
                // $menu = $menu->unique();

            }
        }
        $treemenu = '';
        $this->topArrayMenu($treemenu, $menu);
        request()->session()->put('usermenu', $treemenu);
        return $treemenu;
    }
    
    public function topArrayMenu(&$return, $array, $pid=0, $level=0)
    {   
        $filtered = $array->filter(function ($value, $key) use($pid) {
            if ($value->ParentID==$pid) return $value;
        });
        foreach ($filtered->sortBy('Sequence') as $item) {
            
            $nextlevel = $level+1;
            $children = '';
            $this->topArrayMenu($children, $array, $item->ID, $nextlevel);
            $url = (!empty($children)) ? 'javascript:;' : url($item->URL.'?mid='.$item->ID);
            
            $li = '';
            if (empty($children)) {
                $li .='<li class="kt-menu__item " aria-haspopup="true"><a href="'.$url.'" class="kt-menu__link "><span></span></i><span class="kt-menu__link-text">'.$item->MenuName.'</span></a></li>';
            } else {
                $li .= '<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">';
                $li .= '<a href="'.$url.'" class="kt-menu__link kt-menu__toggle">';
                $li .= '<span class="kt-menu__link-text">'.$item->MenuName.'</span>';
                $li .= '<i class="kt-menu__hor-arrow la la-angle-right"></i>';
                $li .= '<i class="kt-menu__ver-arrow la la-angle-right"></i>';
                $li .= '</a>';
                $align = $level==0?'left':'right';
                $li .= '<div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--'.$align.'">';
                $li .= '<ul class="kt-menu__subnav">';
                $li .= $children;
                $li .= '</ul>';
                $li .= '</div>';
                $li .= '</li>';
            }
            $return.=$li;
        }
    }
    
    public function getSideNav($roleid=0)
    {   
        $roleid = empty($roleid) ? 0 : $roleid;
        $menu = $this->where('IsPublic','=',1)->get();
        if ($roleid > 0) {
            $role = \App\Models\Role::find($roleid);
            if ($role) {
                $menurole = $role->menu;
                $menu = $menu->merge($menurole);
            }
        }
        $treemenu = '';
        $this->sideArrayMenu($treemenu, $menu);
        // request()->session()->put('usermenu', $treemenu);
        return $treemenu;
    }
    
    public function sideArrayMenu(&$return, $array, $pid=0, $level=0)
    {   
        $filtered = $array->filter(function ($value, $key) use($pid) {
            if ($value->ParentID==$pid) return $value;
        });
        foreach ($filtered->sortBy('Sequence') as $item) {
            
            $nextlevel = $level+1;
            $children = '';
            $this->sideArrayMenu($children, $array, $item->ID, $nextlevel);
            $url = (!empty($children)) ? 'javascript:;' : url($item->URL.'?mid='.$item->ID);
            
            $li = '';
            if (empty($children)) {
                $li .= '<li class="kt-menu__item " aria-haspopup="true"><a href="'.$url.'" class="kt-menu__link "><i class="kt-menu__link-icon flaticon-home"></i><span class="kt-menu__link-text">'.$item->MenuName.'</span></a></li>';
            } else {
                $li .= '<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-web"></i><span class="kt-menu__link-text">'.$item->MenuName.'</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>';
                
                $li .= '<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>';
                $li .= '<ul class="kt-menu__subnav">';
                $li .= '<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">'.$item->MenuName.'</span></span></li>';
                $li .= $children;
                $li .= '</ul>';
                
                $li .= '</div>';
                
                $li .= '</li>';
            }
            $return.=$li;
        }
    }
    
    
}
