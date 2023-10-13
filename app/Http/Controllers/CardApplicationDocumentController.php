<?php

namespace App\Http\Controllers;

use App\Enum\CardDocumentStatusEnum;
use App\Enum\UserAbilityEnum;
use App\Http\Requests\StoreCardApplicationDocumentRequest;
use App\Http\Requests\StoreCardApplicationRequest;
use App\Http\Requests\UpdateCardApplicationDocumentRequest;
use App\Http\Requests\UpdateCardApplicationRequest;
use App\Models\CardApplication;
use App\Models\CardApplicationDocument;
use App\Traits\DocumentTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\isNull;

class CardApplicationDocumentController extends Controller
{
    use DocumentTrait;
    public function __construct()
    {
        $this->middleware('auth:academics,cardApplicationStaffs');
        //$this->middleware('ability:' . UserAbilityEnum::CARD_OWNERSHIP->name);

    }


    /**
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function index($cardApplication)
    {
        $this->authorize('viewAny', CardApplicationDocument::class);
        return CardApplicationDocument::whereCardApplicationId($cardApplication)->select(['id','status'])->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(CardApplication $cardApplication)
    {
        //

    }


    /**
     * @throws \Throwable
     */
    public function store(StoreCardApplicationDocumentRequest $request,CardApplication $cardApplication): array
    {
        $this->authorize('create', CardApplicationDocument::class);
//        if (Auth::user()->getAttribute('academic_id')!=$cardApplication->academic_id)
//            return ['success'=>false,
//                'message'=>'You don\'t have authority to update the Application ',
//            ];
        $id = DB::transaction(callback: function () use ($request, $cardApplication) {
            $file= $request->file('file');
            $description= $request['description'];
            $id=$request['id'];
            if($file->isValid()  && $file->extension()=='pdf' ) {
                $filename = $file->getClientOriginalName();
                $cardApplicationDocument = $cardApplication->cardApplicationDocument()->create([
                        'file_name' => $filename,
                        'description' => $description,
                        'status' => CardDocumentStatusEnum::SUBMITTED]
                );
                $file->storeAs(...$this::storePositionData($cardApplication->academic_id, $cardApplicationDocument));
                return $cardApplicationDocument->id;
            }
            else // the file is not valid
                return 0;
        });
        if (0 != $id)
            return ['success'=>true,
                'message'=>'File is uploaded successfully!',
                'id'=>$id,
            ];
        return ['success'=>false,
            'message'=>'File is not valid please retry',
            'id'=>0,
        ];


    }

    /**
     * Display the specified resource.
     *
     * @param CardApplication $cardApplication
     * @return Response
     */
    public function show(CardApplication $cardApplication, CardApplicationDocument $document)
    {
        $this->authorize('view', $cardApplication);
//        if (Auth::user()->getAttribute('academic_id')!=$cardApplication->academic_id)
//            abort(403, 'Unauthorized Access');
        $fileStorageData = $this::storePositionData($cardApplication->academic_id, $document); // Adjust the file path according to your file storage location
        $filePath= $fileStorageData[0].'/'.$fileStorageData[1];
        $disk = $fileStorageData[2];
        // Check if the file exists
        if (Storage::disk($disk)->exists($filePath)) {
            // Retrieve the file's contents
            $fileContents = Storage::disk($disk)->get($filePath);

            // Determine the file's MIME type
            $mimeType = Storage::disk($disk)->mimeType($filePath);

            // Return the file response
            return response($fileContents)->header('Content-Type', $mimeType)->
            header('Content-Disposition',"inline; filename=$document->file_name");
        }

        // If the file doesn't exist, return a 404 response or handle it as per your requirements
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CardApplication $cardApplication
     * @return Response
     */
    public function edit(CardApplication $cardApplication)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCardApplicationRequest $request
     * @param int $cardApplication
     * @return array
     * @throws \Throwable
     */
    public function update(UpdateCardApplicationDocumentRequest $request,int  $cardApplication, int $cardApplicationDocument)
    {
        return CardApplicationDocument::whereId($cardApplicationDocument)->update($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CardApplication $cardApplication
     * @return Response
     */
    public function destroy(CardApplication $cardApplication)
    {
        //
    }
}
