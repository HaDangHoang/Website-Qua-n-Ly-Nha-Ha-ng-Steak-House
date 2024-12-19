<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Table Layout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Layout chung cho danh sách bàn */
        .table-layout {
            display: grid;
            grid-template-columns: repeat(4, 100px); /* Điều chỉnh để có 4 cột */
            gap: 15px;
            padding: 20px;
            background-color: #ffffff;
         
        }

        .table {
            width: 100px;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 2px solid #cccccc;
            border-radius: 10px;
            cursor: pointer;
            background-color: #ffffff;
            transition: background-color 0.3s, border-color 0.3s;
        }

    .selected {
            background-color: #28a745;
            border-color: #28a745;
        }

    .Reserved {
            background-color: #fff9c4;
            border-color: #fdd835;
            cursor: not-allowed;
        }

    .Occupied {
            background-color: #ffcccb;
            border-color: #e53935;
            cursor: not-allowed;
        }

        .table-number {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        /* Căn chỉnh modal */
        .modal-dialog {
            max-width: 500px; /* Điều chỉnh độ rộng của modal */
            margin: 1.75rem auto;
        }

        .modal-dialog-centered {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-body {
            display: grid;
            grid-template-columns: repeat(3, 100px); /* 3 cột trong popup modal */
            gap: 10px;
        }

        .modal-footer button {
            margin: 5px;
        }

        button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
    </style>
</head>

<body>
    <h1>Danh sách bàn</h1>

    <form action="{{ route('admin.submit.tables') }}" method="POST" id="reservationForm">
        @csrf
        <input id="reservation_id" type="hidden" name="reservation_id" value="{{ $reservationId }}">
        <div class="table-layout" id="table-layout">
            @foreach ($tables as $table)
            {{-- {{dd($table)}} --}}
            <div class="table {{ $table['status'] === 'Reserved' ? 'Reserved' : '' }} {{ $table['status'] === 'Occupied' ? 'Occupied' : 'Available' }}"
                 data-id="{{ $table['table_id'] }}" data-status="{{ $table['status'] }}">
                <span class="table-name">{{ $table['name'] }}</span>
            </div>
        @endforeach
        
        </div>

        <button type="submit" id="confirmSelection">Xác nhận</button>
    </form>

    <script>
        document.querySelectorAll('.table').forEach(table => {
            table.addEventListener('click', () => {
                const status = table.getAttribute('data-status');
                if (status === 'Reserved' || status === 'Occupied') {
    alert('Bàn này đã được đặt hoặc đang có khách.');
}
 else {
                    table.classList.toggle('selected');
                }
            });
        });

        document.getElementById('reservationForm').addEventListener('submit', (event) => {
            event.preventDefault(); 
            const selectedTables = Array.from(document.querySelectorAll('.table.selected'))
                .map(table => table.getAttribute('data-id'));
            selectedTables.forEach(tableId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'tables[]'; 
                input.value = tableId;
                document.getElementById('reservationForm').appendChild(input);
            });
            document.getElementById('reservationForm').submit();
        });

        function submitModal() {
            const selectedTable = document.querySelector('.table.selected');
            if (selectedTable) {
                const dataId = selectedTable.getAttribute('data-id');
                const reservationId = document.getElementById('reservation_id').value;
                $.ajax({
                    url: '{{ route('admin.submit.Movetables') }}',
                    type: 'POST',
                    data: {
                        dataId: dataId,
                        reservationId: reservationId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message); 
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Có lỗi xảy ra');
                    }
                });
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
