<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Attachment;
use App\Models\AttachmentCommessa;
use App\Models\AttachmentManutenzione;
use App\Models\AttachmentMessaggio;
use App\Models\AttachmentModOt23;
use App\Models\AttachmentScadenza;
use App\Models\Commessa;
use App\Models\CommessaRapportino;
use App\Models\InailModOt23;
use App\Models\Item;
use App\Models\Manutenzione;
use App\Models\Messaggio;
use App\Models\Scadenza;
use App\Scopes\IsMessaggioScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class UploadController extends Controller
{
    public function upload(Request $request) {

        /// Log::info($request->all());

        switch ($request->input('upload_mode', null)) {
            case 'multi':
                $validationRules = [];
                break;

            case 'cloud':
                $validationRules = [
                    'label' => 'required',
                    'url_cloud' => 'required'
                ];

                break;
            default:
                $validationRules = [
                    'label' => 'required',
                    'attachment' => 'required|file|max:25000'
                ];
        }

        $validatedData = $request->validate($validationRules);

        // controllo i limiti
        if (isLimitUpload())
            return response()->json(['message' => 'Spazio in cloud terminato.'], 401);

        DB::beginTransaction();
        try {

            if ($request->input('upload_mode', null) == 'cloud') {
                switch ($request->get('module', null)) {
                    case 'modot':
                        $el = new AttachmentModOt23;
                        $item = InailModOt23::find($request->get('item_id'));
                        if (!$item) return response()->json(['message' => 'Not found'], 404);;

                        $el->item_id = $item->id;
                        $el->azienda_id = $item->azienda_id;
                        break;

                    case 'scadenza':
                        $el = new AttachmentScadenza();
                        $scadenza = Scadenza::find($request->get('scadenza_id'));
                        if (!$scadenza) return response()->json(['message' => 'Not found'], 404);;

                        $el->scadenza_id = $scadenza->id;
                        $el->azienda_id = $scadenza->azienda_id;
                        break;

                    case 'manutenzione':
                        $el = new AttachmentManutenzione();
                        $manutenzione = Manutenzione::withoutGlobalScope('App\Scopes\ManutenzioneScope')->find($request->get('manutenzione_id'));
                        if (!$manutenzione) return response()->json(['message' => 'Not found'], 404);;

                        $el->manutenzione_id = $manutenzione->id;
                        $el->azienda_id = $manutenzione->azienda_id;
                        break;

                    case 'messaggio':
                        $el = new AttachmentMessaggio();
                        $messaggio = Messaggio::withoutGlobalScope(IsMessaggioScope::class)->find($request->get('messaggio_id'));
                        if (!$messaggio) return response()->json(['message' => 'Not found'], 404);;

                        $el->messaggio_id = $messaggio->id;
                        $el->azienda_id = $messaggio->azienda_id;
                        break;

                    case 'commessa-rapportino':
                        $el = new AttachmentCommessa();

                        $rapportino = CommessaRapportino::find($request->get('item_id'));
                        if (!$rapportino) return response()->json(['message' => 'Not found'], 404);;

                        $node = Commessa::find($rapportino->commesse_id);
                        if (!$node) return response()->json(['message' => 'Not found'], 404);;

                        $el->commesse_id = $node->id;
                        $el->commesse_rapportini_id = $rapportino->id;
                        $el->azienda_id = $node->azienda_id;
                        break;

                    case 'commessa':
                        $el = new AttachmentCommessa();

                        $node = Commessa::find($request->get('commesse_id'));
                        if (!$node) return response()->json(['message' => 'Not found'], 404);;

                        $el->commesse_id = $node->id;
                        $el->azienda_id = $node->azienda_id;
                        break;

                    default:
                        $el = new Attachment;
                        $item = Item::find($request->get('item_id'));
                        if (!$item) return response()->json(['message' => 'Not found'], 404);;

                        $el->item_id = $item->id;
                        $el->controller = $item->controller;
                        $el->azienda_id = $item->azienda_id;
                }

                $filename = 'cloud';
                $el->filename = $filename;

                $el->label = $request->get('label');
                $el->url_cloud = $request->get('url_cloud');
                $el->size = 0;

                $el->save();

                DB::commit();
                return response()->json(['res' => 'success', 'message' => 'Upload completed'], 200);

            }
            else {
                /** upload fisici **/
                foreach ($request->file() as $type => $files) {
                    switch ($request->get('module', null)) {
                        case 'modot':
                            $el = new AttachmentModOt23;
                            $item = InailModOt23::find($request->get('item_id'));
                            if (!$item) return response()->json(['message' => 'Not found'], 404);;

                            $el->item_id = $item->id;
                            $el->azienda_id = $item->azienda_id;

                            $storage = $el->azienda_id.'/modot/';
                            break;

                        case 'scadenza':
                            $el = new AttachmentScadenza();
                            $scadenza = Scadenza::find($request->get('scadenza_id'));
                            if (!$scadenza) return response()->json(['message' => 'Not found'], 404);;

                            $el->scadenza_id = $scadenza->id;
                            $el->azienda_id = $scadenza->azienda_id;

                            $storage = $el->azienda_id.'/scadenza/';
                            break;

                        case 'manutenzione':
                            $el = new AttachmentManutenzione();
                            $manutenzione = Manutenzione::withoutGlobalScope('App\Scopes\ManutenzioneScope')->find($request->get('manutenzione_id'));
                            if (!$manutenzione) return response()->json(['message' => 'Not found'], 404);;

                            $el->manutenzione_id = $manutenzione->id;
                            $el->azienda_id = $manutenzione->azienda_id;

                            $storage = $el->azienda_id.'/manutenzione/';
                            break;

                        case 'messaggio':
                            $el = new AttachmentMessaggio();
                            $messaggio = Messaggio::withoutGlobalScope(IsMessaggioScope::class)->find($request->get('messaggio_id'));
                            if (!$messaggio) return response()->json(['message' => 'Not found'], 404);;

                            $el->messaggio_id = $messaggio->id;
                            $el->azienda_id = $messaggio->azienda_id;

                            $storage = $el->azienda_id.'/messaggio/';
                            break;

                        case 'commessa-rapportino':
                            $el = new AttachmentCommessa();

                            $rapportino = CommessaRapportino::find($request->get('item_id'));
                            if (!$rapportino) return response()->json(['message' => 'Not found'], 404);;

                            $node = Commessa::find($rapportino->commesse_id);
                            if (!$node) return response()->json(['message' => 'Not found'], 404);;

                            $el->commesse_id = $node->id;
                            $el->commesse_rapportini_id = $rapportino->id;
                            $el->azienda_id = $node->azienda_id;

                            $storage = $el->azienda_id.'/commessa/';
                            break;

                        case 'commessa':
                            $el = new AttachmentCommessa();

                            $node = Commessa::find($request->get('commesse_id'));
                            if (!$node) return response()->json(['message' => 'Not found'], 404);;

                            $el->commesse_id = $node->id;
                            $el->azienda_id = $node->azienda_id;

                            $storage = $el->azienda_id.'/commessa/';
                            break;

                        default:
                            $el = new Attachment;
                            $item = Item::find($request->get('item_id'));
                            if (!$item) return response()->json(['message' => 'Not found'], 404);;

                            $el->item_id = $item->id;
                            $el->controller = $item->controller;
                            $el->azienda_id = $item->azienda_id;

                            $storage = $el->azienda_id.'/';
                    }

                    Log::info($files);
                    Log::info('--------------------------');

                    if (!is_array($files)) {
                        $files = [$files];
                    }

                    foreach ($files as $file) {
                        $filename = $file->getClientOriginalName();
                        $filename = pathinfo($filename, PATHINFO_FILENAME);
                        $filename = Str::slug($filename);
                        $extension = $file->getClientOriginalExtension();
                        $filename = $filename . '.' . strtolower($extension);
                        $el->filename = $filename;

                        $el->label = $request->input('label') ?? $filename;
                        $el->size = $file->getSize();

                        $el->save();
                        $storage .= $el->id;

                        $file->move(public_path('docs/'.$storage), $filename);
                    }


                    DB::commit();
                    return response()->json(['res' => 'success', 'message' => 'Upload completed'], 200);
                }
            }



        }
        catch (\Exception $e) {

            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 404);
        }

    }

    public function attachments(Request $request, $id) {

        Log::info($request->all());

        switch ($request->get('module', null)) {
            case 'modot':
                $el = InailModOt23::with('attachments')->find($id);
                break;

            case 'scadenza':
                $el = Scadenza::with('attachments')->find($id);
                break;

            case 'manutenzione':
                $el = Manutenzione::withoutGlobalScope('App\Scopes\ManutenzioneScope')->with('attachments')->find($id);
                break;

            case 'messaggio':
                $el = Messaggio::withoutGlobalScope(IsMessaggioScope::class)->with('attachments')->find($id);
                break;

            case 'commessa-rapportino':
                $el = CommessaRapportino::with('attachments')->find($id);
                break;

            default:
                $el = Item::with('attachments')->find($id);
        }

        if (!$el) abort(404);

        $data['module'] = $request->get('module', null);
        $data['attachments'] = $el->attachments;
        $data['_read_only'] = false;
        return view('dashboard.upload.index', $data);
    }

    public function download($md5_attachment_id) {
        $el = Attachment::whereRaw("md5(id) = '$md5_attachment_id'")->first();
        if (!$el) abort(404);

        $file = public_path('docs/'.$el->azienda_id.'/'.$el->id.'/'.$el->filename);
        return response()->download($file, $el->filename);
    }

    public function delete(Request $request, $id) {

        switch ($request->get('module', null)) {
            case 'modot':
                $el = AttachmentModOt23::whereId($id)->first();
                if (!$el) abort(404);
                $file = public_path('docs/'.$el->azienda_id.'/modot/'.$el->id);
                break;

            case 'scadenza':
                $el = AttachmentScadenza::whereId($id)->first();
                if (!$el) abort(404);
                $file = public_path('docs/'.$el->azienda_id.'/scadenza/'.$el->id);
                break;

            case 'manutenzione':
                $el = AttachmentManutenzione::whereId($id)->first();
                if (!$el) abort(404);
                $file = public_path('docs/'.$el->azienda_id.'/manutenzione/'.$el->id);
                break;

            case 'messaggio':
                $el = AttachmentMessaggio::whereId($id)->first();
                if (!$el) abort(404);
                $file = public_path('docs/'.$el->azienda_id.'/messaggio/'.$el->id);
                break;

            case 'commessa-rapportino':
            case 'commessa':
                $el = AttachmentCommessa::whereId($id)->first();
                if (!$el) abort(404);
                $file = public_path('docs/'.$el->azienda_id.'/commessa/'.$el->id);
                break;

            default:
                $el = Attachment::whereId($id)->first();
                if (!$el) abort(404);

                $file = public_path('docs/'.$el->azienda_id.'/'.$el->id);
        }

        File::deleteDirectory($file);
        $el->delete();
        return response()->json(['res' => 'success', 'payload' => $id]);
    }

    public function visibility(Request $request, $id) {
        $el = Attachment::whereId($id)->first();
        if (!$el) abort(404);

        try {
            $el->is_public = $request->input('is_public');
            $el->save();

            return response()->json(['res' => 'success']);
        }
        catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
