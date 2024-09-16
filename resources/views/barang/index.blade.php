@extends('layout')

@section('content')
    <h1>List of Barang</h1>

    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">
        Create New Barang
    </button>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
            {!! $message !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times"></i></span>
            </button>
        </div>
    @endif

    <table id="tengplate" class="table table-striped mt-3">
        <thead>
            <tr>
                @foreach ($columns as $column)
                    <th>{{ ucfirst($column) }}</th>
                @endforeach
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($barang as $item)
                <tr>
                    @foreach ($columns as $column)
                        <td>{{ $item->$column }}</td> 
                    @endforeach
                    <td>
                        <!-- Button to Open the Edit Modal -->
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#editModal{{ $item->id }}">
                            <i class="fas fa-edit"></i>
                        </button>
    
                        <form action="{{ route('barang.destroy', $item->id) }}" method="POST" style="display:inline;" id="delete-form-{{ $item->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $item->id }}, '{{ $item->name }}')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @include('barang.modal.create')
    @include('barang.modal.edit')

    <script>
$(document).ready(function() {
    @if ($errors->any())
        @php
            $formType = old('form_type');
        @endphp
        @if ($formType == 'create')
            $('#createModal').modal('show');
        @elseif (strpos($formType, 'edit-') === 0)
            @php
                $id = substr($formType, 5); 
            @endphp
            var modalId = '#editModal' + '{{ $id }}'; 
            $(modalId).modal('show');
        @endif
    @endif
});

        function confirmDelete(itemId, itemName) {
        Swal.fire({
            title: `Are you sure you want to delete <b>${itemName}</b>?`,
            showDenyButton: true,
            showCancelButton: false, 
            confirmButtonText: "Yes",
            denyButtonText: "No",
            icon: "question"
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form to delete the item
                document.getElementById('delete-form-' + itemId).submit();
                Swal.fire("Deleted!", "Your item has been deleted.", "success");
            } else if (result.isDenied) {
                Swal.fire("Item not deleted", "Your item is safe!", "info");
            }
        });
    }

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.modal').forEach(function(modal) {
        const form = modal.querySelector('form');
        const originalValues = {};
        
        // Store the original values when the modal opens
        form.querySelectorAll('input, textarea').forEach(function(input) {
            originalValues[input.name] = input.value;
        });
        
        // Function to check if values have changed
        function checkIfChanged() {
            let isChanged = false;
            form.querySelectorAll('input, textarea').forEach(function(input) {
                if (input.value !== originalValues[input.name]) {
                    isChanged = true;
                }
            });
            // Enable or disable the submit button based on changes
            form.querySelector('button[type="submit"]').disabled = !isChanged;
        }
        
        // Add event listeners to all input fields to check changes
        form.querySelectorAll('input, textarea').forEach(function(input) {
            input.addEventListener('input', checkIfChanged);
        });

        // Initialize the button state
        checkIfChanged();
    });
});

    </script>
@endsection
