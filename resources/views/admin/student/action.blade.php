<div class="btn-group">
    @can('update_student')
        <a href="{{ route('admin.students.edit', $row->id) }}" class="btn btn-sm btn-primary">
            <i class="bi bi-pencil"></i>
        </a>
    @endcan

    @can('delete_student')
        <a href="javascript:void(0)" onclick="deleteResource('{{ route('admin.students.destroy', $row->id) }}')"
            class="btn btn-sm btn-danger">
            <i class="bi bi-trash"></i>
        </a>
    @endcan
</div>