<div class="btn-group">
    @can('update_batch')
        <a href="{{ route('admin.batches.edit', $row->id) }}" class="btn btn-sm btn-primary">
            <i class="bi bi-pencil"></i>
        </a>
    @endcan

    @can('delete_batch')
        <a href="javascript:void(0)" onclick="deleteResource('{{ route('admin.batches.destroy', $row->id) }}')"
            class="btn btn-sm btn-danger">
            <i class="bi bi-trash"></i>
        </a>
    @endcan
</div>