<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Tag;
use OptimaIt\Transcript\SubtitleFactory;
use OptimaIt\Transcript\TranscriptFinder;
use OptimaIt\Transcript\TranscriptGenerator;

class AjaxController extends Controller
{
    public function contactsByCompanies()
    {
       if(!request('value')){
        abort(404);
       }
       return response()->json([
        'body' => OptionsView(Contact::whereIn('company_id', request('value'))->get(), 'id', function($item){
            return $item->name.(request('include') ? '-'.$item->{request('include')} : '');
        })
       ]);
    }


    public function deleteTag($id){
        $tag = Tag::findOrFail(decryptIt($id));

        $tag->delete();

        return response()->json([
            'success' => 1
        ]);
    }


    public function updateTag($id){
        $tag = Tag::findOrFail(decryptIt($id));
        $tag->name = request('value');

        $tag->save();

        return ajaxSuccess([
            // 'notification' => 
        ]);
    }
}
