<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        // $this->middleware('checkPermission:rolemgmt');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('role.index');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rolemodel = new Role();
        $role = $rolemodel->postCreateRole();
        return $this->jsonOutput($role);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rolemodel = new Role();
        $role = $rolemodel->findOrFail($id);
        return $this->jsonOutput($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rolemodel = new Role();
        $role = $rolemodel->postUpdateRole($id);
        return $this->jsonOutput($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function dataPaging()
    {
        $rolemodel = new Role();
        $roles = $rolemodel->getDataPaging();
        return $this->jsonOutput($roles);
    }
    
    public function dataList()
    {
        $rolemodel = new Role();
        $result = $rolemodel->getDataList();
        return $this->jsonOutput($result);
    }
    
    public function menu()
    {   
        $rolemodel = new Role();
        $roles = $rolemodel->getDataList();
        return view('role.menu')->with(compact('roles'));
    }
    
    public function updateMenu($id)
    {
        $rolemodel = new Role();
        $result = $rolemodel->updateMenu($id);
        return $this->jsonOutput($result);
        
    }
    
    
}
