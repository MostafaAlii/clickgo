<!-- Modal -->
<div class="modal fade" id="documentsModal{{ $profession->id }}" tabindex="-1"
    aria-labelledby="documentsModalLabel{{ $profession->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentsModalLabel{{ $profession->id }}">
                    مستندات المهنة: {{ $profession->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                @if($profession->documents && $profession->documents->count())
                <ul class="list-group">
                    @foreach($profession->documents as $document)
                    <li class="list-group-item">
                        {{ $document->name }}
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="text-danger">لا يوجد مستندات</div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
