<?php

namespace App\Http\Controllers;

use App\Enum\CardStatusEnum;
use App\Events\CardApplicationUpdated;
use App\Http\Requests\StoreCardApplicationRequest;
use App\Http\Requests\UpdateCardApplicationRequest;
use App\Models\CardApplication;
use App\Models\CardApplicationUpdate;
use App\Traits\DocumentTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class CardApplicationController extends Controller
{
    use DocumentTrait;

    public function __construct()
    {
        $this->middleware('auth:academics,entryStaffs,couponStaffs,cardApplicationStaffs');
    }

    /**
     * @return Application|Factory|View|RedirectResponse|Redirector|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', CardApplication::class);
        $user = Auth::user();
        if ($user->cardApplicant->currentCardApplication()->count() > 0) {
            return ($request->expectsJson())
                ? response()->json(["cardApplication" => $user->cardApplicant->currentCardApplication()->with('cardLastUpdate')->first()], 200)
                : view('cardApplication/show');;
        }
        $user->cardApplicant->address;
        $models = [$user];
        $caption = 'User info';
        return ($request->expectsJson())
            ? response()->json(["message " => 'Application not found. Please create a new one.'], 404)
            : view('cardApplication/index', compact('models', 'caption'));
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
     * @return Application|RedirectResponse|Redirector|\Illuminate\Http\JsonResponse
     */
    public function store(StoreCardApplicationRequest $request)
    {
        $this->authorize('create', CardApplication::class);
        DB::transaction(function () {
            $cardApplication = new CardApplication();
            $cardApplication->academic_id = Auth::user()->cardApplicant->academic_id;
            $cardApplication->expiration_date = date('Y-m-d', strtotime('-1 day'));
            $cardApplication->saveOrFail();
            $cardApplication->applicantComments()->create(['comment' => '']);
        });
        return ($request->expectsJson())
            ? response()->json(["message " => 'the application has created', 'success' => true], 201)
            : redirect(route('cardApplication.index'));
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
        return view('cardApplication/edit');

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
        if ($cardApplication->cardApplicationDocument()->where('status', CardStatusEnum::INCOMPLETE)->count() > 0) return ['success' => false, 'message' => 'You don\'t have update the wrong/incomplete documents ',];

        $vData = $request->validated();
        $vData['status'] = CardStatusEnum::SUBMITTED;
        DB::transaction(function () use ($vData, $cardApplication) {
            $old_status = $cardApplication->cardLastUpdate->status ?? null;
            $cardApplication->applicantComments()->create($vData);
            $cardApplication->touch();
            broadcast(event: new CardApplicationUpdated(
                cardApplication: $cardApplication,
                status: $vData['status']->value,
                old_status: $old_status,
                comment: $vData['comment'] ?? null))->toOthers();
        });

        return ['success' => true, 'message' => 'Application has been saved',];
        //return ['success' => false, 'message' => 'Application didn\'t saved',];
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
