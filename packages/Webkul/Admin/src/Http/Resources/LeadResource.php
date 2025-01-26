<?php

namespace Webkul\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class LeadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                   => $this->id,
            'title'                => $this->title,
            'lead_value'           => $this->lead_value,
            'formatted_lead_value' => core()->formatBasePrice($this->lead_value),
            'status'               => $this->status,
            'expected_close_date'  => $this->convertToSaoPaulo($this->expected_close_date),
            'rotten_days'          => $this->rotten_days,
            'closed_at'            => $this->closed_at,
            'created_at'           => $this->convertToSaoPaulo($this->created_at),
            'updated_at'           => $this->convertToSaoPaulo($this->updated_at),
            'person'               => new PersonResource($this->person),
            'user'                 => new UserResource($this->user),
            'type'                 => new TypeResource($this->type),
            'source'               => new SourceResource($this->source),
            'pipeline'             => new PipelineResource($this->pipeline),
            'stage'                => new StageResource($this->stage),
            'tags'                 => TagResource::collection($this->tags),
        ];
   }

  /**
     * Convert the date to 'America/Sao_Paulo' timezone without altering its value.
     *
     * @param  string|null $date
     * @return string|null
     */
    private function convertToSaoPaulo($date)
    {
        return $date ? Carbon::parse($date, 'UTC')->timezone('America/Sao_Paulo')->format('Y-m-d H:i:s') : null;
    }    
}
