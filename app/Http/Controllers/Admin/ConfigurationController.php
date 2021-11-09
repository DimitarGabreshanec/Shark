<?php

namespace App\Http\Controllers\Admin;
   
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;  
use App\Models\Configuration;
use App\Service\ConfigurationService;
use App\Http\Requests\Admin\ConfigurationRequest;
use Session;


class ConfigurationController extends AdminController
{
     
    public function edit(Request $request)
    { 
        $configuration = Configuration::orderByDesc('id')->first();   
        return view("admin.configuration.edit", [
            'configuration' => $configuration,
        ]);
    }

    public function update(ConfigurationRequest $request)
    {   
        $configuration = Configuration::orderByDesc('id')->first(); 
        if (ConfigurationService::doUpdate($configuration, $request->all())) {
            $request->session()->flash('success', '設定を更新しました。');
        } else {
            $request->session()->flash('success', '設定更新が失敗しました。');
        } 
        
        return redirect()->route('admin.configuration.edit', [
            'configuration' => $configuration,
        ]);
    }

}