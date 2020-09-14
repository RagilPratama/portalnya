<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index()
    {
        return view('menu.index');
    }
    
    public function store(Request $request)
    {
        $menumodel = new Menu();
        $role = $menumodel->postCreateMenu();
        return $this->jsonOutput($role);
    }
    
    public function update(Request $request, $id)
    {
        $menumodel = new Menu();
        $role = $menumodel->postUpdateMenu($id);
        return $this->jsonOutput($role);
    }
    
    public function destroy($id)
    {
        $menumodel = new Menu();
        $role = $menumodel->postDeleteMenu($id);
        return $this->jsonOutput($role);
    }
    
    public function dataList()
    {
        $menumodel = new Menu();
        $result = $menumodel->getDataList();
        return $this->jsonOutput($result);
    }
    
    public function role($id)
    {
        $menumodel = new Menu();
        $result = $menumodel->getByRole($id);
        return $this->jsonOutput($result);
        
    }
    
    
    
}
