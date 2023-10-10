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
use Throwable;
use function PHPUnit\Framework\isNull;

class CardApplicationController extends Controller
{
    use DocumentTrait;

    public function __construct()
    {
        $this->middleware('auth:academics,cardApplicationStaffs');
//        $this->middleware('ability:' . UserAbilityEnum::CARD_OWNERSHIP->name);

    }


    /**
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function index()
    {
        $this->authorize('viewAny', CardApplication::class);
        $user = Auth::user();
        if ($user->cardApplicant->currentCardApplication()->count() > 0) return redirect(route('cardApplication.show', ["cardApplication" => $user->cardApplicant->currentCardApplication]));
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
        $this->authorize('create', CardApplication::class);
        $cardApplication = new CardApplication();
        $cardApplication->academic_id = Auth::user()->cardApplicant->academic_id;
        $cardApplication->expiration_date = date('Y-m-d', strtotime('-1 day'));
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
        $this->authorize('view', $cardApplication);
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
        $this->authorize('update', $cardApplication);
        $files = $cardApplication->cardApplicationDocument()->get(['id', 'description', 'status']);


        return view('cardApplication/edit', compact('cardApplication', 'files'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCardApplicationRequest $request
     * @param CardApplication $cardApplication
     * @return array
     * @throws Throwable
     */
    public function update(UpdateCardApplicationRequest $request, CardApplication $cardApplication): array
    {
        $this->authorize('update', $cardApplication);
        if ($cardApplication->cardApplicationDocument()->where('status', CardStatusEnum::INCOMPLETE)->count() > 0) return ['success' => false, 'message' => 'You don\'t have update the wrong documents ',];
        $cardApplication->status = CardStatusEnum::SUBMITTED;
        if ($cardApplication->saveOrFail()) return ['success' => true, 'message' => 'Application has been saved',];
        return ['success' => false, 'message' => 'Application didn\'t saved',];
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
