@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@section('content')
<div style="margin-bottom: 2.5rem;">
    <h1 style="font-family: var(--font-heading); font-size: 2.5rem; color: var(--woody-blue); letter-spacing: 1px; text-shadow: 2px 2px 0 white;">Koleksi Mainan KKN</h1>
    <p style="color: var(--text-main); font-size: 1.1rem; margin-top: 0.5rem; font-weight: 700;">Daftarkan nama pemain, divisi, dan kredensial akun login mereka untuk sistem absensi.</p>
</div>

@if (session('success'))
    <div style="background-color: var(--buzz-green); color: white; border: 4px solid var(--buzz-green-dark); padding: 1.25rem; border-radius: 16px; font-family: var(--font-heading); font-size: 1.1rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; text-shadow: 1px 1px 0 var(--buzz-green-dark);">
        <i data-lucide="check-circle" style="width: 24px; height: 24px;"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif

<div style="display: flex; gap: 2.5rem; flex-wrap: wrap; align-items: flex-start;">
    
    <!-- Left Column: Add / Edit Form Card -->
    <div class="toy-card" style="flex: 1; min-width: 320px; max-width: 400px; padding: 2rem; border-color: var(--buzz-purple);">
        <h3 id="form-title" style="font-family: var(--font-heading); font-size: 1.4rem; color: var(--buzz-purple); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i data-lucide="user-plus" id="form-icon" style="width: 24px; height: 24px;"></i> <span id="form-title-text">Tambah Mainan Baru</span>
        </h3>

        <form id="student-form" action="{{ route('admin.students.store') }}" method="POST">
            @csrf
            <!-- Method spoofing placeholder for PUT requests -->
            <div id="method-container"></div>

            <div style="margin-bottom: 1.25rem;">
                <label for="student_name" class="toy-label">Nama Lengkap</label>
                <input type="text" name="name" id="student_name" class="toy-input" required>
                @error('name') <span style="color: var(--woody-red); font-weight: 700; font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label for="student_division" class="toy-label">Divisi KKN</label>
                <input type="text" name="division" id="student_division" class="toy-input" required>
                @error('division') <span style="color: var(--woody-red); font-weight: 700; font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label for="student_email" class="toy-label">Email Akun</label>
                <input type="email" name="email" id="student_email" class="toy-input" required>
                @error('email') <span style="color: var(--woody-red); font-weight: 700; font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 2rem;">
                <label for="student_password" class="toy-label">Password</label>
                <div style="position: relative;">
                    <input type="password" name="password" id="student_password" class="toy-input" style="padding-right: 3rem; margin-bottom: 0;" placeholder="Minimal 8 karakter..." autocomplete="new-password" required>
                    <button type="button" onclick="togglePassword()" style="position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%); background: #E5E7EB; border: 2px solid #9CA3AF; border-radius: 8px; cursor: pointer; color: #4B5563; padding: 0.3rem; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease;">
                        <i data-lucide="eye" id="password-toggle-icon" style="width: 18px; height: 18px;"></i>
                    </button>
                </div>
                <span id="password-help" style="color: var(--text-muted); font-weight: 700; font-size: 0.85rem; display: none; margin-top: 0.5rem;">Kosongkan jika tidak ingin mengubah password.</span>
                @error('password') <span style="color: var(--woody-red); font-weight: 700; font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <button type="submit" id="btn-submit" class="toy-btn" style="width: 100%;">
                    <i data-lucide="plus" style="width: 20px; height: 20px;"></i> <span id="btn-text">Daftarkan Mainan</span>
                </button>
                <button type="button" id="btn-cancel" onclick="resetForm()" class="toy-btn toy-btn-danger" style="width: 100%; display: none;">
                    BATAL / RESET
                </button>
            </div>
        </form>
    </div>

    <!-- Right Column: Student Table -->
    <div class="toy-card" style="flex: 2; min-width: 480px; padding: 0; overflow: hidden; border-color: var(--woody-blue);">
        <div style="padding: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; border-bottom: 4px solid var(--woody-blue); background: #EFF6FF;">
            <div>
                <h3 style="font-family: var(--font-heading); font-size: 1.4rem; color: var(--woody-blue);">Daftar Mainan <span style="color: var(--woody-red);">({{ $students->count() }} Orang)</span></h3>
                <p style="color: var(--text-muted); font-size: 1rem; margin-top: 0.25rem; font-weight: 700;">Semua akun pemain yang bisa masuk sistem</p>
            </div>
            
            <!-- Live Search input -->
            <div style="position: relative; max-width: 260px; width: 100%;">
                <input type="text" id="search-input" class="toy-input" style="padding-left: 2.75rem; border-radius: 99px; margin-bottom: 0;" placeholder="Cari mainan...">
                <i data-lucide="search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 20px; height: 20px; color: var(--woody-blue);"></i>
            </div>
        </div>

        <div class="table-container" style="border: none; border-radius: 0; box-shadow: none;">
            <table id="students-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Nama Mainan</th>
                        <th>Email Login</th>
                        <th>Divisi</th>
                        <th style="text-align: right; width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $index => $s)
                        <tr class="student-row">
                            <td style="color: var(--text-muted); font-weight: 800;">{{ $index + 1 }}</td>
                            <td class="student-name-cell" style="font-weight: 800; color: var(--woody-blue);">{{ $s->name }}</td>
                            <td class="student-email-cell" style="color: var(--text-muted); font-weight: 700;">{{ $s->email }}</td>
                            <td class="student-division-cell">
                                <span class="badge badge-warning">{{ $s->division }}</span>
                            </td>
                            <td style="text-align: right; display: flex; justify-content: flex-end; gap: 0.5rem;">
                                <button type="button" onclick="editStudent({{ $s->id }}, '{{ addslashes($s->name) }}', '{{ addslashes($s->division) }}', '{{ addslashes($s->email) }}')" class="toy-btn toy-btn-small" style="background: var(--woody-blue); border-color: #1D4ED8; text-shadow: 1px 1px 0 #1D4ED8;">
                                    <i data-lucide="edit-2" style="width: 14px; height: 14px;"></i> Edit
                                </button>
                                
                                <form action="{{ route('admin.students.delete', $s->id) }}" method="POST" onsubmit="return confirm('Yakin hapus mainan {{ $s->name }}?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="toy-btn toy-btn-small toy-btn-danger">
                                        <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-row">
                            <td colspan="5" style="padding: 4rem 2rem; text-align: center; color: var(--text-muted);">
                                <i data-lucide="users" style="margin: 0 auto 1rem auto; display: block; width: 64px; height: 64px; opacity: 0.3; color: var(--woody-blue);"></i>
                                <span style="font-family: var(--font-heading); font-size: 1.2rem;">Kotak mainan masih kosong.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Live Search Logic
    const searchInput = document.getElementById('search-input');
    const tableRows = document.querySelectorAll('.student-row');

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        
        tableRows.forEach(row => {
            const name = row.querySelector('.student-name-cell').textContent.toLowerCase();
            const email = row.querySelector('.student-email-cell').textContent.toLowerCase();
            const division = row.querySelector('.student-division-cell').textContent.toLowerCase();
            
            if (name.includes(query) || email.includes(query) || division.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Edit Mode Form Switcher
    function editStudent(id, name, division, email) {
        // Change Form Action & Method
        const form = document.getElementById('student-form');
        form.action = `/admin/students/${id}`;
        
        const methodContainer = document.getElementById('method-container');
        methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        // Fill fields
        document.getElementById('student_name').value = name;
        document.getElementById('student_division').value = division;
        document.getElementById('student_email').value = email;
        
        // Set password optional
        const passwordInput = document.getElementById('student_password');
        passwordInput.required = false;
        passwordInput.placeholder = "Kosongkan jika tidak ubah...";
        document.getElementById('password-help').style.display = 'block';
        
        // Update Title & Button text
        document.getElementById('form-title-text').innerText = 'Ubah Mainan';
        document.getElementById('form-icon').setAttribute('data-lucide', 'user-cog');
        document.getElementById('btn-text').innerText = 'Simpan Perubahan';
        
        const btnSubmit = document.getElementById('btn-submit');
        btnSubmit.style.background = 'var(--woody-yellow)';
        btnSubmit.style.borderColor = 'var(--woody-brown)';
        btnSubmit.style.color = 'var(--woody-brown)';
        btnSubmit.style.textShadow = 'none';
        
        // Show cancel button
        document.getElementById('btn-cancel').style.display = 'inline-flex';
        
        // Focus first field
        document.getElementById('student_name').focus();
        
        lucide.createIcons();
    }

    // Reset Form to Add Mode
    function resetForm() {
        const form = document.getElementById('student-form');
        form.action = "{{ route('admin.students.store') }}";
        form.reset();
        
        const methodContainer = document.getElementById('method-container');
        methodContainer.innerHTML = '';
        
        // Restore password requirement
        const passwordInput = document.getElementById('student_password');
        passwordInput.required = true;
        passwordInput.placeholder = "Minimal 8 karakter...";
        document.getElementById('password-help').style.display = 'none';
        
        document.getElementById('form-title-text').innerText = 'Tambah Mainan Baru';
        document.getElementById('form-icon').setAttribute('data-lucide', 'user-plus');
        document.getElementById('btn-text').innerText = 'Daftarkan Mainan';
        
        const btnSubmit = document.getElementById('btn-submit');
        btnSubmit.style = 'width: 100%;'; // reset inline styles
        
        document.getElementById('btn-cancel').style.display = 'none';
        
        lucide.createIcons();
    }

    function togglePassword() {
        const passwordInput = document.getElementById('student_password');
        const icon = document.getElementById('password-toggle-icon');
        const btn = icon.parentElement;
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.setAttribute('data-lucide', 'eye-off');
            btn.style.background = 'var(--woody-blue)';
            btn.style.borderColor = '#1D4ED8';
            btn.style.color = 'white';
        } else {
            passwordInput.type = 'password';
            icon.setAttribute('data-lucide', 'eye');
            btn.style.background = '#E5E7EB';
            btn.style.borderColor = '#9CA3AF';
            btn.style.color = '#4B5563';
        }
        lucide.createIcons();
    }
</script>
@endsection
