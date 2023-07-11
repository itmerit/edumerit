<?php

namespace Modules\MenuManage\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\SidebarDataStore;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Cache;
use Modules\MenuManage\Entities\Sidebar;
use Modules\RolePermission\Entities\Permission;
use Modules\MenuManage\Entities\PermissionSection;
use Modules\RolePermission\Entities\AssignPermission;
use Modules\MenuManage\Http\Requests\SectionRequestFrom;

class SidebarManagerController extends Controller
{
    use SidebarDataStore;
    public function sectionStore(SectionRequestFrom $request)
    {     
     
        if (config('app.app_sync')) {
            Toastr::error('Restricted in demo mode');
            return back();
        }
        $permission_position = Permission::whereNotNull('permission_section')->where('user_id', auth()->user()->id)->latest()->first();
        try{
            $permission = Permission::create([
                'name' => $request->name,
                'route'=>strtolower($request->name),
                'position' => $permission_position->id + 1,
                'user_id' => auth()->user()->id,
                'permission_section'=>1,
                'type'=>1,
                'is_menu' => 1,
                'status' => 1,
                'menu_status' => 1,
                'is_admin'=>!in_array(auth()->user()->role_id, [2,3]) ? 1 : 0,
                'is_student'=>auth()->user()->role_id == 2 ? 1 : 0,
                'is_parent'=>auth()->user()->role_id == 3 ? 1 : 0,
            ]);
            Sidebar::create([
                'permission_id' => $permission->id,
                'user_id' => auth()->id(),
                'role_id' => auth()->user()->role_id,
                'position' => $permission_position->id + 1,
                'active_status' => 1,
                'parent'=>NULL
            ]);
            Cache::forget('sidebars'.auth()->user()->id);
            
            Toastr::success('Operation successful', 'Success');
            return redirect()->route('menumanage.index');
        }catch(\Exception $e){

        }
    }
    public function sectionEditForm($id)
    {
        if (config('app.app_sync')) {
            Toastr::error('Restricted in demo mode');
            return back();
        }
        $data = [];
        $data['unused_menus'] = self::unUsedMenu(); 
        Cache::forget('sidebars'.auth()->user()->id);             
        $data['sidebar_menus'] = sidebar_menus();
        $data['editPermissionSection'] = Permission::where('user_id', auth()->user()->id)->where('id', $id)->first();
        return view('menumanage::index', $data);
    }

    public function sectionUpdate(SectionRequestFrom $request)
    {        
        $request->validate([
            'id' => 'required'
        ]);
        $section = Permission::find($request->id);
        $section->name = $request->name;
        $section->save();
                 
        Toastr::success('Operation successful', 'Success');
        return redirect()->route('menumanage.index');
    }
    public function deleteSection(Request $request)
    {
        //   uest->all());
       
        if(config('app.app_sync')) {
            return $this->reloadWithData();
        }
      
        try {
            if ($request->id != 1) {
                $section = Sidebar::where('id', $request->id)->where('user_id', auth()->user()->id)->first();
                if (count($section->subModule)!=0) {
                   
                    foreach ($section->subModule as $sidebar) {
                        $sidebar->update(['active_status' => 0, 'ignore'=>0]);                      
                    }
                }
               
                if($section->permissionInfo->permission_section == 1 && count($section->subModule)==0) {
                  
                    Permission::where('user_id', auth()->user()->id)->where('id', $section->permission_id)->delete();
                    $section->delete();
                }
                              
            }
            Cache::forget('sidebars'.auth()->user()->id);
            return $this->reloadWithData();
        } catch (\Exception $e) {
            return response()->json([
                'msg' => __('common.Operation failed')
            ], 500);
        }

    }

    public function removeMenu(Request $request)
    {
        $sidebar = Sidebar::with('userChildMenu')->where('id', $request->id)->where('user_id', auth()->user()->id)->first();
        if ($sidebar && !config('app.app_sync')) {
            if($sidebar->userChildMenu->count() > 0) {
                foreach($sidebar->userChildMenu as $child) {
                    $child->update(['active_status'=>0]);
                }
               
            }
            Sidebar::where('parent', $sidebar->permission_id)->update(['active_status'=>0]);
            $sidebar->active_status = 0;
            $sidebar->save();
        }
        Cache::forget('sidebars'.auth()->user()->id);
        return $this->reloadWithData();

    }


    private function orderMenu(array $menuItems,  $menu_status = 1, $parent_id = null, $un_used = null)
    { 
        
        foreach ($menuItems as $index => $item) {
            $menuItem = Sidebar::where('id', $item->id)
            ->when(!$un_used, function($q){
                $q->where('active_status', 1);
              })
            ->where('user_id', auth()->user()->id)->first();

            $data = [
                'position' => $index + 1,                  
                'parent'=>$parent_id,               
                'active_status' => $menu_status ?? 1,
            ];          
           
            if ($menuItem) {
                $menuItem->update($data);
                if (isset($item->children)) {
                    $this->orderMenu($item->children, $menu_status, $menuItem->permission_id, $un_used);
                }
            }

        }

    }


    public function menuUpdate(Request $request)
    {
      
        if (!config('app.app_sync')) {        
            $menuItemOrder = json_decode($request->get('order'));

            if ($request->unused_ids) {
                Sidebar::whereIn('id', $request->unused_ids)->update([
                    'active_status' => 0, 
                ]);
            }
            if ($request->ids) {
                Sidebar::whereIn('id', $request->ids)->update([
                    'active_status' => 1,
                ]);
            }
        
        }
        $this->orderMenu($menuItemOrder, $request->menu_status, $request->section, $request->un_used);        
      
        Cache::forget('sidebars'.auth()->user()->id);
        return $this->reloadWithData();
    }


    public function sortSection(Request $request)
    {
        
        if($request->ids && !config('app.app_sync')) {
            foreach($request->ids as $key=>$permissionSection) {
              
              $sidebar =  Sidebar::where('id', $permissionSection)->where('user_id', auth()->user()->id)->where('role_id', auth()->user()->role_id)
              ->where('active_status', 1)->first();
              if($sidebar){
                $sidebar->position = $key+1;
                $sidebar->save();
              }
             
            }
        }
        Cache::forget('sidebars'.auth()->user()->id); 
    }

    public function resetMenu()
    {        
        try {          
            Sidebar::where('user_id', auth()->user()->id)
            ->where('role_id', auth()->user()->role_id)
            ->delete();
            $this->resetSidebarStore();
            Cache::forget('sidebars'.auth()->user()->id);  
            return redirect()->back();
           
        } catch (\Exception $e) {
           ;
        }

    }
    public function resetWithDefault()
    {
        try {
                    
            Sidebar::where('user_id', auth()->user()->id)->where('role_id', auth()->user()->role_id)->delete();
            $this->defaultSidebarStore();  
            return redirect()->back();           
        } catch (\Exception $e) {
           ;
        }
    }

    private function reloadWithData()
    {

        $data = $this->getMenusData();
        return response()->json([
            'msg' => 'Success',
            'available_list' => (string)view('menumanage::components.available_list', $data),
            'menus' => (string)view('menumanage::components.components', $data),
            'live_preview' => (string)view('menumanage::components.live_preview', $data)
        ], 200);
    }
   
    public function getMenusData()
    {      
        $unused_menus = self::unUsedMenu();              
        $sidebar_menus = sidebar_menus();
        return compact('unused_menus', 'sidebar_menus');
    }
    public static function unUsedMenu()
    {
        $sectionIds = Sidebar::whereNull('parent')->pluck('permission_id')->toArray();
        $parentSidebars = Sidebar::whereIn('parent', $sectionIds)    
                        ->deActiveMenuUser()
                        ->pluck('permission_id')
                        ->toArray();
        $single = Sidebar::whereNotIn('parent', $parentSidebars)
                    ->deActiveMenuUser()
                    ->pluck('permission_id')
                    ->toArray();       
        $hasIds = array_merge($parentSidebars, $single);
       
        $hasIds = (array_unique($hasIds));
        if($hasIds) {
           return Sidebar::whereIn('permission_id', $hasIds)->deActiveMenuUser()->get();
        } 
        return collect();  
    }
}
