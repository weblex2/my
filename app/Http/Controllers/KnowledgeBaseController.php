<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeBase;
use App\Http\Requests\StoreKnowledgeBaseRequest;
use App\Http\Requests\UpdateKnowledgeBaseRequest;
use App\Http\Resources\KnowledgeBaseResource;
use Request;

class KnowledgeBaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return KnowledgeBaseResource::collection(
            KnowledgeBase::all()
        );
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
     * @param  \App\Http\Requests\StoreKnowledgeBaseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreKnowledgeBaseRequest $request)
    {
        $kb  = KnowledgeBase::create([
            'topic' => $request->topic,
            'description' => $request->description,
            'text' => $request->text
        ]);

        return new KnowledgeBaseResource($kb);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\KnowledgeBase  $knowledgeBase
     * @return \Illuminate\Http\Response
     */
    public function show(KnowledgeBase $knowledgeBase)
    {
        return new KnowledgeBaseResource($knowledgeBase);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\KnowledgeBase  $knowledgeBase
     * @return \Illuminate\Http\Response
     */
    public function edit(KnowledgeBase $knowledgeBase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateKnowledgeBaseRequest  $request
     * @param  \App\Models\KnowledgeBase  $knowledgeBase
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateKnowledgeBaseRequest $request, KnowledgeBase $knowledgeBase)
    {
        //$knowledgeBase  = KnowledgeBase::find($request->id);
        $knowledgeBase->update([
            'topic' => $request->topic,
            'description' => $request->description,
            'text' => $request->text
        ]);
        return new KnowledgeBaseResource($knowledgeBase);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\KnowledgeBase  $knowledgeBase
     * @return \Illuminate\Http\Response
     */
    public function destroy(KnowledgeBase $knowledgeBase)
    {
        $knowledgeBase->delete();
        return response('', 204);
    }

}
