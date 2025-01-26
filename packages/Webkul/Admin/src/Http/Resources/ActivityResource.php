<?php

namespace Webkul\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ActivityResource extends JsonResource
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
            'id'            => $this->id,
            'parent_id'     => $this->parent_id ?? null,
            'title'         => $this->title,
            'type'          => $this->type,
            'comment'       => $this->comment,
            'additional'    => is_array($this->resource->additional) ? $this->resource->additional : json_decode($this->resource->additional, true),
            'schedule_from' => $this->convertToSaoPaulo($this->schedule_from),
            'schedule_to'   => $this->convertToSaoPaulo($this->schedule_to),
            'is_done'       => $this->is_done,
            'user'          => new UserResource($this->user),
            'files'         => ActivityFileResource::collection($this->files),
            'participants'  => ActivityParticipantResource::collection($this->participants),
            'location'      => $this->location,
            'created_at'    => $this->convertToSaoPaulo($this->created_at),
            'updated_at'    => $this->convertToSaoPaulo($this->updated_at),
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
