<?php

namespace App\Http\Controllers;

use App\Enum\CardDocumentStatusEnum;
use App\Enum\CardStatusEnum;
use App\Enum\UserAbilityEnum;
use App\Http\Requests\StoreCardApplicationRequest;
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

class CardApplicationController extends Controller
{
    use DocumentTrait;
    public function __construct()
    {
        $this->middleware('auth:academics');
        $this->middleware('ability:' . UserAbilityEnum::CARD_OWNERSHIP->name);

    }


    /**
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function index()
    {
        $user = Auth::user();
        if($user->cardApplicant->currentCardApplication()->count() > 0)
            return redirect(route('cardApplication.show', [
                "cardApplication"=>$user->cardApplicant->currentCardApplication
            ]));
        $user->cardApplicant->address;
        $models = [$user];
        $caption = 'User info';
        return view('cardApplication/index', compact('models', 'caption'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }


    /**
     * @param StoreCardApplicationRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(StoreCardApplicationRequest $request)
    {
        $cardApplication= new CardApplication();
        $cardApplication->academic_id= Auth::user()->cardApplicant->academic_id;
        $cardApplication->expiration_date= date('Y-m-d', strtotime('-1 day'));
        $cardApplication->save();
        return redirect(route('cardApplication.show', compact("cardApplication")));
    }

    /**
     * Display the specified resource.
     *
     * @param CardApplication $cardApplication
     * @return Response
     */
    public function show(CardApplication $cardApplication)
    {
        dd($cardApplication);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CardApplication $cardApplication
     * @return array|Application|Factory|View
     */
    public function edit(CardApplication $cardApplication)
    {
        if (Auth::user()->getAttribute('academic_id')!=$cardApplication->academic_id)
            abort(403, 'Unauthorized Access');
        $files = $cardApplication->cardApplicationDocument()->get(['id','description']);


        return view('cardApplication/edit', compact('cardApplication','files'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCardApplicationRequest $request
     * @param CardApplication $cardApplication
     * @return array
     * @throws \Throwable
     */
    public function update(UpdateCardApplicationRequest $request,CardApplication $cardApplication): array
    {
        if (Auth::user()->getAttribute('academic_id')!=$cardApplication->academic_id)
            return ['success'=>false,
        'message'=>'You don\'t have authority to update the Application ',
    ];
        if($cardApplication->updateOrFail(
            ['status'=>CardStatusEnum::SUBMITTED]))
            return ['success'=>true,
                'message'=>'Application has been saved',
                ];
        return ['success'=>false,
            'message'=>'Application didn\'t saved',
        ];
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
