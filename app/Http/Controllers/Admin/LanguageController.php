<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class LanguageController extends Controller
{
    public function langManage($lang = false)
    {
        $pageTitle = 'Language Manager';
        $languages = Language::orderBy('is_default', 'desc')->get();
        return view('admin.language.lang', compact('pageTitle', 'languages'));
    }

    public function langEdit($id)
    {
        $lang = Language::findOrFail($id);
        $pageTitle = "Update " . $lang->name . " Keywords";
        $json = file_get_contents(resource_path('lang/') . $lang->code . '.json');
        $list_lang = Language::all();

        if (empty($json)) {
            $notify[] = ['error', 'File not found'];
            return back()->withNotify($notify);
        }

        $json = json_decode($json, true);

        $perPage = getPaginate(20);
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;

        $search = request()->search;
        if ($search) {
            $json = array_filter(
                $json,
                function ($key) use ($search) {
                    return stripos($key, $search) !== false;
                },
                ARRAY_FILTER_USE_KEY
            );
        }

        $items = array_slice($json, $offset, $perPage, true);

        $json = new LengthAwarePaginator(
            $items,
            count($json),
            $perPage,
            $currentPage,
            ['path' => url()->current()]
        );

        return view('admin.language.edit_lang', compact('pageTitle', 'json', 'lang', 'list_lang'));
    }

    public function langUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])]
        ]);

        $language = Language::findOrFail($id);

        if (!$request->is_default) {
            $defaultLang = Language::where('is_default', Status::YES)->where('id', '!=', $id)->exists();
            if (!$defaultLang) {
                $notify[] = ['error', 'You\'ve to set another language as default before unset this'];
                return back()->withNotify($notify);
            }
        }

        $language->name = $request->name;
        $language->is_default = $request->is_default ? Status::YES : Status::NO;
        if ($request->hasFile('image')) {
            try {
                $old = $language->image;
                $language->image = fileUploader($request->image, getFilePath('language'), getFileSize('language'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload language image'];
                return back()->withNotify($notify);
            }
        }
        $language->save();

        if ($request->is_default) {
            $lang = Language::where('is_default', Status::YES)->where('id', '!=', $language->id)->first();
            if ($lang) {
                $lang->is_default = Status::NO;
                $lang->save();
            }
        }

        $notify[] = ['success', 'Language updated successfully'];
        return back()->withNotify($notify);
    }

    public function storeLanguageJson(Request $request, $id)
    {
        $lang = Language::findOrFail($id);
        $request->validate([
            'key' => 'required',
            'value' => 'required'
        ]);

        $items = file_get_contents(resource_path('lang/') . $lang->code . '.json');

        $reqKey = trim($request->key);

        if (array_key_exists($reqKey, json_decode($items, true))) {
            $notify[] = ['error', "Key already exist"];
            return back()->withNotify($notify);
        } else {
            $newArr[$reqKey] = trim($request->value);
            $itemData = json_decode($items, true);
            $result = array_merge($itemData, $newArr);
            file_put_contents(resource_path('lang/') . $lang->code . '.json', json_encode($result));
            $notify[] = ['success', "Language key added successfully"];
            return back()->withNotify($notify);
        }
    }

    public function deleteLanguageJson(Request $request, $id)
    {
        $request->validate([
            'key' => 'required',
            'value' => 'required'
        ]);

        $key = $request->key;
        $lang = Language::findOrFail($id);
        $data = file_get_contents(resource_path('lang/') . $lang->code . '.json');

        $jsonArr = json_decode($data, true);
        unset($jsonArr[$key]);

        file_put_contents(resource_path('lang/') . $lang->code . '.json', json_encode($jsonArr));
        $notify[] = ['success', "Language key deleted successfully"];
        return back()->withNotify($notify);
    }

    public function updateLanguageJson(Request $request, $id)
    {
        $request->validate([
            'key' => 'required',
            'value' => 'required'
        ]);

        $key = trim($request->key);
        $reqValue = $request->value;
        $lang = Language::findOrFail($id);

        $data = file_get_contents(resource_path('lang/') . $lang->code . '.json');

        $jsonArr = json_decode($data, true);

        $jsonArr[$key] = $reqValue;

        file_put_contents(resource_path('lang/') . $lang->code . '.json', json_encode($jsonArr));

        $notify[] = ['success', 'Language key updated successfully'];
        return back()->withNotify($notify);
    }


    public function langDelete($id)
    {
        $lang = Language::find($id);
        fileManager()->removeFile(resource_path('lang/') . $lang->code . '.json');
        fileManager()->removeFile(getFilePath('language') . '/' . $lang->image);
        $lang->delete();
        $notify[] = ['success', 'Language deleted successfully'];
        return back()->withNotify($notify);
    }


}
