<?php



namespace App\Http\Controllers\Dashboard;



use App\Models\AttachmentS3;
use App\Utilities\S3;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;


class UploadS3Controller extends Controller
{

    protected $s3;

    /**
     * @param $s3
     */
    public function __construct(S3 $s3)
    {
        $this->s3 = $s3;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $reference = DB::table($request->input('reference_table'))
            ->where('id', $request->input('reference_id'))
            ->where('azienda_id', getAziendaId())
            ->first();

        if (!$reference)
            abort(404);

        $data['attachments'] = AttachmentS3::where('reference_id', $reference->id)
                ->where('reference_table', $request->input('reference_table'))
                ->where('to_delete', '0')
                ->get();

        return view('dashboard.upload.s3.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
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

        $reference = DB::table($request->input('reference_table'))
            ->where('id', $request->input('reference_id'))
            ->where('azienda_id', getAziendaId())
            ->first();

        if (!$reference)
            abort(404);

        $reference_id = $request->input('reference_id');
        $reference_table = $request->input('reference_table');
        $is_public = $request->input('is_public', '0');
        $is_embedded = $request->input('is_embedded', '0');
        $label = $request->input('label', null);

        try {
            if ($request->input('upload_mode', null) == 'cloud') {

                $s3Attachment = new AttachmentS3;
                $s3Attachment->id = Str::uuid();
                $s3Attachment->azienda_id = $reference->azienda_id;
                $s3Attachment->reference_id = $reference_id;
                $s3Attachment->reference_table = $reference_table;
                $s3Attachment->is_public = $is_public;
                $s3Attachment->is_embedded = $is_embedded;
                $s3Attachment->users_id = auth()->user()->id;

                $filename = 'cloud';
                $s3Attachment->filename = $filename;

                $s3Attachment->label = $label;
                $s3Attachment->url_cloud = $request->get('url_cloud');
                $s3Attachment->size = 0;

                $s3Attachment->save();
            }
            else if ($request->input('upload_mode', null) == 'multi') {
                $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));

                // check if the upload is success, throw exception or return response you need
                if ($receiver->isUploaded() === false) {
                    throw new UploadMissingFileException();
                }

                // receive the file
                $save = $receiver->receive();

                // check if the upload has finished (in chunk mode it will send smaller files)
                if ($save->isFinished()) {
                    // save the file and return any response you need, current example uses `move` function. If you are
                    // not using move, you need to manually delete the file by unlink($save->getFile()->getPathname())
                    return $this->s3->saveFile($save->getFile(), $reference, $reference_id, $reference_table, $is_public, $is_embedded, $label);
                }

                // we are in chunk mode, lets send the current progress
                $handler = $save->handler();

                return response()->json([
                    "done" => $handler->getPercentageDone(),
                ]);
            }
            else {
                /** upload fisici **/
                foreach ($request->file() as $type => $files) {
                    // Log::info($files);
                    // Log::info('--------------------------');

                    if (!is_array($files)) {
                        $files = [$files];
                    }

                    foreach ($files as $file) {
                        $this->s3->saveFile($file, $reference, $reference_id, $reference_table, '0', $is_embedded, $label);
                    }
                }
            }

            return response()->json(['res' => 'success', 'message' => 'Upload completed']);
        }
        catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        ///
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $s3Attachment = AttachmentS3::find($id);
        if (!$s3Attachment)
            abort(404);

        /** Cancellazione logica c'è un job che si occupa di fare quella fisica **/
        try {
            $s3Attachment->to_delete = '1';
            $s3Attachment->save();

            return response()->json(['res' => 'success', 'message' => 'Salvataggio avvenuto']);
        }
        catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function visibility($id, $is_public) {
        $s3Attachment = AttachmentS3::find($id);
        if (!$s3Attachment)
            abort(404);

        try {
            $s3Attachment->is_public = $is_public;
            $s3Attachment->save();

            return response()->json(['res' => 'success', 'message' => 'Salvataggio avvenuto']);
        }
        catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function embedded($id, $is_embedded) {
        $s3Attachment = AttachmentS3::find($id);
        if (!$s3Attachment)
            abort(404);

        try {
            $s3Attachment->is_embedded = $is_embedded;
            $s3Attachment->save();

            return response()->json(['res' => 'success', 'message' => 'Salvataggio avvenuto']);
        }
        catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function modal(Request $request)
    {
        $reference = DB::table($request->input('reference_table'))
            ->where('id', $request->input('reference_id'))
            ->where('azienda_id', getAziendaId())
            ->first();

        if (!$reference)
            abort(404);

        $data['el'] = $reference;
        if (!$data['el']) abort('404');

        $data['reference_table'] = $request->input('reference_table');
        $data['reference_id'] = $request->input('reference_id');

        return view('dashboard.upload.s3.modal-upload', $data);
    }


}

