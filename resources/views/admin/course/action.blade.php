<div class="btn-group">
    @can('update_course')
        <a href="{{ route('admin.courses.edit', $row->id) }}" class="btn btn-sm btn-primary">
            <i class="bi bi-pencil"></i>
        </a>
    @endcan

    @can('delete_course')
        <a href="javascript:void(0)" onclick="deleteResource('{{ route('admin.courses.destroy', $row->id) }}')"
            class="btn btn-sm btn-danger">
            <i class="bi bi-trash"></i>
        </a>
    @endcan
</div>