<div class="btn-group">
    @can('update_lead')
        <a href="{{ route('admin.levels.edit', $row->id) }}" class="btn btn-sm btn-primary" data-bs-toggle="modal"
            data-bs-target="#editLevelModal_{{ $row->id }}">
            <i class="bi bi-pencil"></i>
        </a>
    @endcan

    @can('delete_lead')
        <a href="javascript:void(0)" onclick="deleteResource('{{ route('admin.levels.destroy', $row->id) }}')"
            class="btn btn-sm btn-danger">
            <i class="bi bi-trash"></i>
        </a>
    @endcan
</div>

<!-- Edit Level Modal -->
<div class="modal fade" id="editLevelModal_{{ $row->id }}" tabindex="-1" aria-labelledby="editLevelModalLabel_{{ $row->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editLevelModalLabel_{{ $row->id }}">Edit Level</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.levels.update', $row->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name" class="form-label">Name<sup class="text-danger">*</sup></label>
                        <input type="text" value="{{ $row->name }}" name="name" id="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="status" class="form-label">Status<sup class="text-danger">*</sup></label>
                        <select name="status" id="status" class="form-control">
                            <option value="1" {{ $row->status == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $row->status == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
