<div class="btn-group">
    @can('update_payment')
    <a href="{{ route('admin.payments.edit', $row->id) }}" class="btn btn-sm btn-primary">
        <i class="bi bi-pencil"></i>
    </a>
    @endcan

    @can('view_payments')
    <a href=" route('admin.payments.show', $row->id) }}" class="btn btn-sm btn-info">
        <i class="bi bi-eye"></i>
    </a>
    @endcan
    @can('delete_payment')
    <a href="javascript:void(0)" onclick="deleteResource('{{ route('admin.payments.destroy', $row->id) }}')"
        class="btn btn-sm btn-danger">
        <i class="bi bi-trash"></i>
    </a>
    @endcan
</div>