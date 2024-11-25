<div class="btn-group">
    @can('update_user')
        <a href="{{ route('admin.users.edit', $row->id) }}" class="btn btn-sm btn-primary">
            <i class="bi bi-pencil"></i>
        </a>
    @endcan

    @can('delete_user')
        <a href="javascript:void(0)" onclick="deleteResource('{{ route('admin.users.destroy', $row->id) }}')"
            class="btn btn-sm btn-danger">
            <i class="bi bi-trash"></i>
        </a>
    @endcan
</div>