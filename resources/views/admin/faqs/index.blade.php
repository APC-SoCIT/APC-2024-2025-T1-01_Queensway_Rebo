@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-semibold text-gray-800 mb-0">❓ FAQ List</h2>
            <a href="{{ route('admin.faqs.create') }}" class="btn btn-indigo d-flex align-items-center gap-2">
                <i class="fas fa-plus-circle"></i> <span>Add New FAQ</span>
            </a>
        </div>

        <!-- Search Bar -->
        <div class="mb-4" style="max-width: 350px;">
            <div class="position-relative">
                <input id="search-input" type="text" class="form-control ps-5 shadow-sm" placeholder="Search question...">
            </div>
        </div>

        <!-- FAQ Table -->
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="faq-table" class="table table-hover align-middle mb-0">
                        <thead class="bg-indigo text-white">
                            <tr>
                                <th data-sort="number" class="sortable">ID <i class="fas fa-sort"></i></th>
                                <th data-sort="string" class="sortable">Question <i class="fas fa-sort"></i></th>
                                <th data-sort="string" class="sortable">Answer <i class="fas fa-sort"></i></th>
                                <th data-sort="string" class="sortable">Keywords <i class="fas fa-sort"></i></th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($faqs as $faq)
                                <tr>
                                    <td class="fw-semibold text-gray-800">{{ $faq->id }}</td>
                                    <td>{{ $faq->question }}</td>
                                    <td>{{ Str::limit($faq->answer, 60) }}</td>
                                    <td><span class="badge bg-light text-gray-700 border">{{ $faq->keywords }}</span></td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="action-btn edit-btn"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Delete this FAQ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn delete-btn" title="Delete">
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
        </div>
    </div>

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <style>
        /* ===== Modern Dashboard Theme (Matches Products Page) ===== */
        .text-gray-700 {
            color: #374151 !important;
        }

        .text-gray-800 {
            color: #1f2937 !important;
        }

        .text-gray-500 {
            color: #6b7280 !important;
        }

        .bg-indigo {
            background-color: #4f46e5 !important;
        }

        .btn-indigo {
            background: #4f46e5;
            color: #fff;
            border: none;
            transition: all 0.2s ease-in-out;
        }

        .btn-indigo:hover {
            background: #4338ca;
            color: #fff;
        }

        .btn-outline-indigo {
            border: 1.5px solid #4f46e5;
            color: #4f46e5;
            background: transparent;
            transition: all 0.2s ease-in-out;
        }

        .btn-outline-indigo:hover {
            background: #4f46e5;
            color: #fff;
        }

        .btn-outline-danger {
            border: 1.5px solid #dc2626;
            color: #dc2626;
            background: transparent;
            transition: all 0.2s ease-in-out;
        }

        .btn-outline-danger:hover {
            background: #dc2626;
            color: #fff;
        }

        .table thead th {
            font-weight: 600;
            vertical-align: middle;
            padding: 0.85rem 1rem;
            font-size: 0.9rem;
        }

        .table tbody td {
            font-size: 0.9rem;
            color: #374151;
            padding: 0.85rem 1rem;
        }

        .table-hover tbody tr:hover {
            background-color: #f9fafb;
        }

        .sortable {
            cursor: pointer;
            user-select: none;
        }

        .sortable i {
            margin-left: 4px;
            font-size: 0.8rem;
            opacity: 0.7;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.4rem 0.6rem;
            border-radius: 0.5rem;
        }

        /* ===== Action Buttons (Same as Products Page) ===== */
        .action-btn {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: none;
            font-size: 16px;
            transition: all 0.2s ease-in-out;
            color: #fff !important;
        }

        .edit-btn {
            background-color: #facc15;
            /* yellow-400 */
        }

        .edit-btn:hover {
            background-color: #eab308;
            /* yellow-500 */
        }

        .delete-btn {
            background-color: #ef4444;
            /* red-500 */
        }

        .delete-btn:hover {
            background-color: #dc2626;
            /* red-600 */
        }
    </style>

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
                    headers.forEach(h => {
                        const icon = h.querySelector('i');
                        if (h !== header) icon.className = 'fas fa-sort';
                    });
                    const icon = header.querySelector('i');
                    icon.className = sortDirection === 1 ? 'fas fa-sort-up' : 'fas fa-sort-down';
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