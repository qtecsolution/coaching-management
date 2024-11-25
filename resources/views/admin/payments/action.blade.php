<div class="btn-group">
    @can('update_payment')
    <a href="{{ route('admin.payments.edit', $row->id) }}" class="btn btn-sm btn-primary">
        <i class="bi bi-pencil"></i>
    </a>
    @endcan

    @can('view_payment')
    <a href=" route('admin.batches.show', $row->id) }}" class="btn btn-sm btn-info">
        <i class="bi bi-eye"></i>
    </a>
    @endcan
</div>