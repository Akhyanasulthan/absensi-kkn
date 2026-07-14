@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@section('content')
<div style="margin-bottom: 2.5rem;">
    <h1 style="font-size: 2.25rem; font-weight: 800; color: var(--text-main); letter-spacing: -0.04em;">Manajemen Data Mahasiswa KKN</h1>
    <p style="color: var(--text-muted); font-size: 1rem; margin-top: 0.5rem;">Daftarkan nama mahasiswa, divisi, dan kredensial akun login mereka untuk sistem absensi.</p>
</div>

@if (session('success'))
    <div style="background-color: var(--success-light); color: var(--success-hover); border: 1px solid rgba(16, 185, 129, 0.2); padding: 1.25rem; border-radius: var(--radius-md); font-size: 0.95rem; font-weight: 500; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; box-shadow: var(--shadow-sm);">
        <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif

<div style="display: flex; gap: 2.5rem; flex-wrap: wrap; align-items: flex-start;">
    
    <!-- Left Column: Add / Edit Form Card -->
    <div class="glass-card" style="flex: 1; min-width: 320px; max-width: 400px; padding: 2rem;">
        <h3 id="form-title" style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; letter-spacing: -0.01em;">
            <i data-lucide="user-plus" id="form-icon" style="color: var(--primary); width: 22px; height: 22px;"></i> <span id="form-title-text">Tambah Mahasiswa</span>
        </h3>

        <form id="student-form" action="{{ route('admin.students.store') }}" method="POST">
            @csrf
            <!-- Method spoofing placeholder for PUT requests -->
            <div id="method-container"></div>

            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label for="student_name" class="form-label">Nama Lengkap</label>
                <input type="text" name="name" id="student_name" class="form-input" placeholder="" required>
                @error('name') <span style="color: var(--danger); font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label for="student_division" class="form-label">Divisi KKN</label>
                <input type="text" name="division" id="student_division" class="form-input" placeholder="" required>
                @error('division') <span style="color: var(--danger); font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label for="student_email" class="form-label">Email Akun</label>
                <input type="email" name="email" id="student_email" class="form-input" placeholder="" required>
                @error('email') <span style="color: var(--danger); font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label for="student_password" class="form-label">Password</label>
                <div style="position: relative;">
                    <input type="password" name="password" id="student_password" class="form-input" style="padding-right: 2.5rem;" placeholder="Minimal 8 karakter..." autocomplete="new-password" required>
                    <button type="button" onclick="togglePassword()" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-muted); padding: 0; display: flex; align-items: center; justify-content: center;">
                        <i data-lucide="eye" id="password-toggle-icon" style="width: 18px; height: 18px;"></i>
                    </button>
                </div>
                <span id="password-help" style="color: var(--text-muted); font-size: 0.85rem; display: none; margin-top: 0.5rem;">Kosongkan jika tidak ingin mengubah password.</span>
                @error('password') <span style="color: var(--danger); font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <button type="submit" id="btn-submit" class="btn btn-primary" style="width: 100%; justify-content: center; font-size: 1rem; padding: 0.85rem;">
                    <i data-lucide="plus" style="width: 18px; height: 18px;"></i> <span id="btn-text">Daftarkan Mahasiswa</span>
                </button>
                <button type="button" id="btn-cancel" onclick="resetForm()" class="btn btn-outline" style="width: 100%; justify-content: center; font-size: 0.95rem; display: none;">
                    Batal / Reset Form
                </button>
            </div>
        </form>
    </div>

    <!-- Right Column: Student Table -->
    <div class="glass-card" style="flex: 2; min-width: 480px; padding: 0; overflow: hidden;">
        <div style="padding: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; border-bottom: 1px solid var(--border-color); background: rgba(255,255,255,0.5);">
            <div>
                <h3 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); letter-spacing: -0.02em;">Daftar Terdaftar <span style="color: var(--primary);">({{ $students->count() }} Orang)</span></h3>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.25rem;">Semua akun mahasiswa KKN yang berhak login</p>
            </div>
            
            <!-- Live Search input -->
            <div style="position: relative; max-width: 260px; width: 100%;">
                <input type="text" id="search-input" class="form-input" style="padding-left: 2.5rem; font-size: 0.9rem; border-radius: var(--radius-full);" placeholder="Cari nama/divisi/email...">
                <i data-lucide="search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--text-muted);"></i>
            </div>
        </div>

        <div class="table-container" style="border: none; border-radius: 0; box-shadow: none;">
            <table id="students-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Nama Mahasiswa</th>
                        <th>Email Login</th>
                        <th>Divisi</th>
                        <th style="text-align: right; width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $index => $s)
                        <tr class="student-row">
                            <td style="color: var(--text-muted); font-weight: 500;">{{ $index + 1 }}</td>
                            <td class="student-name-cell" style="font-weight: 600; color: var(--text-main);">{{ $s->name }}</td>
                            <td class="student-email-cell" style="color: var(--text-muted); font-size: 0.9rem;">{{ $s->email }}</td>
                            <td class="student-division-cell">
                                <span class="badge" style="background-color: var(--primary-light); color: var(--primary);">{{ $s->division }}</span>
                            </td>
                            <td style="text-align: right; display: flex; justify-content: flex-end; gap: 0.5rem;">
                                <button type="button" onclick="editStudent({{ $s->id }}, '{{ addslashes($s->name) }}', '{{ addslashes($s->division) }}', '{{ addslashes($s->email) }}')" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.85rem; color: var(--primary); border-color: rgba(99, 102, 241, 0.2); background-color: var(--primary-light);">
                                    <i data-lucide="edit-2" style="width: 14px; height: 14px;"></i> Edit
                                </button>
                                
                                <form action="{{ route('admin.students.delete', $s->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun mahasiswa {{ $s->name }}?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.85rem; color: var(--danger); border-color: rgba(239, 68, 68, 0.2); background-color: var(--danger-light);">
                                        <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-row">
                            <td colspan="5" style="padding: 4rem 2rem; text-align: center; color: var(--text-muted);">
                                <i data-lucide="users" style="margin: 0 auto 1rem auto; display: block; width: 48px; height: 48px; opacity: 0.5;"></i>
                                <span style="font-size: 1.1rem; font-weight: 500;">Belum ada data mahasiswa terdaftar.</span>
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
        passwordInput.placeholder = "Kosongkan jika tidak ingin diubah...";
        document.getElementById('password-help').style.display = 'block';
        
        // Update Title & Button text
        document.getElementById('form-title-text').innerText = 'Ubah Data Mahasiswa';
        document.getElementById('form-icon').setAttribute('data-lucide', 'user-cog');
        document.getElementById('btn-text').innerText = 'Simpan Perubahan';
        
        const btnSubmit = document.getElementById('btn-submit');
        btnSubmit.classList.remove('btn-primary');
        btnSubmit.classList.add('btn-success');
        
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
        
        document.getElementById('form-title-text').innerText = 'Tambah Mahasiswa';
        document.getElementById('form-icon').setAttribute('data-lucide', 'user-plus');
        document.getElementById('btn-text').innerText = 'Daftarkan Mahasiswa';
        
        const btnSubmit = document.getElementById('btn-submit');
        btnSubmit.classList.remove('btn-success');
        btnSubmit.classList.add('btn-primary');
        
        document.getElementById('btn-cancel').style.display = 'none';
        
        lucide.createIcons();
    }

    function togglePassword() {
        const passwordInput = document.getElementById('student_password');
        const icon = document.getElementById('password-toggle-icon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.setAttribute('data-lucide', 'eye-off');
        } else {
            passwordInput.type = 'password';
            icon.setAttribute('data-lucide', 'eye');
        }
        lucide.createIcons();
    }
</script>
@endsection
