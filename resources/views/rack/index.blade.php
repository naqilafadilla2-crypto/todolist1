@extends('layouts.sidebar')

@section('title', 'Rack Management')

@section('content')
<div style="padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Rack Management</h2>
        <button onclick="openRackModal()" style="padding: 10px 20px; background: #2c2f7e; color: white; border: none; border-radius: 5px; cursor: pointer;">
            + Tambah Rack
        </button>
    </div>

    @livewire('rack-management')
</div>

<!-- Rack Modal -->
<div id="rackModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); align-items: center; justify-content: center;">
    <div style="background: white; padding: 20px; border-radius: 8px; width: 90%; max-width: 500px;">
        <h3>Tambah Rack</h3>
        <form action="{{ route('rack.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 15px;">
                <label>Nama Rack:</label>
                <input type="text" name="name" required style="width: 100%; padding: 8px; margin-top: 5px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label>Deskripsi:</label>
                <textarea name="description" style="width: 100%; padding: 8px; margin-top: 5px;"></textarea>
            </div>
            <div style="margin-bottom: 15px;">
                <label>Total Unit (U):</label>
                <input type="number" name="total_units" value="42" min="1" max="100" required style="width: 100%; padding: 8px; margin-top: 5px;">
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeRackModal()" style="padding: 10px 20px; background: #ccc; border: none; border-radius: 5px; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 10px 20px; background: #2c2f7e; color: white; border: none; border-radius: 5px; cursor: pointer;">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openRackModal() {
    document.getElementById('rackModal').style.display = 'flex';
}

function closeRackModal() {
    document.getElementById('rackModal').style.display = 'none';
}
</script>
@endsection
