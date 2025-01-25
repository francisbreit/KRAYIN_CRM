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
            'schedule_from' => $this->formatDate($this->schedule_from),
            'schedule_to'   => $this->formatDate($this->schedule_to),
            'is_done'       => $this->is_done,
            'user'          => new UserResource($this->user),
            'files'         => ActivityFileResource::collection($this->files),
            'participants'  => ActivityParticipantResource::collection($this->participants),
            'location'      => $this->location,
            'created_at'    => $this->formatDate($this->created_at),
            'updated_at'    => $this->formatDate($this->updated_at),
        ];
    }

    /**
     * Format the date to 'Y-m-d H:i:s' in the São Paulo timezone.
     *
     * @param  string|null $date
     * @return string|null
     */
    private function formatDate($date)
    {
        return $date ? Carbon::parse($date)->timezone('America/Sao_Paulo')->format('Y-m-d H:i:s') : null;
    }
}
