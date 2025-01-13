<?php

namespace Webkul\Admin\Http\Controllers\Settings;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Settings\PipelineDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\PipelineForm;
use Webkul\Lead\Repositories\PipelineRepository;

class PipelineController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected PipelineRepository $pipelineRepository) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(PipelineDataGrid::class)->process();
        }

        return view('admin::settings.pipelines.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin::settings.pipelines.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PipelineForm $request): RedirectResponse
    {
        $request->validated();
    
        // Adiciona as traduções para "Ganho" e "Perdido"
        $request->merge([
            'is_default' => request()->has('is_default') ? 1 : 0,
            //'won_label'  => __('won-stage'),  // Tradução para "Ganho"
            //'lost_label' => __('lost-stage'), // Tradução para "Perdido"
            'won_label' => __('pipeline.create.won-stage'),
            'lost_label' => __('pipeline.create.lost-stage'),
        ]);
    
        // Dispara evento antes da criação
        Event::dispatch('settings.pipeline.create.before');
    
        // Cria o pipeline com os dados fornecidos
        $pipeline = $this->pipelineRepository->create($request->all());
    
        // Dispara evento após a criação
        Event::dispatch('settings.pipeline.create.after', $pipeline);
    
        // Mensagem de sucesso
        session()->flash('success', trans('admin::app.settings.pipelines.index.create-success'));
    
        return redirect()->route('admin.settings.pipelines.index');
    }    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $pipeline = $this->pipelineRepository->findOrFail($id);

        return view('admin::settings.pipelines.edit', compact('pipeline'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PipelineForm $request, int $id): RedirectResponse
    {
        $request->validated();
    
        // Adiciona as traduções para "Ganho" e "Perdido"
        $request->merge([
            'is_default' => request()->has('is_default') ? 1 : 0,
            //'won_label'  => __('won-stage'),  // Tradução para "Ganho"
            //'lost_label' => __('lost-stage'), // Tradução para "Perdido"
            'won_label' => __('pipeline.create.won-stage'),
            'lost_label' => __('pipeline.create.lost-stage'),
        ]);
    
        // Dispara evento antes da atualização
        Event::dispatch('settings.pipeline.update.before', $id);
    
        // Atualiza o pipeline com os dados fornecidos
        $pipeline = $this->pipelineRepository->update($request->all(), $id);
    
        // Dispara evento após a atualização
        Event::dispatch('settings.pipeline.update.after', $pipeline);
    
        // Mensagem de sucesso
        session()->flash('success', trans('admin::app.settings.pipelines.index.update-success'));
    
        return redirect()->route('admin.settings.pipelines.index');
    }
        

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $pipeline = $this->pipelineRepository->findOrFail($id);

        if ($pipeline->is_default) {
            return response()->json([
                'message' => trans('admin::app.settings.pipelines.index.default-delete-error'),
            ], 400);
        } else {
            $defaultPipeline = $this->pipelineRepository->getDefaultPipeline();

            $pipeline->leads()->update([
                'lead_pipeline_id'       => $defaultPipeline->id,
                'lead_pipeline_stage_id' => $defaultPipeline->stages()->first()->id,
            ]);
        }

        try {
            Event::dispatch('settings.pipeline.delete.before', $id);

            $this->pipelineRepository->delete($id);

            Event::dispatch('settings.pipeline.delete.after', $id);

            return response()->json([
                'message' => trans('admin::app.settings.pipelines.index.delete-success'),
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => trans('admin::app.settings.pipelines.index.delete-failed'),
            ], 400);
        }

        return response()->json([
            'message' => trans('admin::app.settings.pipelines.index.delete-failed'),
        ], 400);
    }

    public function show($id)
    {
        $pipeline = Pipeline::find($id);
        $pipeline->stages = collect($pipeline->stages)->map(function ($stage) {
        });
    
        return view('pipeline.show', compact('pipeline'));
    }
}
