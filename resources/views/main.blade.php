<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">
</head>
<body class="bg-light">

    <div class="container py-5 d-flex flex-column align-items-center">

        <!-- Logo dan Judul -->
        <img src="{{ asset('img/Group 9.png') }}" alt="logo" width="90" height="90" class="rounded-circle">
        <h1 class="fw-bold text-uppercase mt-3">To Do List</h1>

        <!-- Form Tambah Todo -->
        <form action="/store" method="POST" class="w-100 max-w-500 mt-4 mb-4">
            @csrf

            <div class="mb-3">
                <input type="text" name="title" class="form-control shadow-sm" placeholder="Add your task here" required>
            </div>

            <div class="mb-3">
                <input type="datetime-local" name="datetime" class="form-control shadow-sm" required>
            </div>

            <div class="mb-3">
                <select name="priority" class="form-select shadow-sm">
                    <option value="low">Low Priority</option>
                    <option value="medium" selected>Medium Priority</option>
                    <option value="high">High Priority</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">ADD</button>
        </form>

        <!-- Daftar Todo -->
        <div class="bg-white p-4 rounded shadow w-100 max-w-500 overflow-auto" style="max-height: 300px;">
            <ul class="list-group">
                @foreach($todos as $todo)
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="form-check me-2">
                            <form action="/complete/{{ $todo->id }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="checkbox" onchange="this.form.submit()" class="form-check-input" {{ $todo->completed ? 'checked' : '' }}>
                            </form>
                        </div>

                        <div class="flex-grow-1 me-3">
                            <span class="badge 
                                {{ $todo->priority === 'low' ? 'bg-success' : 
                                   ($todo->priority === 'medium' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                {{ ucfirst($todo->priority) }}
                            </span>

                            <div class="mt-2">
                                <div class="fw-medium {{ $todo->completed ? 'text-decoration-line-through text-muted' : '' }}">
                                    {{ $todo->title }}
                                </div>
                                <small class="{{ $todo->completed ? 'text-muted text-decoration-line-through' : 'text-muted' }}">
                                    {{ \Carbon\Carbon::parse($todo->datetime)->format('d M Y, H:i') }}
                                </small>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <button onclick="openEditModal('{{ $todo->id }}', '{{ $todo->title }}', '{{ $todo->datetime }}')" class="btn btn-sm btn-outline-primary">
                                <i class="ri-edit-line"></i>
                            </button>

                            <form id="deleteForm-{{ $todo->id }}" action="/delete/{{ $todo->id }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete({{ $todo->id }})" class="btn btn-sm btn-outline-danger">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Task</h5>
                        <button type="button" class="btn-close" onclick="closeEditModal()"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editId" name="id">

                        <div class="mb-3">
                            <input type="text" id="editTitle" name="title" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <input type="datetime-local" id="editDatetime" name="datetime" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <select name="priority" class="form-select shadow-sm">
                                <option value="low">Low Priority</option>
                                <option value="medium" selected>Medium Priority</option>
                                <option value="high">High Priority</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">UPDATE</button>
                        <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap & Modal JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function openEditModal(id, title, datetime) {
            const modal = new bootstrap.Modal(document.getElementById('editModal'));
            document.getElementById('editForm').action = "/edit/" + id;
            document.getElementById('editTitle').value = title;
            document.getElementById('editDatetime').value = datetime;
            modal.show();
        }

        function closeEditModal() {
            const modalElement = document.getElementById('editModal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            modal.hide();
        }

        function confirmDelete(todoId) {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm-' + todoId).submit();
                } 
            });
        }
    </script>
</body>
</html>
