@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">FAQ List</h2>

    <a href="{{ route('admin.faqs.create') }}" class="btn btn-success mb-3">
        <i class="fas fa-plus-circle"></i> Add New FAQ
    </a>

    <!-- Search bar -->
    <div class="mb-3" style="max-width: 300px;">
        <input id="search-input" type="text" class="form-control ps-5" placeholder="Search question...">
    </div>

    <!-- FAQ Table -->
    <div class="table-responsive">
        <table id="faq-table" class="table table-bordered table-striped small">
            <thead class="table-dark">
                <tr>
                    <th data-sort="number" style="cursor:pointer;">ID <i class="fas fa-sort"></i></th>
                    <th data-sort="string" style="cursor:pointer;">Question <i class="fas fa-sort"></i></th>
                    <th data-sort="string" style="cursor:pointer;">Answer <i class="fas fa-sort"></i></th>
                    <th data-sort="string" style="cursor:pointer;">Keywords <i class="fas fa-sort"></i></th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($faqs as $faq)
                    <tr>
                        <td>{{ $faq->id }}</td>
                        <td>{{ $faq->question }}</td>
                        <td>{{ Str::limit($faq->answer, 50) }}</td>
                        <td>{{ $faq->keywords }}</td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center flex-wrap">
                                <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this FAQ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- FontAwesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>

{{-- Search + Sort --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.getElementById('faq-table');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const searchInput = document.getElementById('search-input');

        // Search functionality
        searchInput.addEventListener('input', () => {
            const query = searchInput.value.toLowerCase();
            rows.forEach(row => {
                const question = row.cells[1].textContent.toLowerCase();
                row.style.display = question.includes(query) ? '' : 'none';
            });
        });

        // Sort functionality
        let sortColumnIndex = null;
        let sortDirection = 1;

        const headers = table.querySelectorAll('thead th[data-sort]');
        headers.forEach((header, index) => {
            header.addEventListener('click', () => {
                const type = header.getAttribute('data-sort');
                if (sortColumnIndex === index) {
                    sortDirection = -sortDirection;
                } else {
                    sortColumnIndex = index;
                    sortDirection = 1;
                }

                // Reset icons
                headers.forEach(h => {
                    const icon = h.querySelector('i');
                    if (h !== header) icon.className = 'fas fa-sort';
                });

                // Set icon direction
                const icon = header.querySelector('i');
                icon.className = sortDirection === 1 ? 'fas fa-sort-up' : 'fas fa-sort-down';

                // Sort rows
                const sortedRows = rows
                    .filter(row => row.style.display !== 'none')
                    .sort((a, b) => {
                        let aText = a.cells[index].textContent.trim();
                        let bText = b.cells[index].textContent.trim();

                        if (type === 'number') {
                            aText = parseFloat(aText) || 0;
                            bText = parseFloat(bText) || 0;
                        } else {
                            aText = aText.toLowerCase();
                            bText = bText.toLowerCase();
                        }

                        return (aText < bText ? -1 : aText > bText ? 1 : 0) * sortDirection;
                    });

                sortedRows.forEach(row => tbody.appendChild(row));
            });
        });
    });
</script>
@endsection
