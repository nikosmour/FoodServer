<?php

namespace App\Http\Controllers;

use App\Enum\CardStatusEnum;
use App\Events\CardApplicationUpdated;
use App\Http\Requests\UpdateCardApplicationRequest;
use App\Models\CardApplication;
use App\Traits\DocumentTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
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
     * @return Application|Factory|View|RedirectResponse|Redirector|JsonResponse
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', CardApplication::class);
        $cardApplicant = Auth::user()->cardApplicant;
//        $user->load('cardApplicant.currentCardApplication.cardLastUpdate');
        if ($cardApplicant->currentCardApplication) {
            $update = $cardApplicant->currentCardApplication->cardLastUpdate;
            $responseData = [
                "cardApplication" => $cardApplicant->currentCardApplication
            ];
            if ($update->status !== CardStatusEnum::ACCEPTED)
                $responseData['lastExpiration'] = $cardApplicant->validCardApplication()->value('expiration_date')->format('Y-m-d');
//            value('status')->first();
            return response()->json($responseData, 200);
        }

        return response()->json(["message" => 'Application not found. Please create a new one.'], 404);

//        $user->cardApplicant->address;
//        $models = [$user];
//        $caption = 'User info';
//        return view('cardApplication/index', compact('models', 'caption'));
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
     * @return Application|RedirectResponse|Redirector|JsonResponse
     */
    public function store()
    {
        $this->authorize('create', CardApplication::class);
        DB::transaction(function () {
            $cardApplication = new CardApplication();
            $cardApplication->academic_id = Auth::user()->cardApplicant->academic_id;
            $cardApplication->expiration_date = now()->subDay()->format('Y-m-d');
            $cardApplication->saveOrFail();
            $cardApplication->applicantComments()->create(['comment' => '']);
        });
        return response()->json(["message " => 'the application has created', 'success' => true], 201);
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
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(UpdateCardApplicationRequest $request, CardApplication $cardApplication): JsonResponse
    {
        $this->authorize('update', $cardApplication);
        $vData = $request->validated();
        $vData['status'] = CardStatusEnum::from($vData['status']);
        if ($vData['status'] === CardStatusEnum::SUBMITTED && $cardApplication->cardApplicationDocument()->where('status', CardStatusEnum::INCOMPLETE)->count() > 0)
            return response()->json(['success' => false, 'message' => 'You don\'t have update the wrong/incomplete documents '], 422);


        DB::transaction(function () use ($vData, $cardApplication) {
            $old_status = $cardApplication->cardLastUpdate->status ?? null;
            $cardApplication->touch();
            if ($old_status === $vData['status'])
                return;
            $cardApplication->applicantComments()->create($vData);
            broadcast(event: new CardApplicationUpdated(
                cardApplication: $cardApplication,
                status: $vData['status'],
                old_status: $old_status,
                comment: $vData['comment'] ?? null))->toOthers();
        });

        return response()->json(['success' => true, 'message' => 'Application has been updated'], 201);
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
